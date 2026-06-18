<?php
declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ChecklistDashboardController
{
    private $pdo;
    
    public function __construct()
    {
        $config = require dirname(__DIR__, 2) . '/config/database.php';
        $dsn = "mysql:host={$config['host']};dbname={$config['database']};charset=utf8mb4";
        $this->pdo = new \PDO($dsn, $config['username'], $config['password']);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
    
    /**
     * Obtener todos los checklists para el dashboard
     */
    public function getDashboard(Request $request, Response $response): Response
    {
        $user = $_SESSION['user'] ?? null;
        if (!$user) {
            return $this->json($response, ['success' => false, 'error' => 'No autenticado'], 401);
        }
        
        try {
            // Obtener todos los checklists únicos desde risk_checklist_progress
            $sql = "
                SELECT DISTINCT 
                    rcp.nombre_checklist,
                    rcp.riesgo_id,
                    rcp.organizacion_id,
                    rcp.organizacion_nombre,
                    MAX(rcp.updated_at) as updated_at,
                    COUNT(DISTINCT rcp.template_id) as total_evidencias,
                    SUM(CASE WHEN rcp.checked = 1 THEN 1 ELSE 0 END) as afirmativas
                FROM risk_checklist_progress rcp
                GROUP BY rcp.nombre_checklist, rcp.riesgo_id, rcp.organizacion_id, rcp.organizacion_nombre
                ORDER BY updated_at DESC
            ";
            
            $stmt = $this->pdo->query($sql);
            $checklists = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            return $this->json($response, [
                'success' => true,
                'data' => $checklists
            ]);
        } catch (\Exception $e) {
            return $this->json($response, [
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Eliminar un checklist completo
     */
    public function deleteChecklist(Request $request, Response $response): Response
    {
        $user = $_SESSION['user'] ?? null;
        if (!$user || !in_array($user['tipo_utente'] ?? '', ['admin', 'staff'], true)) {
            return $this->json($response, ['success' => false, 'error' => 'No autorizado'], 403);
        }
        
        $params = (array)$request->getParsedBody();
        $nombreChecklist = $params['nombre_checklist'] ?? '';
        $riesgoId = (int)($params['riesgo_id'] ?? 0);
        
        if (empty($nombreChecklist) || $riesgoId <= 0) {
            return $this->json($response, ['success' => false, 'error' => 'Parámetros inválidos'], 400);
        }
        
        try {
            // Eliminar progreso
            $stmt = $this->pdo->prepare("DELETE FROM risk_checklist_progress WHERE nombre_checklist = ? AND riesgo_id = ?");
            $stmt->execute([$nombreChecklist, $riesgoId]);
            
            // Eliminar evidencias (opcional)
            $folder = "checklist_{$riesgoId}_" . preg_replace('/[^a-z0-9]/i', '_', $nombreChecklist);
            $evidencePath = dirname(__DIR__, 2) . "/public/uploads/checklist_evidencias/{$folder}";
            if (is_dir($evidencePath)) {
                $this->deleteDirectory($evidencePath);
            }
            
            return $this->json($response, ['success' => true]);
        } catch (\Exception $e) {
            return $this->json($response, ['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
    
    private function deleteDirectory($dir)
    {
        if (!is_dir($dir)) return;
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            $path = $dir . '/' . $file;
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }
        rmdir($dir);
    }
    
    private function json(Response $response, array $data, int $status = 200): Response
    {
        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }
}
