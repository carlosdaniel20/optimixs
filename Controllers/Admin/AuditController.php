<?php
// app/Controllers/Admin/AuditController.php

declare(strict_types=1);

namespace App\Controllers\Admin;

use mysqli;
use App\Models\AuditLogModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuditController
{
    /**
     * Mostrar página principal de auditoría
     */
    public function index(Request $request, Response $response): Response
    {
        // Verificar que sea admin
        if (($userTipo = $_SESSION['user']['tipo_utente'] ?? '') !== 'admin') {
            return $response->withHeader('Location', '/admin/dashboard')->withStatus(302);
        }

        ob_start();
        require __DIR__ . '/../../../views/admin/audit/index.php';
        $html = ob_get_clean();
        $response->getBody()->write($html);
        return $response;
    }

    /**
     * API para obtener logs de sesiones (DataTables)
     */
    public function getSessionLogs(Request $request, Response $response, mysqli $db): Response
    {
        // Verificar admin
        if (($_SESSION['user']['tipo_utente'] ?? '') !== 'admin') {
            $response->getBody()->write(json_encode(['error' => 'Unauthorized']));
            return $response->withStatus(403)->withHeader('Content-Type', 'application/json');
        }

        $params = $request->getQueryParams();
        $draw = (int)($params['draw'] ?? 1);
        $start = (int)($params['start'] ?? 0);
        $length = (int)($params['length'] ?? 25);
        $search = $params['search']['value'] ?? '';
        $orderColumn = (int)($params['order'][0]['column'] ?? 0);
        $orderDir = $params['order'][0]['dir'] ?? 'desc';

        // Mapeo de columnas
        $columns = ['login_time', 'user_name', 'ip_address', 'country', 'city', 'device_type', 'browser', 'os', 'duration_formatted'];
        $orderBy = $columns[$orderColumn] ?? 'login_time';

        // Query base con JOIN a utenti
        $baseSql = "FROM user_session_log s 
                    LEFT JOIN utenti u ON s.user_id = u.id";

        // Condiciones de búsqueda
        $where = [];
        $params_bind = [];
        $types = '';

        if (!empty($search)) {
            $where[] = "(u.email LIKE ? OR u.nome LIKE ? OR u.cognome LIKE ? OR s.ip_address LIKE ? OR s.country LIKE ? OR s.city LIKE ?)";
            $searchTerm = "%{$search}%";
            $params_bind = array_fill(0, 6, $searchTerm);
            $types = str_repeat('s', 6);
        }

        $whereClause = !empty($where) ? ' WHERE ' . implode(' AND ', $where) : '';

        // Count total
        $countSql = "SELECT COUNT(*) as total " . $baseSql . $whereClause;
        $stmt = $db->prepare($countSql);
        if (!empty($params_bind)) {
            $stmt->bind_param($types, ...$params_bind);
        }
        $stmt->execute();
        $totalRecords = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

        // Query con datos
        $dataSql = "SELECT 
                        s.*,
                        u.email,
                        u.nome,
                        u.cognome,
                        CONCAT(COALESCE(u.nome, ''), ' ', COALESCE(u.cognome, '')) as user_name,
                        DATE_FORMAT(s.login_time, '%d/%m/%Y %H:%i:%s') as login_formatted,
                        CASE 
                            WHEN s.logout_time IS NOT NULL THEN DATE_FORMAT(s.logout_time, '%d/%m/%Y %H:%i:%s')
                            ELSE 'Sesión activa'
                        END as logout_formatted,
                        CASE 
                            WHEN s.session_duration IS NOT NULL THEN 
                                CONCAT(FLOOR(s.session_duration / 3600), 'h ', FLOOR((s.session_duration % 3600) / 60), 'm')
                            ELSE '-'
                        END as duration_formatted
                    " . $baseSql . $whereClause . " 
                    ORDER BY {$orderBy} {$orderDir} 
                    LIMIT ? OFFSET ?";

        $stmt = $db->prepare($dataSql);
        if (!empty($params_bind)) {
            $params_bind[] = $length;
            $params_bind[] = $start;
            $types .= 'ii';
            $stmt->bind_param($types, ...$params_bind);
        } else {
            $stmt->bind_param('ii', $length, $start);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        $response->getBody()->write(json_encode([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * API para obtener logs de acciones (auditoría)
     */
    public function getActionLogs(Request $request, Response $response, mysqli $db): Response
    {
        // Verificar admin
        if (($_SESSION['user']['tipo_utente'] ?? '') !== 'admin') {
            $response->getBody()->write(json_encode(['error' => 'Unauthorized']));
            return $response->withStatus(403)->withHeader('Content-Type', 'application/json');
        }

        $params = $request->getQueryParams();
        $draw = (int)($params['draw'] ?? 1);
        $start = (int)($params['start'] ?? 0);
        $length = (int)($params['length'] ?? 25);
        $search = $params['search']['value'] ?? '';
        $actionFilter = $params['action_filter'] ?? '';
        $dateFrom = $params['date_from'] ?? '';
        $dateTo = $params['date_to'] ?? '';
        $orderColumn = (int)($params['order'][0]['column'] ?? 0);
        $orderDir = $params['order'][0]['dir'] ?? 'desc';

        // Mapeo de columnas
        $columns = ['created_at', 'user_name', 'action', 'target_user', 'field_name', 'old_value', 'new_value', 'ip_address'];
        $orderBy = $columns[$orderColumn] ?? 'created_at';

        // Query base
        $baseSql = "FROM user_audit_log a 
                    LEFT JOIN utenti u ON a.user_id = u.id
                    LEFT JOIN utenti t ON a.target_user_id = t.id";

        // Condiciones
        $where = [];
        $params_bind = [];
        $types = '';

        if (!empty($search)) {
            $where[] = "(u.email LIKE ? OR u.nome LIKE ? OR a.action LIKE ? OR a.ip_address LIKE ?)";
            $searchTerm = "%{$search}%";
            $params_bind = array_fill(0, 4, $searchTerm);
            $types = str_repeat('s', 4);
        }

        if (!empty($actionFilter)) {
            $where[] = "a.action = ?";
            $params_bind[] = $actionFilter;
            $types .= 's';
        }

        if (!empty($dateFrom)) {
            $where[] = "DATE(a.created_at) >= ?";
            $params_bind[] = $dateFrom;
            $types .= 's';
        }

        if (!empty($dateTo)) {
            $where[] = "DATE(a.created_at) <= ?";
            $params_bind[] = $dateTo;
            $types .= 's';
        }

        $whereClause = !empty($where) ? ' WHERE ' . implode(' AND ', $where) : '';

        // Count total
        $countSql = "SELECT COUNT(*) as total " . $baseSql . $whereClause;
        $stmt = $db->prepare($countSql);
        if (!empty($params_bind)) {
            $stmt->bind_param($types, ...$params_bind);
        }
        $stmt->execute();
        $totalRecords = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

        // Query con datos
        $dataSql = "SELECT 
                        a.*,
                        u.email as user_email,
                        u.nome as user_nome,
                        u.cognome as user_cognome,
                        CONCAT(COALESCE(u.nome, ''), ' ', COALESCE(u.cognome, '')) as user_name,
                        t.email as target_email,
                        CONCAT(COALESCE(t.nome, ''), ' ', COALESCE(t.cognome, '')) as target_name,
                        DATE_FORMAT(a.created_at, '%d/%m/%Y %H:%i:%s') as created_formatted
                    " . $baseSql . $whereClause . " 
                    ORDER BY {$orderBy} {$orderDir} 
                    LIMIT ? OFFSET ?";

        $stmt = $db->prepare($dataSql);
        $params_bind[] = $length;
        $params_bind[] = $start;
        $types .= 'ii';
        $stmt->bind_param($types, ...$params_bind);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        $response->getBody()->write(json_encode([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Obtener estadísticas de auditoría
     */
    public function getStats(Request $request, Response $response, mysqli $db): Response
    {
        if (($_SESSION['user']['tipo_utente'] ?? '') !== 'admin') {
            $response->getBody()->write(json_encode(['error' => 'Unauthorized']));
            return $response->withStatus(403)->withHeader('Content-Type', 'application/json');
        }

        $stats = [];

        // Total de usuarios activos hoy
        $stmt = $db->query("
            SELECT COUNT(DISTINCT user_id) as active_today 
            FROM user_session_log 
            WHERE DATE(login_time) = CURDATE()
        ");
        $stats['active_today'] = $stmt->fetch_assoc()['active_today'] ?? 0;

        // Total de sesiones hoy
        $stmt = $db->query("SELECT COUNT(*) as sessions_today FROM user_session_log WHERE DATE(login_time) = CURDATE()");
        $stats['sessions_today'] = $stmt->fetch_assoc()['sessions_today'] ?? 0;

        // Intentos fallidos hoy
        $stmt = $db->query("
            SELECT COUNT(*) as failed_logins 
            FROM user_audit_log 
            WHERE action = 'LOGIN_FAILED' AND DATE(created_at) = CURDATE()
        ");
        $stats['failed_logins'] = $stmt->fetch_assoc()['failed_logins'] ?? 0;

        // Países más comunes
        $stmt = $db->query("
            SELECT country, COUNT(*) as count 
            FROM user_session_log 
            WHERE country IS NOT NULL AND country != 'Local'
            GROUP BY country 
            ORDER BY count DESC 
            LIMIT 5
        ");
        $stats['top_countries'] = $stmt->fetch_all(MYSQLI_ASSOC);

        $response->getBody()->write(json_encode($stats));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
