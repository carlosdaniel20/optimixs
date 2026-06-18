<?php
declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Support\Csrf;

class ApiSessionController
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

    private function json(Response $response, array $data, int $status = 200): Response
    {
        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }

    // Obtener todas las sesiones de un riesgo
    public function getSessionsByRiesgo(Request $request, Response $response, array $args): Response
    {
        $riesgoId = (int)($args['id'] ?? 0);
        if ($riesgoId <= 0) {
            return $this->json($response, ['success' => false, 'error' => 'ID inválido'], 400);
        }
        $db = $this->getDb($request);
        $stmt = $db->prepare("SELECT id, nombre, descripcion, created_at FROM risk_checklist_sessions WHERE riesgo_id = ? ORDER BY created_at DESC");
        $stmt->bind_param('i', $riesgoId);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();
        return $this->json($response, ['success' => true, 'data' => $data]);
    }

    // Crear una nueva sesión
    public function createSession(Request $request, Response $response): Response
    {
        $data = (array) $request->getParsedBody();
        if (!Csrf::validate($data['csrf_token'] ?? '')) {
            return $this->json($response, ['success' => false, 'error' => 'CSRF inválido'], 400);
        }
        $riesgoId = (int)($data['riesgo_id'] ?? 0);
        $nombre = trim($data['nombre'] ?? '');
        $descripcion = trim($data['descripcion'] ?? '');
        if (!$riesgoId || !$nombre) {
            return $this->json($response, ['success' => false, 'error' => 'Faltan datos'], 400);
        }
        $db = $this->getDb($request);
        $usuarioId = $_SESSION['user']['id'] ?? null;
        $stmt = $db->prepare("INSERT INTO risk_checklist_sessions (riesgo_id, nombre, descripcion, usuario_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('issi', $riesgoId, $nombre, $descripcion, $usuarioId);
        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        return $this->json($response, ['success' => true, 'id' => $id]);
    }

// Obtener o crear una sesión por nombre y riesgo_id
public function getOrCreateSession(Request $request, Response $response): Response
{
    $data = (array) $request->getParsedBody();
    if (!Csrf::validate($data['csrf_token'] ?? '')) {
        return $this->json($response, ['success' => false, 'error' => 'CSRF inválido'], 400);
    }
    $riesgoId = (int)($data['riesgo_id'] ?? 0);
    $nombre = trim($data['nombre'] ?? '');
    $descripcion = trim($data['descripcion'] ?? '');
    if (!$riesgoId || !$nombre) {
        return $this->json($response, ['success' => false, 'error' => 'Faltan datos'], 400);
    }
    $db = $this->getDb($request);
    // Buscar si ya existe una sesión con ese nombre y riesgo
    $stmt = $db->prepare("SELECT id FROM risk_checklist_sessions WHERE riesgo_id = ? AND nombre = ?");
    $stmt->bind_param('is', $riesgoId, $nombre);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $sessionId = $row['id'];
        $stmt->close();
        return $this->json($response, ['success' => true, 'id' => $sessionId, 'created' => false]);
    }
    $stmt->close();
    // No existe, crear nueva
    $usuarioId = $_SESSION['user']['id'] ?? null;
    $stmt = $db->prepare("INSERT INTO risk_checklist_sessions (riesgo_id, nombre, descripcion, usuario_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('issi', $riesgoId, $nombre, $descripcion, $usuarioId);
    $stmt->execute();
    $sessionId = $stmt->insert_id;
    $stmt->close();
    return $this->json($response, ['success' => true, 'id' => $sessionId, 'created' => true]);
}

}
