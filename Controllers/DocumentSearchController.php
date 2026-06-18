<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DocumentSearchController
{
    private $db;

    public function __construct()
    {
        global $container;
        $this->db = $container->get('db');
    }

    // Muestra la página de búsqueda (vista)
    public function index(Request $request, Response $response): Response
    {
        ob_start();
        require __DIR__ . '/../Views/search/documents.php';
        $content = ob_get_clean();
        ob_start();
        require __DIR__ . '/../Views/layout.php';
        $html = ob_get_clean();
        $response->getBody()->write($html);
        return $response;
    }

    // API de búsqueda (ya la tienes)
    public function search(Request $request, Response $response)
    {
        try {
            $params = $request->getQueryParams();
            $query = trim($params['q'] ?? '');
            if (strlen($query) < 3) {
                return $this->json($response, ['success' => true, 'results' => []]);
            }

            $like = '%' . $this->db->real_escape_string($query) . '%';
            $sql = "SELECT id, titolo, file_url, created_at,
                           SUBSTRING_INDEX(file_url, '.', -1) as file_type,
                           LEFT(contenido_texto, 200) as snippet
                    FROM libri 
                    WHERE contenido_texto LIKE ?
                    LIMIT 30";
            $stmt = $this->db->prepare($sql);
            if (!$stmt) {
                throw new \Exception('Error prepare: ' . $this->db->error);
            }
            $stmt->bind_param("s", $like);
            $stmt->execute();
            $result = $stmt->get_result();
            $results = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
            
            foreach ($results as &$row) {
                $row['relevancia'] = 1;
            }
            return $this->json($response, ['success' => true, 'results' => $results]);
        } catch (\Exception $e) {
            return $this->json($response, ['error' => $e->getMessage()], 500);
        }
    }

    private function json(Response $response, array $data, int $status = 200)
    {
        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }
}
