<?php
declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Support\Csrf;

class FolderUploadController
{
    /**
     * Sube una carpeta completa con toda su estructura a public/uploads/digital/{libro_id}/
     */
    public function uploadFolder(Request $request, Response $response): Response
    {
        // 1. Verificar autenticación (solo admin/staff)
        $user = $_SESSION['user'] ?? null;
        $role = $user['tipo_utente'] ?? '';
        if (!$user || !in_array($role, ['admin', 'staff'], true)) {
            return $this->json($response, ['success' => false, 'error' => 'Acceso denegado'], 403);
        }

        // 2. Verificar CSRF
        $postData = $request->getParsedBody() ?? [];
        $csrfToken = $postData['csrf_token'] ?? $_POST['csrf_token'] ?? '';
        if (!Csrf::validate($csrfToken)) {
            return $this->json($response, ['success' => false, 'error' => 'CSRF inválido'], 400);
        }

        // 3. Obtener ID del libro
        $libroId = (int) ($postData['libro_id'] ?? $_POST['libro_id'] ?? 0);
        if ($libroId <= 0) {
            return $this->json($response, ['success' => false, 'error' => 'ID de libro no proporcionado'], 400);
        }

        // 4. Obtener archivos subidos
        $files = $request->getUploadedFiles();
        if (empty($files['files'])) {
            return $this->json($response, ['success' => false, 'error' => 'No se recibieron archivos'], 400);
        }

        // 5. Directorio base: public/uploads/digital/{libro_id}/
        $rootPath = dirname(__DIR__, 2); // sube desde app/Controllers hasta la raíz del proyecto
        $baseDir = $rootPath . '/public/uploads/digital/' . $libroId . '/';
        if (!is_dir($baseDir) && !mkdir($baseDir, 0755, true)) {
            return $this->json($response, ['success' => false, 'error' => 'No se pudo crear el directorio de destino'], 500);
        }

        $uploadedCount = 0;
        $errors = [];

        // 6. Procesar cada archivo respetando la estructura de carpetas
        foreach ($files['files'] as $file) {
            $relativePath = $file->getClientFilename(); // Ej: "subcarpeta/archivo.pdf"
            if (empty($relativePath)) {
                $errors[] = 'Nombre de archivo vacío';
                continue;
            }

            // Sanitizar ruta (evita directory traversal)
            $safePath = str_replace(['../', '..\\', './', '.\\'], '', $relativePath);
            $targetFile = $baseDir . $safePath;
            $targetDir = dirname($targetFile);
            if (!is_dir($targetDir) && !mkdir($targetDir, 0755, true)) {
                $errors[] = "No se pudo crear el directorio para $safePath";
                continue;
            }

            try {
                $file->moveTo($targetFile);
                $uploadedCount++;
            } catch (\Throwable $e) {
                $errors[] = "Error moviendo $safePath: " . $e->getMessage();
            }
        }

        if (empty($errors)) {
            return $this->json($response, ['success' => true, 'message' => "Se subieron $uploadedCount archivos correctamente"]);
        } else {
            return $this->json($response, ['success' => false, 'error' => implode(', ', $errors)], 500);
        }
    }

    private function json(Response $response, array $data, int $status = 200): Response
    {
        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }
}

