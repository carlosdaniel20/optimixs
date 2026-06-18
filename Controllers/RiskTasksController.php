<?php
declare(strict_types=1);

namespace App\Controllers;

use mysqli;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Support\Csrf;

class RiskTasksController
{
    private function getDb(Request $request): mysqli
    {
        $container = $GLOBALS['container'] ?? $request->getAttribute('container');
        if ($container && $container->has('db')) {
            return $container->get('db');
        }
        $dbConfig = require dirname(__DIR__, 2) . '/config/database.php';
        $db = new mysqli($dbConfig['host'], $dbConfig['user'], $dbConfig['password'], $dbConfig['dbname']);
        if ($db->connect_error) {
            throw new \Exception('Error de conexión: ' . $db->connect_error);
        }
        return $db;
    }

    public function dashboard(Request $request, Response $response): Response
    {
        $db = $this->getDb($request);
        $userId = $_SESSION['user']['id'] ?? 0;
        $userType = $_SESSION['user']['tipo_utente'] ?? '';

        // Obtener tareas según rol
        if ($userType === 'admin') {
            $stmt = $db->prepare("
                SELECT rt.*, 
                       o.nombre as organization_name,
                       CONCAT(u.nome, ' ', u.cognome) as assigned_name,
                       CONCAT(c.nome, ' ', c.cognome) as created_name
                FROM risk_tasks rt
                LEFT JOIN risk_organization o ON rt.organization_id = o.id
                LEFT JOIN utenti u ON rt.assigned_to = u.id
                LEFT JOIN utenti c ON rt.created_by = c.id
                ORDER BY rt.scheduled_date ASC, rt.scheduled_time ASC
            ");
            $stmt->execute();
            $tasks = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        } else {
            $stmt = $db->prepare("
                SELECT rt.*, 
                       o.nombre as organization_name,
                       CONCAT(u.nome, ' ', u.cognome) as assigned_name,
                       CONCAT(c.nome, ' ', c.cognome) as created_name
                FROM risk_tasks rt
                LEFT JOIN risk_organization o ON rt.organization_id = o.id
                LEFT JOIN utenti u ON rt.assigned_to = u.id
                LEFT JOIN utenti c ON rt.created_by = c.id
                WHERE rt.assigned_to = ? OR rt.created_by = ?
                ORDER BY rt.scheduled_date ASC, rt.scheduled_time ASC
            ");
            $stmt->bind_param("ii", $userId, $userId);
            $stmt->execute();
            $tasks = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        }

        // Estadísticas
        $stats = [
            'total' => count($tasks),
            'pending' => 0,
            'in_progress' => 0,
            'completed' => 0,
            'cancelled' => 0
        ];
        foreach ($tasks as $task) {
            if (isset($stats[$task['status']])) {
                $stats[$task['status']]++;
            }
        }

        ob_start();
        require __DIR__ . '/../Views/risktasks/dashboard.php';
        $content = ob_get_clean();
        ob_start();
        require __DIR__ . '/../Views/layout.php';
        $html = ob_get_clean();
        $response->getBody()->write($html);
        return $response;
    }

    public function getDashboardData(Request $request, Response $response): Response
    {
        $db = $this->getDb($request);
        $userId = $_SESSION['user']['id'] ?? 0;
        $userType = $_SESSION['user']['tipo_utente'] ?? '';

        if ($userType === 'admin') {
            $stmt = $db->prepare("
                SELECT rt.*, 
                       o.nombre as organization_name,
                       CONCAT(u.nome, ' ', u.cognome) as assigned_name
                FROM risk_tasks rt
                LEFT JOIN risk_organization o ON rt.organization_id = o.id
                LEFT JOIN utenti u ON rt.assigned_to = u.id
                ORDER BY rt.scheduled_date ASC, rt.scheduled_time ASC
            ");
            $stmt->execute();
            $tasks = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        } else {
            $stmt = $db->prepare("
                SELECT rt.*, 
                       o.nombre as organization_name,
                       CONCAT(u.nome, ' ', u.cognome) as assigned_name
                FROM risk_tasks rt
                LEFT JOIN risk_organization o ON rt.organization_id = o.id
                LEFT JOIN utenti u ON rt.assigned_to = u.id
                WHERE rt.assigned_to = ?
                ORDER BY rt.scheduled_date ASC, rt.scheduled_time ASC
            ");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $tasks = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $stmt->close();
        }

        return $this->json($response, ['success' => true, 'tasks' => $tasks]);
    }

    public function create(Request $request, Response $response): Response
    {
        $user = $_SESSION['user'] ?? null;
        if (!$user) {
            return $this->json($response, ['error' => 'No autenticado'], 401);
        }

        $data = (array) $request->getParsedBody();
        if (!Csrf::validate($data['csrf_token'] ?? '')) {
            return $this->json($response, ['error' => 'CSRF inválido'], 400);
        }

        $title = trim($data['title'] ?? '');
        $description = trim($data['description'] ?? '');
        $taskType = $data['task_type'] ?? '';
        $organizationId = (int) ($data['organization_id'] ?? 0);
        $assignedTo = (int) ($data['assigned_to'] ?? 0);
        $scheduledDate = $data['scheduled_date'] ?? '';
        $scheduledTime = $data['scheduled_time'] ?? '';
        $priority = $data['priority'] ?? 'media';

        $allowedTypes = ['buque', 'container', 'documental', 'inteligencia', 'logistica', 'personal'];
        if (!$title || !$description || !in_array($taskType, $allowedTypes) || !$scheduledDate || !$scheduledTime) {
            return $this->json($response, ['error' => 'Datos incompletos'], 400);
        }

        $db = $this->getDb($request);
        $stmt = $db->prepare("
            INSERT INTO risk_tasks (title, description, task_type, organization_id, assigned_to, scheduled_date, scheduled_time, priority, status, created_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending', ?)
        ");
        $stmt->bind_param("sssiisssi", $title, $description, $taskType, $organizationId, $assignedTo, $scheduledDate, $scheduledTime, $priority, $user['id']);
        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();

        return $this->json($response, ['success' => true, 'id' => $id]);
    }

    public function updateStatus(Request $request, Response $response, array $args): Response
    {
        $taskId = (int) ($args['id'] ?? 0);
        $user = $_SESSION['user'] ?? null;
        
        $data = (array) $request->getParsedBody();
        $newStatus = $data['status'] ?? '';
        $comments = trim($data['comments'] ?? '');

        $allowedStatuses = ['pending', 'in_progress', 'completed', 'cancelled'];
        if (!in_array($newStatus, $allowedStatuses)) {
            return $this->json($response, ['error' => 'Estado inválido'], 400);
        }

        $db = $this->getDb($request);
        
        // Verificar permisos
        $stmt = $db->prepare("SELECT assigned_to, created_by FROM risk_tasks WHERE id = ?");
        $stmt->bind_param("i", $taskId);
        $stmt->execute();
        $task = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$task) {
            return $this->json($response, ['error' => 'Tarea no encontrada'], 404);
        }

        if ($task['assigned_to'] != $user['id'] && $task['created_by'] != $user['id'] && ($_SESSION['user']['tipo_utente'] ?? '') !== 'admin') {
            return $this->json($response, ['error' => 'No tienes permisos'], 403);
        }

        $completedAt = ($newStatus === 'completed') ? date('Y-m-d H:i:s') : null;
        $stmt = $db->prepare("UPDATE risk_tasks SET status = ?, completed_at = ? WHERE id = ?");
        $stmt->bind_param("ssi", $newStatus, $completedAt, $taskId);
        $stmt->execute();
        $stmt->close();

        return $this->json($response, ['success' => true]);
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        $taskId = (int) ($args['id'] ?? 0);
        $user = $_SESSION['user'] ?? null;

        if (!$user || ($_SESSION['user']['tipo_utente'] ?? '') !== 'admin') {
            return $this->json($response, ['error' => 'Acceso denegado'], 403);
        }

        $db = $this->getDb($request);
        $stmt = $db->prepare("DELETE FROM risk_tasks WHERE id = ?");
        $stmt->bind_param("i", $taskId);
        $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();

        return $this->json($response, ['success' => $affected > 0]);
    }

   public function getTask(Request $request, Response $response): Response
{
    $data = (array) $request->getParsedBody();
    $taskId = (int) ($data['id'] ?? 0);
    
    if ($taskId <= 0) {
        return $this->json($response, ['error' => 'ID de tarea inválido'], 400);
    }
    
    $db = $this->getDb($request);
    
    $stmt = $db->prepare("
        SELECT rt.*, 
               o.nombre as organization_name,
               CONCAT(u.nome, ' ', u.cognome) as assigned_name
        FROM risk_tasks rt
        LEFT JOIN risk_organization o ON rt.organization_id = o.id
        LEFT JOIN utenti u ON rt.assigned_to = u.id
        WHERE rt.id = ?
    ");
    
    $stmt->bind_param("i", $taskId);
    $stmt->execute();
    $result = $stmt->get_result();
    $task = $result->fetch_assoc();
    $stmt->close();

    if (!$task) {
        return $this->json($response, ['error' => 'Tarea no encontrada'], 404);
    }

    return $this->json($response, $task);
}

    public function getOrganizations(Request $request, Response $response): Response
    {
        $db = $this->getDb($request);
        $result = $db->query("SELECT id, nombre FROM risk_organization ORDER BY nombre");
        $orgs = [];
        while ($row = $result->fetch_assoc()) {
            $orgs[] = $row;
        }
        return $this->json($response, $orgs);
    }

    public function getUsers(Request $request, Response $response): Response
    {
        $db = $this->getDb($request);
        $result = $db->query("SELECT id, CONCAT(nome, ' ', cognome) AS name, email FROM utenti WHERE tipo_utente IN ('admin', 'staff') ORDER BY nome");
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        return $this->json($response, $users);
    }

    private function json(Response $response, array $data, int $status = 200): Response
    {
        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }
}
