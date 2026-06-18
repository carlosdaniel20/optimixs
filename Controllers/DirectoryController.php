<?php
declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DirectoryController
{
    public function listFiles(Request $request, Response $response): Response
    {
        $user = $_SESSION['user'] ?? null;
        if (!$user) {
            return $this->json($response, ['success' => false, 'error' => 'No autenticado'], 401);
        }
        
        $params = $request->getQueryParams();
        $path = $params['path'] ?? '';
        
        if (empty($path)) {
            return $this->json($response, ['success' => true, 'files' => []]);
        }
        
        $rootPath = dirname(__DIR__, 2);
        $fullPath = $rootPath . '/public/' . $path;
        
        $files = [];
        if (is_dir($fullPath)) {
            $items = scandir($fullPath);
            foreach ($items as $item) {
                if ($item !== '.' && $item !== '..') {
                    $itemPath = $fullPath . '/' . $item;
                    $files[] = [
                        'name' => $item,
                        'size' => is_file($itemPath) ? filesize($itemPath) : 0,
                        'modified' => date('Y-m-d H:i:s', filemtime($itemPath)),
                        'is_dir' => is_dir($itemPath)
                    ];
                }
            }
        }
        
        return $this->json($response, ['success' => true, 'files' => $files]);
    }
    
    private function json(Response $response, array $data, int $status = 200): Response
    {
        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }
}
