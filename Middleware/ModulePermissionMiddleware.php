
<?php
declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

class ModulePermissionMiddleware
{
    private $db;
    private $moduleName;
    private $action;

    public function __construct($db, string $moduleName, string $action = 'view')
    {
        $this->db = $db;
        $this->moduleName = $moduleName;
        $this->action = $action;
    }

    public function __invoke(Request $request, RequestHandler $handler)
    {
        $userId = $_SESSION['user']['id'] ?? null;
        $userRole = $_SESSION['user']['tipo_utente'] ?? '';

        // Administrador tiene acceso total
        if ($userRole === 'admin') {
            return $handler->handle($request);
        }

        if (!$userId) {
            $response = new Response();
            return $response->withHeader('Location', '/login')->withStatus(302);
        }

        // Obtener el módulo
        $stmt = $this->db->prepare("SELECT id FROM modules WHERE name = ?");
        $stmt->bind_param('s', $this->moduleName);
        $stmt->execute();
        $result = $stmt->get_result();
        $module = $result->fetch_assoc();
        $stmt->close();

        if (!$module) {
            $response = new Response();
            $response->getBody()->write('Módulo no encontrado');
            return $response->withStatus(404);
        }

        // Verificar permiso
        $permStmt = $this->db->prepare("
            SELECT can_view, can_edit, can_delete 
            FROM user_module_permissions 
            WHERE user_id = ? AND module_id = ?
        ");
        $permStmt->bind_param('ii', $userId, $module['id']);
        $permStmt->execute();
        $perm = $permStmt->get_result()->fetch_assoc();
        $permStmt->close();

        $allowed = false;
        if ($perm) {
            if ($this->action === 'view') $allowed = (bool)$perm['can_view'];
            elseif ($this->action === 'edit') $allowed = (bool)$perm['can_edit'];
            elseif ($this->action === 'delete') $allowed = (bool)$perm['can_delete'];
        }

        if (!$allowed) {
            $response = new Response();
            $response->getBody()->write('No tienes permiso para acceder a este módulo.');
            return $response->withStatus(403);
        }

        return $handler->handle($request);
    }
}

