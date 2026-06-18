<?php
declare(strict_types=1);

namespace App\Controllers;

use mysqli;
use App\Support\Csrf;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserPermissionsController
{
    private $db;
    
    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }
    
    /**
     * Carga los permisos de un usuario en la sesión
     */
    private function loadUserPermissionsToSession(int $userId): void
    {
        $permissions = [];
        
        $stmt = $this->db->prepare("SELECT module_id, can_view, can_edit, can_delete FROM user_module_permissions WHERE user_id = ?");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $permissions[$row['module_id']] = [
                'view' => (bool)$row['can_view'],
                'edit' => (bool)$row['can_edit'],
                'delete' => (bool)$row['can_delete']
            ];
        }
        $stmt->close();
        
        $_SESSION['user_permissions'] = $permissions;
    }
    
    public function index(Request $request, Response $response): Response
    {
        if (($userTipo = $_SESSION['user']['tipo_utente'] ?? '') !== 'admin') {
            return $response->withHeader('Location', '/admin/dashboard')->withStatus(302);
        }

        // Obtener usuarios
        $users = [];
        $result = $this->db->query("SELECT id, nome, cognome, email, tipo_utente FROM utenti ORDER BY tipo_utente, nome, cognome");
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        // Obtener módulos
        $modules = [];
        $result = $this->db->query("SELECT * FROM modules WHERE parent_id IS NULL ORDER BY sort_order");
        while ($row = $result->fetch_assoc()) {
            $subStmt = $this->db->prepare("SELECT * FROM modules WHERE parent_id = ? ORDER BY sort_order");
            $subStmt->bind_param('i', $row['id']);
            $subStmt->execute();
            $subResult = $subStmt->get_result();
            $row['submodules'] = $subResult->fetch_all(MYSQLI_ASSOC);
            $subStmt->close();
            $modules[] = $row;
        }

        // Ruta correcta: app/Views/admin/permissions/index.php
        $viewPath = dirname(__DIR__, 2) . '/app/Views/admin/permissions/index.php';
        
        if (!file_exists($viewPath)) {
            $response->getBody()->write("Vista no encontrada: " . $viewPath);
            return $response;
        }
        
        ob_start();
        require $viewPath;
        $content = ob_get_clean();

        // Layout: app/Views/layout.php
        $layoutPath = dirname(__DIR__, 2) . '/app/Views/layout.php';
        if (file_exists($layoutPath)) {
            ob_start();
            require $layoutPath;
            $html = ob_get_clean();
        } else {
            $html = $content;
        }

        $response->getBody()->write($html);
        return $response;
    }

    public function getUserPermissions(Request $request, Response $response, array $args): Response
    {
        if (($_SESSION['user']['tipo_utente'] ?? '') !== 'admin') {
            $response->getBody()->write(json_encode(['error' => 'Unauthorized']));
            return $response->withStatus(403)->withHeader('Content-Type', 'application/json');
        }

        $userId = (int)($args['id'] ?? 0);
        $permissions = [];

        $stmt = $this->db->prepare("SELECT module_id, can_view, can_edit, can_delete FROM user_module_permissions WHERE user_id = ?");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $permissions[$row['module_id']] = [
                'view' => (bool)$row['can_view'],
                'edit' => (bool)$row['can_edit'],
                'delete' => (bool)$row['can_delete']
            ];
        }
        $stmt->close();

        $response->getBody()->write(json_encode($permissions));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function updatePermissions(Request $request, Response $response): Response
    {
        if (($_SESSION['user']['tipo_utente'] ?? '') !== 'admin') {
            return $response->withStatus(403);
        }

        // NOTA: La validación CSRF ya la hace el middleware CsrfMiddleware
        // No necesitamos validar manualmente aquí porque el middleware ya lo hizo
        // Si quieres mantener la validación manual, descomenta el código de abajo
        // y asegúrate de que el método validateToken exista en la clase Csrf
        
        /*
        // Verificar CSRF token (solo si el middleware no lo hace)
        $data = (array) $request->getParsedBody();
        $csrfToken = $data['csrf_token'] ?? '';
        if (!Csrf::validateToken($csrfToken)) {
            return $response->withHeader('Location', '/admin/permissions?error=csrf')->withStatus(302);
        }
        */

        $data = (array) $request->getParsedBody();
        $userId = (int)($data['user_id'] ?? 0);
        $permissions = $data['permissions'] ?? [];

        // Iniciar transacción
        $this->db->begin_transaction();

        try {
            $deleteStmt = $this->db->prepare("DELETE FROM user_module_permissions WHERE user_id = ?");
            $deleteStmt->bind_param('i', $userId);
            $deleteStmt->execute();
            $deleteStmt->close();

            $insertStmt = $this->db->prepare("INSERT INTO user_module_permissions (user_id, module_id, can_view, can_edit, can_delete) VALUES (?, ?, ?, ?, ?)");

            foreach ($permissions as $moduleId => $perms) {
                $canView = isset($perms['view']) ? 1 : 0;
                $canEdit = isset($perms['edit']) ? 1 : 0;
                $canDelete = isset($perms['delete']) ? 1 : 0;
                $insertStmt->bind_param('iiiii', $userId, $moduleId, $canView, $canEdit, $canDelete);
                $insertStmt->execute();
            }
            $insertStmt->close();

            // Si el usuario que se está editando es el mismo que está logueado, actualizar sus permisos en sesión
            $currentUserId = (int)($_SESSION['user']['id'] ?? 0);
            if ($userId === $currentUserId) {
                $this->loadUserPermissionsToSession($userId);
            }

            $this->db->commit();
            
            // Redirigir con éxito
            return $response->withHeader('Location', '/admin/permissions?success=1')->withStatus(302);
            
        } catch (\Exception $e) {
            $this->db->rollback();
            error_log('Error updating permissions: ' . $e->getMessage());
            return $response->withHeader('Location', '/admin/permissions?error=1')->withStatus(302);
        }
    }
}
