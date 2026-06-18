<?php
declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class MaritimeController
{
    private function getDb(Request $request): \mysqli
    {
        $container = $GLOBALS['container'] ?? $request->getAttribute('container');
        if ($container && $container->has('db')) {
            return $container->get('db');
        }
        $dbConfig = require dirname(__DIR__, 2) . '/config/database.php';
        $db = new \mysqli($dbConfig['host'], $dbConfig['user'], $dbConfig['password'], $dbConfig['dbname']);
        if ($db->connect_error) {
            throw new \Exception('Error de conexión a la base de datos: ' . $db->connect_error);
        }
        return $db;
    }

    private function json(Response $response, array $data, int $status = 200): Response
    {
        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }

    /**
     * Mostrar la vista principal del módulo marítimo
     */
    public function index(Request $request, Response $response): Response
    {
        ob_start();
        require dirname(__DIR__, 2) . '/app/Views/maritime/index.php';
        $html = ob_get_clean();
        $response->getBody()->write($html);
        return $response;
    }

    /**
     * Obtener datos de un buque por IMO
     */
    public function getVessel(Request $request, Response $response, array $args): Response
    {
        $imo = $args['imo'] ?? '';
        
        if (!preg_match('/^\d{7}$/', $imo)) {
            return $this->json($response, ['error' => 'IMO debe ser un número de 7 dígitos'], 400);
        }
        
        // Usar el wrapper que creamos
        $command = "/usr/local/bin/equasis-wrapper.sh vessel --imo " . escapeshellarg($imo);
        $output = shell_exec($command);
        
        if (!$output || trim($output) === '') {
            return $this->json($response, ['error' => 'No se recibió respuesta de equasis-cli'], 500);
        }
        
        $data = json_decode($output, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->json($response, ['error' => 'Error parseando JSON: ' . json_last_error_msg()], 500);
        }
        
        return $this->json($response, [
            'success' => true,
            'vessel' => $data['basic_info'] ?? [],
            'management' => $data['management'] ?? [],
            'inspections' => $data['inspections'] ?? [],
            'classification_surveys' => $data['classification_surveys'] ?? [],
            'safety_certificate' => $data['safety_certificate'] ?? null,
            'pandi_club' => $data['pandi_club'] ?? null,
            'historical_names' => $data['historical_names'] ?? [],
            'historical_flags' => $data['historical_flags'] ?? []
        ]);
    }

    /**
     * Buscar buques por nombre
     */
    public function searchVessels(Request $request, Response $response): Response
    {
        $params = $request->getQueryParams();
        $name = $params['name'] ?? '';
        
        if (strlen($name) < 3) {
            return $this->json($response, ['error' => 'El nombre debe tener al menos 3 caracteres'], 400);
        }
        
        $command = "/usr/local/bin/equasis-wrapper.sh search --name " . escapeshellarg($name);
        $output = shell_exec($command);
        
        if (!$output || trim($output) === '') {
            return $this->json($response, ['error' => 'No se recibió respuesta de equasis-cli'], 500);
        }
        
        $data = json_decode($output, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->json($response, ['error' => 'Error parseando JSON'], 500);
        }
        
        return $this->json($response, [
            'success' => true,
            'results' => $data['results'] ?? [],
            'count' => count($data['results'] ?? [])
        ]);
    }
}
