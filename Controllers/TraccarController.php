<?php
declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class TraccarController
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
            throw new \Exception('Error de conexión: ' . $db->connect_error);
        }
        return $db;
    }

    private function render(Response $response, string $view, array $data = []): Response
    {
        extract($data);
        ob_start();
        require dirname(__DIR__, 2) . '/views/' . $view . '.php';
        $html = ob_get_clean();
        $response->getBody()->write($html);
        return $response;
    }

    public function dashboard(Request $request, Response $response): Response
    {
        $db = $this->getDb($request);
        
        $stats = ['devices' => 0, 'active' => 0];
        
        // Verificar si la tabla existe antes de consultar
        $checkTable = $db->query("SHOW TABLES LIKE 'traccar_devices'");
        if ($checkTable && $checkTable->num_rows > 0) {
            $result = $db->query("SELECT COUNT(*) as total FROM traccar_devices");
            if ($row = $result->fetch_assoc()) {
                $stats['devices'] = $row['total'];
            }
            
            $result = $db->query("SELECT COUNT(*) as total FROM traccar_devices WHERE status = 'active'");
            if ($row = $result->fetch_assoc()) {
                $stats['active'] = $row['total'];
            }
        }
        
        // Obtener configuración de Traccar
        $traccarConfig = [];
        $configPath = dirname(__DIR__, 2) . '/config/traccar.php';
        if (file_exists($configPath)) {
            $traccarConfig = require $configPath;
        }
        
        $traccarUrl = $traccarConfig['url'] ?? 'http://10.0.3.15:8082';
        $traccarToken = $traccarConfig['token'] ?? 'SDBGAiEA46rvBE8YvK4f_kjTN-6J0DcppxxLY-YQMT-VYYvwsMQCIQD0rCUreH1zlRjydJmrcwTNzHe7d_tG-I2NY1EBirmCp3siaSI6NzEwNjc0OTUzNzc2OTk1MzU3NiwidSI6MSwiZSI6IjIwMjYtMDUtMzBUMDU6MDA6MDAuMDAwKzAwOjAwIn0';
        
        return $this->render($response, 'optimixstracker/dashboard', [
            'stats' => $stats,
            'traccar_url' => $traccarUrl,
            'traccar_token' => $traccarToken
        ]);
    }

    public function map(Request $request, Response $response): Response
    {
        // Obtener configuración de Traccar
        $traccarConfig = [];
        $configPath = dirname(__DIR__, 2) . '/config/traccar.php';
        if (file_exists($configPath)) {
            $traccarConfig = require $configPath;
        }
        
        $traccarUrl = $traccarConfig['url'] ?? 'http://10.0.3.15:8082';
        $traccarToken = $traccarConfig['token'] ?? 'SDBGAiEA46rvBE8YvK4f_kjTN-6J0DcppxxLY-YQMT-VYYvwsMQCIQD0rCUreH1zlRjydJmrcwTNzHe7d_tG-I2NY1EBirmCp3siaSI6NzEwNjc0OTUzNzc2OTk1MzU3NiwidSI6MSwiZSI6IjIwMjYtMDUtMzBUMDU6MDA6MDAuMDAwKzAwOjAwIn0';
        
        return $this->render($response, 'optimixstracker/map', [
            'traccar_url' => $traccarUrl,
            'traccar_token' => $traccarToken
        ]);
    }

    public function devices(Request $request, Response $response): Response
    {
        // Obtener configuración de Traccar
        $traccarConfig = [];
        $configPath = dirname(__DIR__, 2) . '/config/traccar.php';
        if (file_exists($configPath)) {
            $traccarConfig = require $configPath;
        }
        
        $traccarUrl = $traccarConfig['url'] ?? 'http://10.0.3.15:8082';
        $traccarToken = $traccarConfig['token'] ?? 'SDBGAiEA46rvBE8YvK4f_kjTN-6J0DcppxxLY-YQMT-VYYvwsMQCIQD0rCUreH1zlRjydJmrcwTNzHe7d_tG-I2NY1EBirmCp3siaSI6NzEwNjc0OTUzNzc2OTk1MzU3NiwidSI6MSwiZSI6IjIwMjYtMDUtMzBUMDU6MDA6MDAuMDAwKzAwOjAwIn0';
        
        return $this->render($response, 'optimixstracker/devices', [
            'traccar_url' => $traccarUrl,
            'traccar_token' => $traccarToken
        ]);
    }
}
