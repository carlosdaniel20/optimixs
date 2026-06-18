<?php
declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Support\Csrf;

class ChecklistEvidenceController
{
    private $pdo;
    
    public function __construct()
    {
        $config = require dirname(__DIR__, 2) . '/config/database.php';
        $dsn = "mysql:host={$config['host']};dbname={$config['database']};charset=utf8mb4";
        $this->pdo = new \PDO($dsn, $config['username'], $config['password']);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
    
    public function getEvidences(Request $request, Response $response): Response
    {
        $user = $_SESSION['user'] ?? null;
        if (!$user) {
            return $this->json($response, ['success' => false, 'error' => 'No autenticado'], 401);
        }
        
        $params = $request->getQueryParams();
        $riesgoId = (int)($params['riesgo_id'] ?? 0);
        $nombreChecklist = $params['nombre_checklist'] ?? '';
        
        if ($riesgoId <= 0 || empty($nombreChecklist)) {
            return $this->json($response, ['success' => false, 'error' => 'Parámetros requeridos'], 400);
        }
        
        $stmt = $this->pdo->prepare("SELECT * FROM risk_checklist_evidencias WHERE riesgo_id = ? AND nombre_checklist = ? ORDER BY created_at DESC");
        $stmt->execute([$riesgoId, $nombreChecklist]);
        $evidencias = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        return $this->json($response, ['success' => true, 'data' => $evidencias]);
    }
    
    public function uploadEvidences(Request $request, Response $response): Response
    {
        $user = $_SESSION['user'] ?? null;
        if (!$user) {
            return $this->json($response, ['success' => false, 'error' => 'No autenticado'], 401);
        }
        
        $parsedBody = $request->getParsedBody() ?? [];
        $csrfToken = $parsedBody['csrf_token'] ?? $_POST['csrf_token'] ?? '';
        if (!Csrf::validate($csrfToken)) {
            return $this->json($response, ['success' => false, 'error' => 'CSRF inválido'], 400);
        }
        
        $riesgoId = (int)($parsedBody['riesgo_id'] ?? 0);
        $nombreChecklist = $parsedBody['nombre_checklist'] ?? '';
        
        if ($riesgoId <= 0 || empty($nombreChecklist)) {
            return $this->json($response, ['success' => false, 'error' => 'riesgo_id y nombre_checklist son requeridos'], 400);
        }
        
        $files = $request->getUploadedFiles();
        if (empty($files['evidencias'])) {
            return $this->json($response, ['success' => false, 'error' => 'No se recibieron archivos'], 400);
        }
        
        $uploadedFiles = is_array($files['evidencias']) ? $files['evidencias'] : [$files['evidencias']];
        
        $rootPath = dirname(__DIR__, 2);
        $uploadDir = $rootPath . "/public/uploads/evidencias/";
        
        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0755, true)) {
            return $this->json($response, ['success' => false, 'error' => 'No se pudo crear directorio'], 500);
        }
        
        $uploadedCount = 0;
        $errors = [];
        
        foreach ($uploadedFiles as $file) {
            $originalName = $file->getClientFilename();
            if (empty($originalName)) continue;
            
            $extension = pathinfo($originalName, PATHINFO_EXTENSION);
            $uniqueName = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
            $targetFile = $uploadDir . $uniqueName;
            
            try {
                $file->moveTo($targetFile);
                
                $stmt = $this->pdo->prepare("INSERT INTO risk_checklist_evidencias (riesgo_id, nombre_checklist, url, nombre_original, tipo) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([
                    $riesgoId,
                    $nombreChecklist,
                    '/uploads/evidencias/' . $uniqueName,
                    $originalName,
                    strtolower($extension)
                ]);
                $uploadedCount++;
            } catch (\Exception $e) {
                $errors[] = $e->getMessage();
            }
        }
        
        return $this->json($response, [
            'success' => true, 
            'subidos' => $uploadedCount,
            'errores' => $errors
        ]);
    }
    
    public function deleteEvidence(Request $request, Response $response, array $args): Response
    {
        $user = $_SESSION['user'] ?? null;
        if (!$user) {
            return $this->json($response, ['success' => false, 'error' => 'No autenticado'], 401);
        }
        
        $parsedBody = $request->getParsedBody() ?? [];
        $csrfToken = $parsedBody['csrf_token'] ?? $_POST['csrf_token'] ?? '';
        if (!Csrf::validate($csrfToken)) {
            return $this->json($response, ['success' => false, 'error' => 'CSRF inválido'], 400);
        }
        
        $evidenceId = (int)($args['id'] ?? 0);
        
        // Obtener la URL del archivo antes de eliminar
        $stmt = $this->pdo->prepare("SELECT url FROM risk_checklist_evidencias WHERE id = ?");
        $stmt->execute([$evidenceId]);
        $evidence = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if ($evidence) {
            $rootPath = dirname(__DIR__, 2);
            $filePath = $rootPath . $evidence['url'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        
        $stmt = $this->pdo->prepare("DELETE FROM risk_checklist_evidencias WHERE id = ?");
        $stmt->execute([$evidenceId]);
        
        return $this->json($response, ['success' => true]);
    }
    
    private function json(Response $response, array $data, int $status = 200): Response
    {
        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }
}
