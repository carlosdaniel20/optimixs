<?php
declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Services\RiskBayesAnalyzer;
use App\Services\RiskAlertService;
use App\Models\RiskReportModel;
use App\Models\RiskAIAnalysisModel;
use Exception;

class RiskAIReportController
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

    private function renderWithLayout(Response $response, string $content): Response
    {
        ob_start();
        require __DIR__ . '/../Views/layout.php';
        $full = ob_get_clean();
        $response->getBody()->write($full);
        return $response;
    }

    public function index(Request $request, Response $response): Response
    {
        ob_start();
        require __DIR__ . '/../Views/risk/ai-analisis.php';
        $content = ob_get_clean();
        return $this->renderWithLayout($response, $content);
    }

    public function dashboard(Request $request, Response $response): Response
    {
        ob_start();
        require __DIR__ . '/../Views/risk/risk-ai-dashboard.php';
        $content = ob_get_clean();
        return $this->renderWithLayout($response, $content);
    }

    public function getReportsList(Request $request, Response $response): Response
    {
        $db = $this->getDb($request);
        $model = new RiskReportModel($db);
        $reports = $model->getAll();
        
        foreach ($reports as &$report) {
            if ($report['riesgo_id']) {
                $stmt = $db->prepare("SELECT proceso FROM risk_matrix WHERE id = ?");
                $stmt->bind_param('i', $report['riesgo_id']);
                $stmt->execute();
                $res = $stmt->get_result();
                $row = $res->fetch_assoc();
                $report['riesgo_nombre'] = $row ? $row['proceso'] : 'No especificado';
                $stmt->close();
            } else {
                $report['riesgo_nombre'] = 'No asociado';
            }
            
            $usuarioNombre = 'Sistema';
            if ($report['usuario_id']) {
                $stmt = $db->prepare("SELECT nome, email FROM utenti WHERE id = ?");
                $stmt->bind_param('i', $report['usuario_id']);
                $stmt->execute();
                $userRes = $stmt->get_result();
                $user = $userRes->fetch_assoc();
                $usuarioNombre = $user ? ($user['nome'] ?: $user['email']) : "Usuario #{$report['usuario_id']}";
                $stmt->close();
            }
            $report['usuario_nombre'] = $usuarioNombre;
        }
        
        return $this->json($response, ['success' => true, 'data' => $reports]);
    }

    public function analyzeReport(Request $request, Response $response, array $args): Response
    {
        try {
            $reportId = (int)($args['id'] ?? 0);
            if ($reportId <= 0) {
                return $this->json($response, ['success' => false, 'error' => 'ID inválido'], 400);
            }
            
            $db = $this->getDb($request);
            $model = new RiskReportModel($db);
            $report = $model->getById($reportId);
            
            if (!$report) {
                return $this->json($response, ['success' => false, 'error' => 'Reporte no encontrado'], 404);
            }
            
            $analyzer = new RiskBayesAnalyzer();
            $result = $analyzer->analyzeReport($report);
            
            return $this->json($response, $result);
            
        } catch (\Exception $e) {
            error_log("Error en analyzeReport: " . $e->getMessage());
            return $this->json($response, ['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function saveAnalysis(Request $request, Response $response): Response
    {
        try {
            error_log("=== saveAnalysis INICIADO ===");
            
            $body = $request->getBody()->getContents();
            error_log("Body recibido (primeros 500 chars): " . substr($body, 0, 500));
            
            $data = json_decode($body, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                error_log("JSON Error: " . json_last_error_msg());
                return $this->json($response, [
                    'success' => false, 
                    'error' => 'JSON inválido: ' . json_last_error_msg()
                ], 400);
            }
            
            error_log("Data decodificada: report_id=" . ($data['report_id'] ?? 'null'));
            
            if (!$data || empty($data['report_id'])) {
                error_log("ERROR: Datos inválidos o report_id faltante");
                return $this->json($response, [
                    'success' => false, 
                    'error' => 'Datos inválidos: report_id requerido'
                ], 400);
            }
            
            $db = $this->getDb($request);
            error_log("Conexión DB obtenida");
            
            $reportModel = new RiskReportModel($db);
            $report = $reportModel->getById($data['report_id']);
            error_log("Reporte encontrado: " . ($report ? 'SI' : 'NO'));
            
            if (!$report) {
                return $this->json($response, [
                    'success' => false, 
                    'error' => 'Reporte no encontrado'
                ], 404);
            }
            
            $usuarioId = $_SESSION['user']['id'] ?? null;
            error_log("Usuario ID: " . ($usuarioId ?: 'null'));
            
            $analysisModel = new RiskAIAnalysisModel($db);
            error_log("Intentando guardar análisis...");
            
            $analysisId = $analysisModel->save([
                'report_id' => $data['report_id'],
                'riesgo_nombre' => $data['report_nombre'] ?? $report['nombre'],
                'prior' => $data['prior'] ?? 50,
                'posterior' => $data['posterior'] ?? 0,
                'evidencias' => $data['evidencias'] ?? [],
                'recomendaciones' => $data['recomendaciones'] ?? [],
                'usuario_id' => $usuarioId
            ]);
            
            error_log("Análisis guardado con ID: " . $analysisId);
            
            return $this->json($response, [
                'success' => true,
                'message' => 'Análisis guardado correctamente',
                'analysis_id' => $analysisId
            ]);
            
        } catch (Exception $e) {
            error_log("EXCEPCIÓN en saveAnalysis: " . $e->getMessage());
            error_log("Trace: " . $e->getTraceAsString());
            return $this->json($response, [
                'success' => false, 
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getSavedAnalyses(Request $request, Response $response): Response
    {
        try {
            $db = $this->getDb($request);
            $analysisModel = new RiskAIAnalysisModel($db);
            $analyses = $analysisModel->getAll();
            
            return $this->json($response, [
                'success' => true,
                'analyses' => $analyses,
                'total' => count($analyses)
            ]);
            
        } catch (\Exception $e) {
            return $this->json($response, ['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function getAnalysisById(Request $request, Response $response, array $args): Response
    {
        try {
            $analysisId = (int)($args['id'] ?? 0);
            if ($analysisId <= 0) {
                return $this->json($response, ['success' => false, 'error' => 'ID inválido'], 400);
            }
            
            $db = $this->getDb($request);
            $analysisModel = new RiskAIAnalysisModel($db);
            $analysis = $analysisModel->getById($analysisId);
            
            if (!$analysis) {
                return $this->json($response, ['success' => false, 'error' => 'Análisis no encontrado'], 404);
            }
            
            return $this->json($response, [
                'success' => true,
                'analysis' => $analysis
            ]);
            
        } catch (\Exception $e) {
            return $this->json($response, ['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // ==================== MÉTODOS PARA EL DASHBOARD ====================

    public function getStats(Request $request, Response $response): Response
    {
        try {
            $db = $this->getDb($request);
            
            $result = $db->query("SELECT COUNT(*) as total, AVG(posterior) as promedio FROM risk_ai_analysis");
            $row = $result->fetch_assoc();
            
            $criticos = $db->query("SELECT COUNT(*) as c FROM risk_ai_analysis WHERE posterior >= 70")->fetch_assoc();
            $altos = $db->query("SELECT COUNT(*) as c FROM risk_ai_analysis WHERE posterior >= 50 AND posterior < 70")->fetch_assoc();
            
            return $this->json($response, [
                'success' => true,
                'total' => (int)($row['total'] ?? 0),
                'promedio' => round((float)($row['promedio'] ?? 0), 1),
                'criticos' => (int)($criticos['c'] ?? 0),
                'altos' => (int)($altos['c'] ?? 0)
            ]);
        } catch (\Exception $e) {
            error_log("Error en getStats: " . $e->getMessage());
            return $this->json($response, ['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function getAllAnalyses(Request $request, Response $response): Response
    {
        try {
            $db = $this->getDb($request);
            $model = new RiskAIAnalysisModel($db);
            $analyses = $model->getAll();
            
            return $this->json($response, [
                'success' => true,
                'data' => $analyses
            ]);
        } catch (\Exception $e) {
            error_log("Error en getAllAnalyses: " . $e->getMessage());
            return $this->json($response, ['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function getEvolucion(Request $request, Response $response, array $args): Response
    {
        try {
            $reportId = (int)($args['id'] ?? 0);
            if ($reportId <= 0) {
                return $this->json($response, ['success' => false, 'error' => 'ID inválido'], 400);
            }
            
            $db = $this->getDb($request);
            $model = new RiskAIAnalysisModel($db);
            $analyses = $model->getByReportId($reportId, 20);
            
            return $this->json($response, [
                'success' => true,
                'data' => $analyses
            ]);
        } catch (\Exception $e) {
            error_log("Error en getEvolucion: " . $e->getMessage());
            return $this->json($response, ['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function viewAnalysis(Request $request, Response $response, array $args): Response
    {
        try {
            $analysisId = (int)($args['id'] ?? 0);
            if ($analysisId <= 0) {
                return $response->withHeader('Location', '/risk/ai-dashboard')->withStatus(302);
            }
            
            $db = $this->getDb($request);
            $model = new RiskAIAnalysisModel($db);
            $analysis = $model->getById($analysisId);
            
            if (!$analysis) {
                return $response->withHeader('Location', '/risk/ai-dashboard')->withStatus(302);
            }
            
            // Pasar el análisis a la vista
            ob_start();
            require __DIR__ . '/../Views/risk/risk-ai-analysis-detail.php';
            $content = ob_get_clean();
            return $this->renderWithLayout($response, $content);
            
        } catch (\Exception $e) {
            error_log("Error en viewAnalysis: " . $e->getMessage());
            return $response->withHeader('Location', '/risk/ai-dashboard')->withStatus(302);
        }
    }

    public function deleteAnalysis(Request $request, Response $response, array $args): Response
    {
        try {
            $analysisId = (int)($args['id'] ?? 0);
            if ($analysisId <= 0) {
                return $this->json($response, ['success' => false, 'error' => 'ID inválido'], 400);
            }
            
            $db = $this->getDb($request);
            
            $stmt = $db->prepare("DELETE FROM risk_ai_analysis WHERE id = ?");
            $stmt->bind_param('i', $analysisId);
            $stmt->execute();
            $deleted = $stmt->affected_rows;
            $stmt->close();
            
            if ($deleted > 0) {
                return $this->json($response, ['success' => true, 'message' => 'Análisis eliminado correctamente']);
            } else {
                return $this->json($response, ['success' => false, 'error' => 'No se encontró el análisis']);
            }
        } catch (\Exception $e) {
            error_log("Error en deleteAnalysis: " . $e->getMessage());
            return $this->json($response, ['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
