<?php
declare(strict_types=1);

namespace App\Models;

use mysqli;
use Exception;

class RiskAIAnalysisModel
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    public function save(array $data): int
    {
        try {
            error_log("RiskAIAnalysisModel::save - INICIO");
            error_log("Datos recibidos: " . print_r($data, true));
            
            $stmt = $this->db->prepare("
                INSERT INTO risk_ai_analysis 
                (report_id, riesgo_nombre, prior, posterior, evidencias_detectadas, recomendaciones, usuario_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            
            if (!$stmt) {
                error_log("Error en prepare: " . $this->db->error);
                throw new Exception("Error en prepare: " . $this->db->error);
            }
            
            $evidenciasJson = json_encode($data['evidencias'] ?? [], JSON_UNESCAPED_UNICODE);
            $recomendacionesJson = json_encode($data['recomendaciones'] ?? [], JSON_UNESCAPED_UNICODE);
            
            error_log("Evidencias JSON: " . $evidenciasJson);
            error_log("Recomendaciones JSON: " . $recomendacionesJson);
            error_log("report_id: " . ($data['report_id'] ?? 'null'));
            error_log("riesgo_nombre: " . ($data['riesgo_nombre'] ?? 'null'));
            error_log("prior: " . ($data['prior'] ?? 'null'));
            error_log("posterior: " . ($data['posterior'] ?? 'null'));
            error_log("usuario_id: " . ($data['usuario_id'] ?? 'null'));
            
            $stmt->bind_param(
                'isdsssi',
                $data['report_id'],
                $data['riesgo_nombre'],
                $data['prior'],
                $data['posterior'],
                $evidenciasJson,
                $recomendacionesJson,
                $data['usuario_id']
            );
            
            if (!$stmt->execute()) {
                error_log("Error en execute: " . $stmt->error);
                throw new Exception("Error en execute: " . $stmt->error);
            }
            
            $id = $stmt->insert_id;
            $stmt->close();
            
            error_log("Insert exitoso, ID: " . $id);
            
            return $id;
            
        } catch (Exception $e) {
            error_log("EXCEPCIÓN en RiskAIAnalysisModel::save: " . $e->getMessage());
            error_log("Trace: " . $e->getTraceAsString());
            throw $e;
        }
    }

    // Obtener análisis por reporte
    public function getByReportId(int $reportId, int $limit = 10): array
    {
        try {
            $stmt = $this->db->prepare("
                SELECT a.*, u.nome as usuario_nombre 
                FROM risk_ai_analysis a
                LEFT JOIN utenti u ON a.usuario_id = u.id
                WHERE a.report_id = ?
                ORDER BY a.created_at DESC
                LIMIT ?
            ");
            
            if (!$stmt) {
                error_log("Error en prepare getByReportId: " . $this->db->error);
                return [];
            }
            
            $stmt->bind_param('ii', $reportId, $limit);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $row['evidencias_detectadas'] = json_decode($row['evidencias_detectadas'], true);
                $row['recomendaciones'] = json_decode($row['recomendaciones'], true);
                $data[] = $row;
            }
            
            $stmt->close();
            return $data;
            
        } catch (Exception $e) {
            error_log("Error en getByReportId: " . $e->getMessage());
            return [];
        }
    }

    // Obtener análisis por ID
    public function getById(int $id): ?array
    {
        try {
            $stmt = $this->db->prepare("
                SELECT a.*, u.nome as usuario_nombre 
                FROM risk_ai_analysis a
                LEFT JOIN utenti u ON a.usuario_id = u.id
                WHERE a.id = ?
            ");
            
            if (!$stmt) {
                error_log("Error en prepare getById: " . $this->db->error);
                return null;
            }
            
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();
            $stmt->close();
            
            if ($data) {
                $data['evidencias_detectadas'] = json_decode($data['evidencias_detectadas'], true);
                $data['recomendaciones'] = json_decode($data['recomendaciones'], true);
            }
            
            return $data ?: null;
            
        } catch (Exception $e) {
            error_log("Error en getById: " . $e->getMessage());
            return null;
        }
    }

    public function getAll(): array
    {
        try {
            $result = $this->db->query("
                SELECT a.*, u.nome as usuario_nombre 
                FROM risk_ai_analysis a
                LEFT JOIN utenti u ON a.usuario_id = u.id
                ORDER BY a.created_at DESC
            ");
            
            if (!$result) {
                error_log("Error en getAll query: " . $this->db->error);
                return [];
            }
            
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $row['evidencias_detectadas'] = json_decode($row['evidencias_detectadas'], true);
                $row['recomendaciones'] = json_decode($row['recomendaciones'], true);
                $data[] = $row;
            }
            
            return $data;
            
        } catch (Exception $e) {
            error_log("Error en getAll: " . $e->getMessage());
            return [];
        }
    }

    public function getStats(): array
    {
        $stats = [];
        
        try {
            $result = $this->db->query("SELECT COUNT(*) as total FROM risk_ai_analysis");
            $stats['total'] = (int)($result->fetch_assoc()['total'] ?? 0);
            
            $result = $this->db->query("SELECT AVG(posterior) as promedio FROM risk_ai_analysis");
            $stats['promedio_posterior'] = round((float)($result->fetch_assoc()['promedio'] ?? 0), 2);
            
        } catch (Exception $e) {
            error_log("Error en getStats: " . $e->getMessage());
            $stats['total'] = 0;
            $stats['promedio_posterior'] = 0;
        }
        
        return $stats;
    }
}
