<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Services\RiskIntelligenceService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use mysqli;

class RiskIntelligenceController
{
    private $riskService;
    
    public function __construct()
    {
        // No recibimos $db en el constructor
    }

    private function getDb(Request $request): mysqli
    {
        $container = $GLOBALS['container'] ?? $request->getAttribute('container');
        if ($container && $container->has('db')) {
            return $container->get('db');
        }
        $dbConfig = require dirname(__DIR__, 2) . '/config/database.php';
        $db = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['database']);
        if ($db->connect_error) {
            throw new \Exception('Error de conexión a la base de datos: ' . $db->connect_error);
        }
        return $db;
    }

    private function getRiskService(Request $request): RiskIntelligenceService
    {
        if ($this->riskService === null) {
            $db = $this->getDb($request);
            $this->riskService = new RiskIntelligenceService($db);
        }
        return $this->riskService;
    }

    // ============================================================
    // VISTA PRINCIPAL
    // ============================================================
    public function index(Request $request, Response $response): Response
    {
        $db = $this->getDb($request);
        
        // Obtener historial
        $profiles = [];
        $result = $db->query("
            SELECT p.*, 
                   (SELECT COUNT(*) FROM risk_scvs_data WHERE profile_id = p.id) as companies_count,
                   (SELECT COUNT(*) FROM risk_fiscalia_data WHERE profile_id = p.id) as cases_count
            FROM risk_profiles p 
            ORDER BY p.last_consultation DESC LIMIT 100
        ");
        while ($row = $result->fetch_assoc()) {
            if (!isset($row['risk_score']) || $row['risk_score'] === null) {
                $row['risk_score'] = $this->calculateRiskScore($row['companies_count'] ?? 0, $row['cases_count'] ?? 0);
            }
            $profiles[] = $row;
        }
        
        $content = $this->renderView('risk_intelligence/index.php', [
            'pageTitle' => 'Inteligencia de Riesgo',
            'profiles' => $profiles,
            'profileData' => null
        ]);
        return $this->renderWithLayout($response, $content);
    }

    // ============================================================
    // VER PERFIL (HTML)
    // ============================================================
    public function view(Request $request, Response $response, array $args): Response
    {
        $db = $this->getDb($request);
        $profileId = (int)($args['id'] ?? 0);
        
        // Detectar si es AJAX
        $isAjax = $request->getHeaderLine('X-Requested-With') === 'XMLHttpRequest' || 
                  isset($_GET['ajax']) || 
                  ($request->getQueryParams()['ajax'] ?? '') === '1';

        // Si es AJAX, devolver JSON
        if ($isAjax) {
            return $this->getProfileJson($request, $response, $args);
        }
        
        // Si no es AJAX, mostrar HTML normal
        if (!$profileId) {
            $_SESSION['error_message'] = 'ID de perfil inválido';
            return $response->withHeader('Location', '/risk-intelligence')->withStatus(302);
        }
        
        // Obtener el perfil
        $stmt = $db->prepare("SELECT * FROM risk_profiles WHERE id = ?");
        $stmt->bind_param('i', $profileId);
        $stmt->execute();
        $result = $stmt->get_result();
        $profile = $result->fetch_assoc();
        $stmt->close();
        
        if (!$profile) {
            $_SESSION['error_message'] = 'Perfil no encontrado';
            return $response->withHeader('Location', '/risk-intelligence')->withStatus(302);
        }
        
        // Obtener datos SCVS del perfil
        $companies = [];
        $stmt = $db->prepare("SELECT * FROM risk_scvs_data WHERE profile_id = ?");
        $stmt->bind_param('i', $profileId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            if (empty($row['tipo'])) {
                $role = strtoupper($row['role'] ?? '');
                $status = strtoupper($row['situacion'] ?? 'ACTIVA');
                $isActive = $status === 'ACTIVA';
                
                if (strpos($role, 'GERENTE') !== false || strpos($role, 'ADMINISTRADOR') !== false) {
                    $row['tipo'] = $isActive ? 'ADMINISTRACION_ACTUAL' : 'ADMINISTRACION_ANTERIOR';
                } else {
                    $row['tipo'] = $isActive ? 'ACCIONISTA_ACTUAL' : 'ACCIONISTA_ANTERIOR';
                }
            }
            $companies[] = $row;
        }
        $stmt->close();
        
        // Preparar los datos del perfil para mostrar
        $profileData = [
            'searched_document' => $profile['document_number'],
            'total_found' => count($companies),
            'companies' => $companies,
            'risk_score' => $profile['risk_score'] ?? $this->calculateRiskScore(count($companies), 0)
        ];
        
        // Obtener historial para la barra lateral (opcional)
        $profiles = [];
        $result = $db->query("
            SELECT p.*, 
                   (SELECT COUNT(*) FROM risk_scvs_data WHERE profile_id = p.id) as companies_count
            FROM risk_profiles p 
            ORDER BY p.last_consultation DESC LIMIT 50
        ");
        while ($row = $result->fetch_assoc()) {
            $profiles[] = $row;
        }
        
        // Renderizar la vista con los datos del perfil
        $content = $this->renderView('risk_intelligence/index.php', [
            'pageTitle' => 'Perfil de Riesgo - ' . $profile['document_number'],
            'profiles' => $profiles,
            'profileData' => $profileData
        ]);
        return $this->renderWithLayout($response, $content);
    }

    // ============================================================
    // HISTORIAL (VISTA HTML)
    // ============================================================
    public function history(Request $request, Response $response): Response
    {
        $db = $this->getDb($request);
        $profiles = [];
        $result = $db->query("
            SELECT p.*, 
                   (SELECT COUNT(*) FROM risk_scvs_data WHERE profile_id = p.id) as companies_count,
                   (SELECT COUNT(*) FROM risk_fiscalia_data WHERE profile_id = p.id) as cases_count
            FROM risk_profiles p 
            ORDER BY p.last_consultation DESC LIMIT 100
        ");
        while ($row = $result->fetch_assoc()) {
            if (!isset($row['risk_score']) || $row['risk_score'] === null) {
                $row['risk_score'] = $this->calculateRiskScore($row['companies_count'] ?? 0, $row['cases_count'] ?? 0);
            }
            $profiles[] = $row;
        }
        
        $content = $this->renderView('risk_intelligence/history.php', [
            'profiles' => $profiles,
            'pageTitle' => 'Historial de Consultas'
        ]);
        return $this->renderWithLayout($response, $content);
    }

    // ============================================================
    // OBTENER HISTORIAL EN JSON (para AJAX)
    // ============================================================
    public function getHistoryJson(Request $request, Response $response): Response
    {
        try {
            $db = $this->getDb($request);
            $profiles = [];
            
            $result = $db->query("
                SELECT p.*, 
                       (SELECT COUNT(*) FROM risk_scvs_data WHERE profile_id = p.id) as companies_count,
                       (SELECT COUNT(*) FROM risk_fiscalia_data WHERE profile_id = p.id) as cases_count
                FROM risk_profiles p 
                ORDER BY p.last_consultation DESC LIMIT 100
            ");
            
            while ($row = $result->fetch_assoc()) {
                if (!isset($row['risk_score']) || $row['risk_score'] === null) {
                    $row['risk_score'] = $this->calculateRiskScore($row['companies_count'] ?? 0, $row['cases_count'] ?? 0);
                }
                $profiles[] = [
                    'id' => (int)$row['id'],
                    'document_number' => (string)$row['document_number'],
                    'companies_count' => (int)($row['companies_count'] ?? 0),
                    'cases_count' => (int)($row['cases_count'] ?? 0),
                    'risk_score' => (float)($row['risk_score'] ?? 0),
                    'last_consultation' => (string)$row['last_consultation'],
                    'created_at' => (string)$row['created_at']
                ];
            }
            
            return $this->jsonResponse($response, [
                'success' => true,
                'data' => $profiles
            ]);
            
        } catch (\Exception $e) {
            error_log('Error en getHistoryJson: ' . $e->getMessage());
            return $this->jsonResponse($response, [
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ============================================================
    // OBTENER PERFIL EN JSON (para AJAX)
    // ============================================================
    public function getProfileJson(Request $request, Response $response, array $args): Response
    {
        try {
            $profileId = (int)($args['id'] ?? 0);
            
            if (!$profileId) {
                return $this->jsonResponse($response, [
                    'success' => false,
                    'error' => 'ID de perfil inválido'
                ], 400);
            }
            
            $db = $this->getDb($request);
            
            // Obtener el perfil
            $stmt = $db->prepare("SELECT * FROM risk_profiles WHERE id = ?");
            $stmt->bind_param('i', $profileId);
            $stmt->execute();
            $result = $stmt->get_result();
            $profile = $result->fetch_assoc();
            $stmt->close();
            
            if (!$profile) {
                return $this->jsonResponse($response, [
                    'success' => false,
                    'error' => 'Perfil no encontrado'
                ], 404);
            }
            
            // Obtener datos SCVS
            $companies = [];
            $stmt = $db->prepare("SELECT * FROM risk_scvs_data WHERE profile_id = ?");
            $stmt->bind_param('i', $profileId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                if (empty($row['tipo'])) {
                    $role = strtoupper($row['role'] ?? '');
                    $status = strtoupper($row['situacion'] ?? 'ACTIVA');
                    $isActive = $status === 'ACTIVA';
                    
                    if (strpos($role, 'GERENTE') !== false || strpos($role, 'ADMINISTRADOR') !== false) {
                        $row['tipo'] = $isActive ? 'ADMINISTRACION_ACTUAL' : 'ADMINISTRACION_ANTERIOR';
                    } else {
                        $row['tipo'] = $isActive ? 'ACCIONISTA_ACTUAL' : 'ACCIONISTA_ANTERIOR';
                    }
                }
                $companies[] = $row;
            }
            $stmt->close();
            
            $data = [
                'searched_document' => $profile['document_number'],
                'total_found' => count($companies),
                'companies' => $companies,
                'risk_score' => (float)($profile['risk_score'] ?? $this->calculateRiskScore(count($companies), 0))
            ];
            
            return $this->jsonResponse($response, [
                'success' => true,
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            error_log('Error en getProfileJson: ' . $e->getMessage());
            return $this->jsonResponse($response, [
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ============================================================
    // CALCULAR RISK SCORE
    // ============================================================
    private function calculateRiskScore($companiesCount, $casesCount): float
    {
        $score = 0;
        
        // Empresas vinculadas (máximo 50 puntos)
        if ($companiesCount > 0) {
            $score += min($companiesCount * 5, 50);
        }
        
        // Casos fiscales (máximo 30 puntos)
        if ($casesCount > 0) {
            $score += min($casesCount * 10, 30);
        }
        
        // Base mínima
        if ($score === 0) {
            $score = 5;
        }
        
        return min($score, 100);
    }

    // ============================================================
    // BÚSQUEDA AJAX
    // ============================================================
    public function search(Request $request, Response $response): Response
    {
        try {
            $params = $request->getQueryParams();
            $document = trim($params['document'] ?? '');
            
            if (empty($document)) {
                return $this->jsonResponse($response, ['success' => false, 'error' => 'Ingrese un número de cédula o RUC'], 400);
            }
            
            // Validar formato
            if (strlen($document) !== 10 && strlen($document) !== 13) {
                return $this->jsonResponse($response, ['success' => false, 'error' => 'Ingrese una cédula (10 dígitos) o RUC (13 dígitos) válido'], 400);
            }
            
            $userId = (int)($_SESSION['user']['id'] ?? 0);
            $riskService = $this->getRiskService($request);
            $profile = $riskService->getFullProfile($document, $userId);
            
            $scvsData = $profile['scvs'] ?? [];
            $companies = [];
            
            foreach ($scvsData as $item) {
                $company = [
                    'name' => $item['name'] ?? $item['company_name'] ?? '',
                    'ruc' => $item['ruc'] ?? $item['company_ruc'] ?? '',
                    'role' => $item['role'] ?? 'ACCIONISTA',
                    'percentage' => isset($item['participation_percentage']) ? (string)$item['participation_percentage'] : '0',
                    'situacion' => $item['situacion'] ?? 'ACTIVA',
                    'tipo' => $item['tipo'] ?? '',
                    'expediente' => $item['expediente'] ?? '',
                    'nacionalidad' => $item['nacionalidad'] ?? '',
                    'fecha_nombramiento' => $item['fecha_nombramiento'] ?? '',
                    'fecha_termino' => $item['fecha_termino'] ?? '',
                    'periodo' => $item['periodo'] ?? '',
                    'fecha_registro_mercantil' => $item['fecha_registro_mercantil'] ?? '',
                    'articulo' => $item['articulo'] ?? '',
                    'nro_registro_mercantil' => $item['nro_registro_mercantil'] ?? '',
                    'rl_adm' => $item['rl_adm'] ?? '',
                    'situacion_legal' => $item['situacion_legal'] ?? '',
                    'capital_invertido' => $item['capital_invertido'] ?? '',
                    'capital_total' => $item['capital_total'] ?? '',
                    'valor_nomina' => $item['valor_nomina'] ?? ''
                ];
                
                if (empty($company['tipo'])) {
                    $role = strtoupper($company['role']);
                    $status = strtoupper($company['situacion']);
                    $isActive = $status === 'ACTIVA';
                    
                    if (strpos($role, 'GERENTE') !== false || strpos($role, 'ADMINISTRADOR') !== false) {
                        $company['tipo'] = $isActive ? 'ADMINISTRACION_ACTUAL' : 'ADMINISTRACION_ANTERIOR';
                    } else {
                        $company['tipo'] = $isActive ? 'ACCIONISTA_ACTUAL' : 'ACCIONISTA_ANTERIOR';
                    }
                }
                
                if (!empty($company['name']) && !empty($company['ruc'])) {
                    $companies[] = $company;
                }
            }
            
            $data = [
                'success' => true,
                'data' => [
                    'searched_document' => $document,
                    'total_found' => count($companies),
                    'companies' => $companies
                ]
            ];
            
            return $this->jsonResponse($response, $data);
            
        } catch (\Exception $e) {
            error_log('Error en search: ' . $e->getMessage());
            return $this->jsonResponse($response, ['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // ============================================================
    // GUARDAR PERFIL
    // ============================================================
    public function save(Request $request, Response $response): Response
    {
        try {
            $data = (array) $request->getParsedBody();
            $document = trim($data['document'] ?? '');
            
            if (empty($document)) {
                return $this->jsonResponse($response, ['success' => false, 'message' => 'Documento requerido']);
            }
            
            $userId = (int)($_SESSION['user']['id'] ?? 0);
            $riskService = $this->getRiskService($request);
            $profile = $riskService->getFullProfile($document, $userId);
            
            $scvsCount = count($profile['scvs'] ?? []);
            
            return $this->jsonResponse($response, [
                'success' => true, 
                'message' => 'Perfil guardado correctamente. ' . $scvsCount . ' empresas vinculadas.',
                'profile_id' => $profile['id'] ?? null,
                'companies_found' => $scvsCount
            ]);
        } catch (\Exception $e) {
            error_log('Error en save: ' . $e->getMessage());
            return $this->jsonResponse($response, ['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ============================================================
    // ELIMINAR PERFIL
    // ============================================================
    public function delete(Request $request, Response $response, array $args): Response
    {
        try {
            $profileId = (int)($args['id'] ?? 0);
            
            if (!$profileId) {
                return $this->jsonResponse($response, ['success' => false, 'message' => 'ID inválido'], 400);
            }
            
            $db = $this->getDb($request);
            
            // Verificar que el perfil existe
            $stmt = $db->prepare("SELECT id FROM risk_profiles WHERE id = ?");
            $stmt->bind_param('i', $profileId);
            $stmt->execute();
            $result = $stmt->get_result();
            if (!$result->fetch_assoc()) {
                $stmt->close();
                return $this->jsonResponse($response, ['success' => false, 'message' => 'Perfil no encontrado'], 404);
            }
            $stmt->close();
            
            // Eliminar datos relacionados
            $db->query("DELETE FROM risk_scvs_data WHERE profile_id = $profileId");
            $db->query("DELETE FROM risk_audit_log WHERE profile_id = $profileId");
            
            // Eliminar el perfil
            $stmt = $db->prepare("DELETE FROM risk_profiles WHERE id = ?");
            $stmt->bind_param('i', $profileId);
            $stmt->execute();
            $affected = $stmt->affected_rows;
            $stmt->close();
            
            if ($affected > 0) {
                return $this->jsonResponse($response, ['success' => true, 'message' => 'Perfil eliminado correctamente']);
            } else {
                return $this->jsonResponse($response, ['success' => false, 'message' => 'No se pudo eliminar el perfil'], 500);
            }
            
        } catch (\Exception $e) {
            error_log('Error en delete: ' . $e->getMessage());
            return $this->jsonResponse($response, ['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // ============================================================
    // LIMPIAR TODO EL HISTORIAL
    // ============================================================
    public function clearAll(Request $request, Response $response): Response
    {
        try {
            $db = $this->getDb($request);
            
            $result = $db->query("SELECT COUNT(*) as count FROM risk_profiles");
            $row = $result->fetch_assoc();
            $count = (int)($row['count'] ?? 0);
            
            if ($count === 0) {
                return $this->jsonResponse($response, [
                    'success' => false, 
                    'message' => 'No hay consultas para eliminar'
                ], 400);
            }
            
            $db->query("DELETE FROM risk_scvs_data WHERE profile_id IN (SELECT id FROM risk_profiles)");
            $db->query("DELETE FROM risk_audit_log WHERE profile_id IN (SELECT id FROM risk_profiles)");
            $db->query("DELETE FROM risk_profiles");
            
            return $this->jsonResponse($response, [
                'success' => true, 
                'message' => "Se eliminaron $count consultas del historial"
            ]);
            
        } catch (\Exception $e) {
            error_log('Error en clearAll: ' . $e->getMessage());
            return $this->jsonResponse($response, [
                'success' => false, 
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // ============================================================
    // FUNCIONES AUXILIARES
    // ============================================================
    private function jsonResponse(Response $response, array $data, int $status = 200): Response
    {
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            $json = json_encode(['error' => 'Error al codificar JSON']);
        }
        $response->getBody()->write($json);
        return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus($status);
    }

    private function renderView(string $viewPath, array $data = []): string
    {
        extract($data);
        $fullPath = dirname(__DIR__, 2) . '/app/Views/' . $viewPath;
        if (!file_exists($fullPath)) {
            return "<div class='p-4 text-red-600'>Vista no encontrada: {$viewPath}</div>";
        }
        ob_start();
        require $fullPath;
        return ob_get_clean();
    }

    private function renderWithLayout(Response $response, string $content): Response
    {
        $layoutPath = dirname(__DIR__, 2) . '/app/Views/layout.php';
        if (file_exists($layoutPath)) {
            $GLOBALS['layout_content'] = $content;
            ob_start();
            require $layoutPath;
            $html = ob_get_clean();
            $response->getBody()->write($html);
        } else {
            $response->getBody()->write($content);
        }
        return $response;
    }
}
