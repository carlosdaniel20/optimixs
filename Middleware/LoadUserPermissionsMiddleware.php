<?php
declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class LoadUserPermissionsMiddleware
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function __invoke(Request $request, RequestHandler $handler)
    {
        $userId = $_SESSION['user']['id'] ?? null;
        $userRole = $_SESSION['user']['tipo_utente'] ?? '';

        // Si es admin, no necesita permisos específicos (tiene todo)
        if ($userRole === 'admin') {
            // Los admins tienen todos los permisos, el layout lo maneja con $isAdminUser
            $_SESSION['user_permissions'] = [];
            return $handler->handle($request);
        }

        // Si no hay usuario logueado, no cargar permisos
        if (!$userId) {
            unset($_SESSION['user_permissions']);
            return $handler->handle($request);
        }

        // Cargar todos los permisos del usuario desde la base de datos
        $permissions = [];

        $stmt = $this->db->prepare("
            SELECT module_id, can_view, can_edit, can_delete 
            FROM user_module_permissions 
            WHERE user_id = ?
        ");
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

        // Obtener todos los módulos para asegurar que los que no tienen permisos queden como false
        $allModulesStmt = $this->db->prepare("SELECT id FROM modules");
        $allModulesStmt->execute();
        $allModules = $allModulesStmt->get_result();

        while ($module = $allModules->fetch_assoc()) {
            $moduleId = $module['id'];
            if (!isset($permissions[$moduleId])) {
                $permissions[$moduleId] = [
                    'view' => false,
                    'edit' => false,
                    'delete' => false
                ];
            }
        }
        $allModulesStmt->close();

        $_SESSION['user_permissions'] = $permissions;

        return $handler->handle($request);
    }
}
