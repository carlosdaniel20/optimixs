<?php
declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Support\Csrf;

class DigitalFileController
{
    private $pdo;
    
    public function __construct()
    {
        // Usar la conexión existente de tu aplicación
        // Método 1: Usar la función db() si existe
        if (function_exists('db')) {
            $this->pdo = db()->getPdo();
        } 
        // Método 2: Usar variables de entorno
        elseif (getenv('DB_HOST')) {
            $dsn = "mysql:host=" . getenv('DB_HOST') . ";dbname=" . getenv('DB_NAME') . ";charset=utf8mb4";
            $this->pdo = new \PDO($dsn, getenv('DB_USER'), getenv('DB_PASS'));
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
        // Método 3: Configuración directa
        else {
            $config = [
                'host' => 'localhost',
                'database' => 'optimix',
                'username' => 'root',
                'password' => ''
            ];
            $dsn = "mysql:host={$config['host']};dbname={$config['database']};charset=utf8mb4";
            $this->pdo = new \PDO($dsn, $config['username'], $config['password']);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
    }
    
    // Resto de los métodos...
    public function serve(Request $request, Response $response, array $args): Response
    {
        // Tu código existente...
        return $response;
    }
    
    public function getChecklistEvidences(Request $request, Response $response): Response
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
    
    public function uploadChecklistEvidence(Request $request, Response $response): Response
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
        $organizacionId = (int)($parsedBody['organizacion_id'] ?? 0);
        
        if ($riesgoId <= 0 || empty($nombreChecklist)) {
            return $this->json($response, ['success' => false, 'error' => 'riesgo_id y nombre_checklist son requeridos'], 400);
        }
        
        $files = $request->getUploadedFiles();
        if (empty($files['file'])) {
            return $this->json($response, ['success' => false, 'error' => 'No se recibió archivo'], 400);
        }
        
        $file = $files['file'];
        $originalName = $file->getClientFilename();
        
        $rootPath = dirname(__DIR__, 2);
        $uploadDir = "/uploads/checklist_evidencias/";
        $fullDir = $rootPath . "/public" . $uploadDir;
        
        if (!is_dir($fullDir) && !mkdir($fullDir, 0755, true)) {
            return $this->json($response, ['success' => false, 'error' => 'No se pudo crear directorio'], 500);
        }
        
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $uniqueName = uniqid() . '_' . time() . '.' . $extension;
        $targetFile = $fullDir . $uniqueName;
        
        try {
            $file->moveTo($targetFile);
            
            $stmt = $this->pdo->prepare("INSERT INTO risk_checklist_evidencias (riesgo_id, nombre_checklist, organizacion_id, url, nombre_original, tipo) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $riesgoId,
                $nombreChecklist,
                $organizacionId ?: null,
                $uploadDir . $uniqueName,
                $originalName,
                $extension
            ]);
            
            return $this->json($response, [
                'success' => true,
                'uploadURL' => $uploadDir . $uniqueName,
                'fileId' => $this->pdo->lastInsertId()
            ]);
        } catch (\Exception $e) {
            return $this->json($response, ['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
    
    public function deleteChecklistEvidence(Request $request, Response $response, array $args): Response
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
    
    public function serveEvidence(Request $request, Response $response, array $args): Response
    {
        $user = $_SESSION['user'] ?? null;
        if (!$user) {
            return $response->withStatus(403);
        }
        
        $evidenceId = (int)($args['id'] ?? 0);
        if ($evidenceId <= 0) {
            return $response->withStatus(400);
        }
        
        $stmt = $this->pdo->prepare("SELECT * FROM risk_checklist_evidencias WHERE id = ?");
        $stmt->execute([$evidenceId]);
        $evidence = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if (!$evidence) {
            return $response->withStatus(404);
        }
        
        $rootPath = dirname(__DIR__, 2);
        $fullPath = $rootPath . $evidence['url'];
        
        if (!file_exists($fullPath)) {
            return $response->withStatus(404);
        }
        
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $fullPath);
        finfo_close($finfo);
        
        $stream = fopen($fullPath, 'rb');
        if ($stream === false) {
            return $response->withStatus(500);
        }
        
        return $response
            ->withHeader('Content-Type', $mimeType)
            ->withHeader('Content-Disposition', 'inline; filename="' . basename($fullPath) . '"')
            ->withBody(new \Slim\Psr7\Stream($stream));
    }
    
    private function json(Response $response, array $data, int $status = 200): Response
    {
        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }
}
