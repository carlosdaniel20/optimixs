<?php

namespace App\Controllers\Api;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use mysqli;

class RiskIntelligenceApiController
{
    private $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    /**
     * GET /api/risk-intelligence/history
     * Obtener historial de consultas
     */
    public function getHistory(Request $request, Response $response): Response
    {
        try {
            $profiles = [];
            
            $result = $this->db->query("
                SELECT p.*, 
                       (SELECT COUNT(*) FROM risk_scvs_data WHERE profile_id = p.id) as companies_count,
                       (SELECT COUNT(*) FROM risk_fiscalia_data WHERE profile_id = p.id) as cases_count
                FROM risk_profiles p 
                ORDER BY p.id DESC
            ");
            
            while ($row = $result->fetch_assoc()) {
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
            
            $response->getBody()->write(json_encode([
                'success' => true,
                'data' => $profiles,
                'total' => count($profiles)
            ]));
            
            return $response->withHeader('Content-Type', 'application/json');
            
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    /**
     * GET /api/risk-intelligence/profile/{id}
     * Obtener perfil por ID
     */
    public function getProfile(Request $request, Response $response, array $args): Response
    {
        try {
            $profileId = (int)($args['id'] ?? 0);
            
            if (!$profileId) {
                $response->getBody()->write(json_encode([
                    'success' => false,
                    'error' => 'ID de perfil inválido'
                ]));
                return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
            }
            
            // Obtener el perfil
            $stmt = $this->db->prepare("SELECT * FROM risk_profiles WHERE id = ?");
            $stmt->bind_param('i', $profileId);
            $stmt->execute();
            $result = $stmt->get_result();
            $profile = $result->fetch_assoc();
            $stmt->close();
            
            if (!$profile) {
                $response->getBody()->write(json_encode([
                    'success' => false,
                    'error' => 'Perfil no encontrado'
                ]));
                return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
            }
            
            // Obtener datos SCVS
            $companies = [];
            $stmt = $this->db->prepare("SELECT * FROM risk_scvs_data WHERE profile_id = ?");
            $stmt->bind_param('i', $profileId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            while ($row = $result->fetch_assoc()) {
                // Determinar el tipo si no está definido
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
            
            $response->getBody()->write(json_encode([
                'success' => true,
                'data' => $data
            ]));
            
            return $response->withHeader('Content-Type', 'application/json');
            
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    /**
     * GET /api/risk-intelligence/search
     * Buscar por documento
     */
    public function search(Request $request, Response $response): Response
    {
        try {
            $params = $request->getQueryParams();
            $document = trim($params['document'] ?? '');
            
            if (empty($document)) {
                $response->getBody()->write(json_encode([
                    'success' => false,
                    'error' => 'Ingrese un número de cédula o RUC'
                ]));
                return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
            }
            
            if (strlen($document) !== 10 && strlen($document) !== 13) {
                $response->getBody()->write(json_encode([
                    'success' => false,
                    'error' => 'Ingrese una cédula (10 dígitos) o RUC (13 dígitos) válido'
                ]));
                return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
            }
            
            // Buscar en la base de datos primero
            $stmt = $this->db->prepare("SELECT id FROM risk_profiles WHERE document_number = ?");
            $stmt->bind_param('s', $document);
            $stmt->execute();
            $result = $stmt->get_result();
            $existing = $result->fetch_assoc();
            $stmt->close();
            
            if ($existing) {
                // Si existe, devolver el perfil
                return $this->getProfile($request, $response, ['id' => $existing['id']]);
            }
            
            // Si no existe, devolver error
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => 'No se encontró información para este documento'
            ]));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
            
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    /**
     * DELETE /api/risk-intelligence/profile/{id}
     * Eliminar perfil
     */
    public function deleteProfile(Request $request, Response $response, array $args): Response
    {
        try {
            $profileId = (int)($args['id'] ?? 0);
            
            if (!$profileId) {
                $response->getBody()->write(json_encode([
                    'success' => false,
                    'error' => 'ID de perfil inválido'
                ]));
                return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
            }
            
            // Verificar que el perfil existe
            $stmt = $this->db->prepare("SELECT id FROM risk_profiles WHERE id = ?");
            $stmt->bind_param('i', $profileId);
            $stmt->execute();
            $result = $stmt->get_result();
            if (!$result->fetch_assoc()) {
                $stmt->close();
                $response->getBody()->write(json_encode([
                    'success' => false,
                    'error' => 'Perfil no encontrado'
                ]));
                return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
            }
            $stmt->close();
            
            // Eliminar datos relacionados
            $this->db->query("DELETE FROM risk_scvs_data WHERE profile_id = $profileId");
            $this->db->query("DELETE FROM risk_audit_log WHERE profile_id = $profileId");
            
            // Eliminar el perfil
            $stmt = $this->db->prepare("DELETE FROM risk_profiles WHERE id = ?");
            $stmt->bind_param('i', $profileId);
            $stmt->execute();
            $affected = $stmt->affected_rows;
            $stmt->close();
            
            if ($affected > 0) {
                $response->getBody()->write(json_encode([
                    'success' => true,
                    'message' => 'Perfil eliminado correctamente'
                ]));
            } else {
                $response->getBody()->write(json_encode([
                    'success' => false,
                    'error' => 'No se pudo eliminar el perfil'
                ]));
                return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
            }
            
            return $response->withHeader('Content-Type', 'application/json');
            
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    /**
     * POST /api/risk-intelligence/clear-all
     * Limpiar todo el historial
     */
    public function clearAll(Request $request, Response $response): Response
    {
        try {
            $result = $this->db->query("SELECT COUNT(*) as count FROM risk_profiles");
            $row = $result->fetch_assoc();
            $count = (int)($row['count'] ?? 0);
            
            if ($count === 0) {
                $response->getBody()->write(json_encode([
                    'success' => false,
                    'error' => 'No hay consultas para eliminar'
                ]));
                return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
            }
            
            $this->db->query("DELETE FROM risk_scvs_data WHERE profile_id IN (SELECT id FROM risk_profiles)");
            $this->db->query("DELETE FROM risk_audit_log WHERE profile_id IN (SELECT id FROM risk_profiles)");
            $this->db->query("DELETE FROM risk_profiles");
            
            $response->getBody()->write(json_encode([
                'success' => true,
                'message' => "Se eliminaron $count consultas del historial"
            ]));
            
            return $response->withHeader('Content-Type', 'application/json');
            
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    }

    /**
     * Calcular risk score
     */
    private function calculateRiskScore($companiesCount, $casesCount): float
    {
        $score = 0;
        
        if ($companiesCount > 0) {
            $score += min($companiesCount * 5, 50);
        }
        
        if ($casesCount > 0) {
            $score += min($casesCount * 10, 30);
        }
        
        if ($score === 0) {
            $score = 5;
        }
        
        return min($score, 100);
    }
}
