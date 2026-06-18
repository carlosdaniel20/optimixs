<?php

namespace App\Controllers\Api;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class RiskEvidenceController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getEvidencias(Request $request, Response $response, array $args): Response
    {
        try {
            $riesgoId = (int)($args['riesgo_id'] ?? 0);
            $nombreChecklist = urldecode($args['nombre_checklist'] ?? '');
            
            // Verificar si la tabla existe
            $tableCheck = $this->db->query("SHOW TABLES LIKE 'risk_checklist_evidencias'");
            if ($tableCheck->rowCount() == 0) {
                $response->getBody()->write(json_encode(['success' => false, 'error' => 'La tabla no existe']));
                return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
            }
            
            $stmt = $this->db->prepare("SELECT id, url, nombre_original, created_at FROM risk_checklist_evidencias WHERE riesgo_id = ? AND nombre_checklist = ? ORDER BY created_at DESC");
            $stmt->execute([$riesgoId, $nombreChecklist]);
            $evidencias = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            $response->getBody()->write(json_encode(['success' => true, 'data' => $evidencias]));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['success' => false, 'error' => $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    public function uploadEvidencia(Request $request, Response $response): Response
    {
        try {
            $uploadDir = __DIR__ . '/../../../public/uploads/evidencias/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $riesgoId = (int)($_POST['riesgo_id'] ?? 0);
            $nombreChecklist = $_POST['nombre_checklist'] ?? '';
            
            if (!$riesgoId || !$nombreChecklist) {
                $response->getBody()->write(json_encode(['success' => false, 'error' => 'Faltan datos']));
                return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
            }
            
            if (!isset($_FILES['evidencia_archivo']) || $_FILES['evidencia_archivo']['error'] !== UPLOAD_ERR_OK) {
                $response->getBody()->write(json_encode(['success' => false, 'error' => 'No hay archivo']));
                return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
            }
            
            $file = $_FILES['evidencia_archivo'];
            $filename = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file['name']);
            $filepath = $uploadDir . $filename;
            
            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                $url = '/uploads/evidencias/' . $filename;
                
                // Verificar si la tabla existe antes de insertar
                $tableCheck = $this->db->query("SHOW TABLES LIKE 'risk_checklist_evidencias'");
                if ($tableCheck->rowCount() == 0) {
                    // Crear la tabla si no existe
                    $this->db->exec("CREATE TABLE IF NOT EXISTS `risk_checklist_evidencias` (
                        `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
                        `riesgo_id` INT NOT NULL,
                        `nombre_checklist` VARCHAR(255) NOT NULL,
                        `url` VARCHAR(500) NOT NULL,
                        `nombre_original` VARCHAR(255) NULL,
                        `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
                }
                
                $stmt = $this->db->prepare("INSERT INTO risk_checklist_evidencias (riesgo_id, nombre_checklist, url, nombre_original) VALUES (?, ?, ?, ?)");
                $stmt->execute([$riesgoId, $nombreChecklist, $url, $file['name']]);
                
                $response->getBody()->write(json_encode([
                    'success' => true,
                    'evidencia' => [
                        'id' => $this->db->lastInsertId(),
                        'url' => $url,
                        'nombre_original' => $file['name']
                    ]
                ]));
                return $response->withHeader('Content-Type', 'application/json');
            }
            
            $response->getBody()->write(json_encode(['success' => false, 'error' => 'Error al mover el archivo']));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['success' => false, 'error' => $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    public function deleteEvidencia(Request $request, Response $response): Response
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true) ?: $_POST;
            $evidenciaId = (int)($data['evidencia_id'] ?? 0);
            
            $stmt = $this->db->prepare("SELECT url FROM risk_checklist_evidencias WHERE id = ?");
            $stmt->execute([$evidenciaId]);
            $evidencia = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if ($evidencia && $evidencia['url']) {
                $filepath = __DIR__ . '/../../../public' . $evidencia['url'];
                if (file_exists($filepath)) {
                    unlink($filepath);
                }
            }
            
            $stmt = $this->db->prepare("DELETE FROM risk_checklist_evidencias WHERE id = ?");
            $stmt->execute([$evidenciaId]);
            
            $response->getBody()->write(json_encode(['success' => true]));
            return $response->withHeader('Content-Type', 'application/json');
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['success' => false, 'error' => $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }
}
