<?php
declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\RiskClassificationModel;
use App\Models\RiskMatrixModel;
use App\Models\RiskEvaluationModel;
use App\Support\Csrf;

class ApiRiskController
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
            throw new \Exception('Error de conexión a la base de datos: ' . $db->connect_error);
        }
        return $db;
    }

    private function json(Response $response, array $data, int $status = 200): Response
    {
        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }

    private function validateCsrf(array $data): void
    {
        if (!Csrf::validate($data['csrf_token'] ?? '')) {
            throw new \Exception('CSRF inválido');
        }
    }

    // ==================== CLASIFICACIONES ====================

    public function getClassifications(Request $request, Response $response): Response
    {
        $db = $this->getDb($request);
        $model = new RiskClassificationModel($db);
        return $this->json($response, ['success' => true, 'data' => $model->getAll()]);
    }

    public function createClassification(Request $request, Response $response): Response
    {
        $db = $this->getDb($request);
        $data = (array) $request->getParsedBody();
        if (!Csrf::validate($data['csrf_token'] ?? '')) {
            return $this->json($response, ['success' => false, 'error' => 'CSRF inválido'], 400);
        }
        $categoria = trim($data['categoria'] ?? '');
        $descripcion = trim($data['descripcion'] ?? '');
        if (!$categoria || !$descripcion) {
            return $this->json($response, ['success' => false, 'error' => 'Faltan datos'], 400);
        }
        $model = new RiskClassificationModel($db);
        $id = $model->create($categoria, $descripcion);
        return $this->json($response, ['success' => true, 'id' => $id]);
    }

    public function updateClassification(Request $request, Response $response, array $args): Response
    {
        $db = $this->getDb($request);
        $id = (int) ($args['id'] ?? 0);
        if ($id <= 0) {
            return $this->json($response, ['success' => false, 'error' => 'ID inválido'], 400);
        }
        $data = (array) $request->getParsedBody();
        if (!Csrf::validate($data['csrf_token'] ?? '')) {
            return $this->json($response, ['success' => false, 'error' => 'CSRF inválido'], 400);
        }
        $categoria = trim($data['categoria'] ?? '');
        $descripcion = trim($data['descripcion'] ?? '');
        if (!$categoria || !$descripcion) {
            return $this->json($response, ['success' => false, 'error' => 'Faltan datos'], 400);
        }
        $model = new RiskClassificationModel($db);
        $updated = $model->update($id, $categoria, $descripcion);
        return $this->json($response, ['success' => $updated]);
    }

    public function deleteClassification(Request $request, Response $response, array $args): Response
    {
        $db = $this->getDb($request);
        $id = (int) ($args['id'] ?? 0);
        if ($id <= 0) {
            return $this->json($response, ['success' => false, 'error' => 'ID inválido'], 400);
        }
        $data = (array) $request->getParsedBody();
        if (!Csrf::validate($data['csrf_token'] ?? '')) {
            return $this->json($response, ['success' => false, 'error' => 'CSRF inválido'], 400);
        }
        $model = new RiskClassificationModel($db);
        $deleted = $model->delete($id);
        return $this->json($response, ['success' => $deleted]);
    }

    // ==================== MATRIZ ====================

    public function getMatrix(Request $request, Response $response): Response
    {
        $db = $this->getDb($request);
        $model = new RiskMatrixModel($db);
        return $this->json($response, ['success' => true, 'data' => $model->getAll()]);
    }

    public function getMatrixById(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);
        if ($id <= 0) {
            return $this->json($response, ['success' => false, 'error' => 'ID inválido'], 400);
        }
        $db = $this->getDb($request);
        $stmt = $db->prepare("SELECT * FROM risk_matrix WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
        return $this->json($response, ['success' => true, 'data' => $data]);
    }

    public function createMatrix(Request $request, Response $response): Response
    {
        $db = $this->getDb($request);
        $data = (array) $request->getParsedBody();
        if (!Csrf::validate($data['csrf_token'] ?? '')) {
            return $this->json($response, ['success' => false, 'error' => 'CSRF inválido'], 400);
        }
        $required = ['proceso', 'descripcion', 'causaRaiz', 'clasificacion', 'probabilidad', 'impacto', 'zonaInherente', 'controles', 'probabilidadResidual', 'impactoResidual', 'zonaResidual', 'actividades', 'responsables', 'fechaImplementacion', 'fechaSeguimiento', 'seguimiento'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                return $this->json($response, ['success' => false, 'error' => "Falta campo: $field"], 400);
            }
        }
        $model = new RiskMatrixModel($db);
        $id = $model->create($data);
        return $this->json($response, ['success' => true, 'id' => $id]);
    }

    public function updateMatrix(Request $request, Response $response, array $args): Response
    {
        $db = $this->getDb($request);
        $id = (int) ($args['id'] ?? 0);
        if ($id <= 0) {
            return $this->json($response, ['success' => false, 'error' => 'ID inválido'], 400);
        }
        $data = (array) $request->getParsedBody();
        if (!Csrf::validate($data['csrf_token'] ?? '')) {
            return $this->json($response, ['success' => false, 'error' => 'CSRF inválido'], 400);
        }
        $required = ['proceso', 'descripcion', 'causaRaiz', 'clasificacion', 'probabilidad', 'impacto', 'zonaInherente', 'controles', 'probabilidadResidual', 'impactoResidual', 'zonaResidual', 'actividades', 'responsables', 'fechaImplementacion', 'fechaSeguimiento', 'seguimiento'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                return $this->json($response, ['success' => false, 'error' => "Falta campo: $field"], 400);
            }
        }
        $model = new RiskMatrixModel($db);
        $updated = $model->update($id, $data);
        return $this->json($response, ['success' => $updated]);
    }

    public function deleteMatrix(Request $request, Response $response, array $args): Response
    {
        $db = $this->getDb($request);
        $id = (int) ($args['id'] ?? 0);
        if ($id <= 0) {
            return $this->json($response, ['success' => false, 'error' => 'ID inválido'], 400);
        }
        $data = (array) $request->getParsedBody();
        if (!Csrf::validate($data['csrf_token'] ?? '')) {
            return $this->json($response, ['success' => false, 'error' => 'CSRF inválido'], 400);
        }
        $model = new RiskMatrixModel($db);
        $deleted = $model->delete($id);
        return $this->json($response, ['success' => $deleted]);
    }

    // ==================== EVALUACIONES ====================

    public function getEvaluations(Request $request, Response $response): Response
    {
        $db = $this->getDb($request);
        $model = new RiskEvaluationModel($db);
        return $this->json($response, ['success' => true, 'data' => $model->getAll()]);
    }

    public function getEvaluationById(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);
        if ($id <= 0) {
            return $this->json($response, ['success' => false, 'error' => 'ID inválido'], 400);
        }
        $db = $this->getDb($request);
        $stmt = $db->prepare("SELECT * FROM risk_evaluations WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
        return $this->json($response, ['success' => true, 'data' => $data]);
    }

    public function createEvaluation(Request $request, Response $response): Response
    {
        $db = $this->getDb($request);
        $data = (array) $request->getParsedBody();
        if (!Csrf::validate($data['csrf_token'] ?? '')) {
            return $this->json($response, ['success' => false, 'error' => 'CSRF inválido'], 400);
        }
        $required = ['descripcion', 'causaRaiz', 'clasificacion', 'probabilidad', 'pesoProbabilidad', 'impacto', 'pesoImpacto', 'zonaRiesgo', 'controles', 'tipo', 'implementacion', 'documentado', 'frecuencia', 'evidencia', 'valoracion', 'probabilidadResidual', 'impactoResidual', 'zonaResidual'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                return $this->json($response, ['success' => false, 'error' => "Falta campo: $field"], 400);
            }
        }
        $model = new RiskEvaluationModel($db);
        $id = $model->create($data);
        return $this->json($response, ['success' => true, 'id' => $id]);
    }

    public function updateEvaluation(Request $request, Response $response, array $args): Response
    {
        $db = $this->getDb($request);
        $id = (int) ($args['id'] ?? 0);
        if ($id <= 0) {
            return $this->json($response, ['success' => false, 'error' => 'ID inválido'], 400);
        }
        $data = (array) $request->getParsedBody();
        if (!Csrf::validate($data['csrf_token'] ?? '')) {
            return $this->json($response, ['success' => false, 'error' => 'CSRF inválido'], 400);
        }
        $required = ['descripcion', 'causaRaiz', 'clasificacion', 'probabilidad', 'pesoProbabilidad', 'impacto', 'pesoImpacto', 'zonaRiesgo', 'controles', 'tipo', 'implementacion', 'documentado', 'frecuencia', 'evidencia', 'valoracion', 'probabilidadResidual', 'impactoResidual', 'zonaResidual'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                return $this->json($response, ['success' => false, 'error' => "Falta campo: $field"], 400);
            }
        }
        $model = new RiskEvaluationModel($db);
        $updated = $model->update($id, $data);
        return $this->json($response, ['success' => $updated]);
    }

    public function deleteEvaluation(Request $request, Response $response, array $args): Response
    {
        $db = $this->getDb($request);
        $id = (int) ($args['id'] ?? 0);
        if ($id <= 0) {
            return $this->json($response, ['success' => false, 'error' => 'ID inválido'], 400);
        }
        $data = (array) $request->getParsedBody();
        if (!Csrf::validate($data['csrf_token'] ?? '')) {
            return $this->json($response, ['success' => false, 'error' => 'CSRF inválido'], 400);
        }
        $model = new RiskEvaluationModel($db);
        $deleted = $model->delete($id);
        return $this->json($response, ['success' => $deleted]);
    }

    // ==================== IMPACTOS ====================

    public function getImpacts(Request $request, Response $response): Response
    {
        $db = $this->getDb($request);
        $result = $db->query("SELECT * FROM risk_impacts ORDER BY id");
        return $this->json($response, ['success' => true, 'data' => $result->fetch_all(MYSQLI_ASSOC)]);
    }

    public function getImpactById(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);
        if ($id <= 0) {
            return $this->json($response, ['success' => false, 'error' => 'ID inválido'], 400);
        }
        $db = $this->getDb($request);
        $stmt = $db->prepare("SELECT * FROM risk_impacts WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
        return $this->json($response, ['success' => true, 'data' => $data]);
    }

    public function createImpact(Request $request, Response $response): Response
    {
        $db = $this->getDb($request);
        $data = (array) $request->getParsedBody();
        try {
            $this->validateCsrf($data);
        } catch (\Exception $e) {
            return $this->json($response, ['success' => false, 'error' => $e->getMessage()], 400);
        }
        $nivel = trim($data['nivel'] ?? '');
        $impacto = trim($data['impacto'] ?? '');
        $descEconomica = trim($data['descripcion_economica'] ?? '');
        $descReputacional = trim($data['descripcion_reputacional'] ?? '');
        $otros = trim($data['otros_impactos'] ?? '');
        if (!$nivel || !$impacto) {
            return $this->json($response, ['success' => false, 'error' => 'Faltan datos requeridos'], 400);
        }
        $stmt = $db->prepare("INSERT INTO risk_impacts (nivel, impacto, descripcion_economica, descripcion_reputacional, otros_impactos) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('sssss', $nivel, $impacto, $descEconomica, $descReputacional, $otros);
        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        return $this->json($response, ['success' => true, 'id' => $id]);
    }

    public function updateImpact(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);
        if ($id <= 0) {
            return $this->json($response, ['success' => false, 'error' => 'ID inválido'], 400);
        }
        $db = $this->getDb($request);
        $data = (array) $request->getParsedBody();
        try {
            $this->validateCsrf($data);
        } catch (\Exception $e) {
            return $this->json($response, ['success' => false, 'error' => $e->getMessage()], 400);
        }
        $nivel = trim($data['nivel'] ?? '');
        $impacto = trim($data['impacto'] ?? '');
        $descEconomica = trim($data['descripcion_economica'] ?? '');
        $descReputacional = trim($data['descripcion_reputacional'] ?? '');
        $otros = trim($data['otros_impactos'] ?? '');
        if (!$nivel || !$impacto) {
            return $this->json($response, ['success' => false, 'error' => 'Faltan datos requeridos'], 400);
        }
        $stmt = $db->prepare("UPDATE risk_impacts SET nivel=?, impacto=?, descripcion_economica=?, descripcion_reputacional=?, otros_impactos=? WHERE id=?");
        $stmt->bind_param('sssssi', $nivel, $impacto, $descEconomica, $descReputacional, $otros, $id);
        $stmt->execute();
        $stmt->close();
        return $this->json($response, ['success' => true]);
    }

    public function deleteImpact(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);
        if ($id <= 0) {
            return $this->json($response, ['success' => false, 'error' => 'ID inválido'], 400);
        }
        $db = $this->getDb($request);
        $data = (array) $request->getParsedBody();
        try {
            $this->validateCsrf($data);
        } catch (\Exception $e) {
            return $this->json($response, ['success' => false, 'error' => $e->getMessage()], 400);
        }
        $stmt = $db->prepare("DELETE FROM risk_impacts WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();
        return $this->json($response, ['success' => true]);
    }

    // ==================== PROBABILIDADES ====================

    public function getProbabilities(Request $request, Response $response): Response
    {
        $db = $this->getDb($request);
        $result = $db->query("SELECT * FROM risk_probabilities ORDER BY porcentaje");
        return $this->json($response, ['success' => true, 'data' => $result->fetch_all(MYSQLI_ASSOC)]);
    }

    public function bulkUpdateProbabilities(Request $request, Response $response): Response
    {
        $db = $this->getDb($request);
        $data = (array) $request->getParsedBody();
        try {
            $this->validateCsrf($data);
        } catch (\Exception $e) {
            return $this->json($response, ['success' => false, 'error' => $e->getMessage()], 400);
        }
        $probabilities = json_decode($data['probabilities'] ?? '[]', true);
        if (!is_array($probabilities) || empty($probabilities)) {
            return $this->json($response, ['success' => false, 'error' => 'Datos inválidos'], 400);
        }
        foreach ($probabilities as $p) {
            $stmt = $db->prepare("UPDATE risk_probabilities SET nivel=?, porcentaje=?, frecuencia=?, color=? WHERE id=?");
            $stmt->bind_param('sissi', $p['nivel'], $p['porcentaje'], $p['frecuencia'], $p['color'], $p['id']);
            $stmt->execute();
            $stmt->close();
        }
        return $this->json($response, ['success' => true]);
    }
    
    // ==================== REPORTES ====================

  public function saveReport(Request $request, Response $response): Response
{
    $data = (array) $request->getParsedBody();
    if (!Csrf::validate($data['csrf_token'] ?? '')) {
        return $this->json($response, ['success' => false, 'error' => 'CSRF inválido'], 400);
    }
    $nombre = trim($data['nombre'] ?? '');
    $contenido = trim($data['contenido'] ?? '');
    $riesgoId = isset($data['riesgo_id']) ? (int) $data['riesgo_id'] : null;
    $organizationName = trim($data['organization_name'] ?? '');
    $igr = isset($data['igr']) ? (float) $data['igr'] : null;
    
    if (!$nombre || !$contenido) {
        return $this->json($response, ['success' => false, 'error' => 'Nombre y contenido requeridos'], 400);
    }
    
    $db = $this->getDb($request);
    $usuarioId = $_SESSION['user']['id'] ?? null;
    
    // Insertar reporte
    $stmt = $db->prepare("INSERT INTO risk_reports (nombre, contenido, riesgo_id, usuario_id, organization_name, igr, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param('ssiisd', $nombre, $contenido, $riesgoId, $usuarioId, $organizationName, $igr);
    $stmt->execute();
    $id = $stmt->insert_id;
    $stmt->close();
    
    // ========== GENERAR QR ==========
    $qrBase64 = \App\Helpers\QRHelper::generateRiskReportQR($id, [
        'nombre' => $nombre,
        'contenido' => $contenido
    ]);
    
    if ($qrBase64) {
        $updateStmt = $db->prepare("UPDATE risk_reports SET qr_code = ? WHERE id = ?");
        $updateStmt->bind_param('si', $qrBase64, $id);
        $updateStmt->execute();
        $updateStmt->close();
    }
    // ================================
    
    return $this->json($response, ['success' => true, 'id' => $id]);
}

    public function getReports(Request $request, Response $response): Response
    {
        $db = $this->getDb($request);
        $model = new \App\Models\RiskReportModel($db);
        $reports = $model->getAll();
        return $this->json($response, ['success' => true, 'data' => $reports]);
    }

   public function getReportById(Request $request, Response $response, array $args): Response
{
    $id = (int) ($args['id'] ?? 0);
    if (!$id) {
        return $this->json($response, ['success' => false, 'error' => 'ID inválido'], 400);
    }
    $db = $this->getDb($request);
    $model = new \App\Models\RiskReportModel($db);
    $report = $model->getById($id);
    
    if (!$report) {
        return $this->json($response, ['success' => false, 'error' => 'Reporte no encontrado'], 404);
    }
    
    // ========== NUEVO: CARGAR ARCHIVOS ADJUNTOS ==========
    $fileModel = new \App\Models\RiskReportFileModel($db);
    $files = $fileModel->getByReportId($id);
    $report['adjuntos'] = $files;
    // ====================================================
    
    return $this->json($response, ['success' => true, 'data' => $report]);
}

    public function deleteReport(Request $request, Response $response, array $args): Response
    {
        $id = (int) ($args['id'] ?? 0);
        if (!$id) {
            return $this->json($response, ['success' => false, 'error' => 'ID inválido'], 400);
        }
        $data = (array) $request->getParsedBody();
        if (!Csrf::validate($data['csrf_token'] ?? '')) {
            return $this->json($response, ['success' => false, 'error' => 'CSRF inválido'], 400);
        }
        $db = $this->getDb($request);
        $model = new \App\Models\RiskReportModel($db);
        $deleted = $model->delete($id);
        return $this->json($response, ['success' => $deleted]);
    } 

    // ==================== ORGANIZACIÓN ====================

    public function getAllOrganizations(Request $request, Response $response): Response
    {
        $db = $this->getDb($request);
        $result = $db->query("SELECT id, nombre, ruc, sector, telefono, direccion, proceso_nombre, proceso_justificacion, created_at, updated_at FROM risk_organization ORDER BY nombre ASC");
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $this->json($response, ['success' => true, 'data' => $data]);
    }

    public function getOrganizationById(Request $request, Response $response, array $args): Response
    {
        $id = (int)($args['id'] ?? 0);
        if ($id <= 0) {
            return $this->json($response, ['success' => false, 'error' => 'ID inválido'], 400);
        }
        $db = $this->getDb($request);
        $stmt = $db->prepare("SELECT id, nombre, ruc, sector, telefono, direccion, proceso_nombre, proceso_justificacion, created_at, updated_at FROM risk_organization WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $org = $result->fetch_assoc();
        $stmt->close();
        if (!$org) {
            return $this->json($response, ['success' => false, 'error' => 'No encontrado'], 404);
        }
        return $this->json($response, ['success' => true, 'data' => $org]);
    }

    public function createOrganization(Request $request, Response $response): Response
    {
        $data = (array) $request->getParsedBody();
        if (!Csrf::validate($data['csrf_token'] ?? '')) {
            return $this->json($response, ['success' => false, 'error' => 'CSRF inválido'], 400);
        }
        $nombre = trim($data['nombre'] ?? '');
        $ruc = trim($data['ruc'] ?? '');
        $sector = trim($data['sector'] ?? '');
        $telefono = trim($data['telefono'] ?? '');
        $direccion = trim($data['direccion'] ?? '');
        $procesoNombre = trim($data['proceso_nombre'] ?? '');
        $procesoJustificacion = trim($data['proceso_justificacion'] ?? '');
        
        if (!$nombre || !$procesoNombre) {
            return $this->json($response, ['success' => false, 'error' => 'Nombre y proceso son obligatorios'], 400);
        }
        
        $db = $this->getDb($request);
        $stmt = $db->prepare("INSERT INTO risk_organization (nombre, ruc, sector, telefono, direccion, proceso_nombre, proceso_justificacion) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sssssss', $nombre, $ruc, $sector, $telefono, $direccion, $procesoNombre, $procesoJustificacion);
        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        
        return $this->json($response, ['success' => true, 'id' => $id]);
    }

    public function updateOrganization(Request $request, Response $response, array $args): Response
    {
        $id = (int)($args['id'] ?? 0);
        if ($id <= 0) {
            return $this->json($response, ['success' => false, 'error' => 'ID inválido'], 400);
        }
        $data = (array) $request->getParsedBody();
        if (!Csrf::validate($data['csrf_token'] ?? '')) {
            return $this->json($response, ['success' => false, 'error' => 'CSRF inválido'], 400);
        }
        $nombre = trim($data['nombre'] ?? '');
        $ruc = trim($data['ruc'] ?? '');
        $sector = trim($data['sector'] ?? '');
        $telefono = trim($data['telefono'] ?? '');
        $direccion = trim($data['direccion'] ?? '');
        $procesoNombre = trim($data['proceso_nombre'] ?? '');
        $procesoJustificacion = trim($data['proceso_justificacion'] ?? '');
        
        if (!$nombre || !$procesoNombre) {
            return $this->json($response, ['success' => false, 'error' => 'Nombre y proceso son obligatorios'], 400);
        }
        
        $db = $this->getDb($request);
        $stmt = $db->prepare("UPDATE risk_organization SET nombre=?, ruc=?, sector=?, telefono=?, direccion=?, proceso_nombre=?, proceso_justificacion=? WHERE id=?");
        $stmt->bind_param('sssssssi', $nombre, $ruc, $sector, $telefono, $direccion, $procesoNombre, $procesoJustificacion, $id);
        $stmt->execute();
        $stmt->close();
        
        return $this->json($response, ['success' => true]);
    }

    public function deleteOrganization(Request $request, Response $response, array $args): Response
    {
        $id = (int)($args['id'] ?? 0);
        if ($id <= 0) {
            return $this->json($response, ['success' => false, 'error' => 'ID inválido'], 400);
        }
        $data = (array) $request->getParsedBody();
        if (!Csrf::validate($data['csrf_token'] ?? '')) {
            return $this->json($response, ['success' => false, 'error' => 'CSRF inválido'], 400);
        }
        $db = $this->getDb($request);
        $stmt = $db->prepare("DELETE FROM risk_organization WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $deleted = $stmt->affected_rows;
        $stmt->close();
        
        return $this->json($response, ['success' => $deleted > 0]);
    }

    public function updateOrganizationPost(Request $request, Response $response, array $args): Response
    {
        return $this->updateOrganization($request, $response, $args);
    }

    public function updateClassificationPost(Request $request, Response $response, array $args): Response
    {
        $id = (int)($args['id'] ?? 0);
        if ($id <= 0) {
            return $this->json($response, ['success' => false, 'error' => 'ID inválido'], 400);
        }
        $data = (array) $request->getParsedBody();
        if (!Csrf::validate($data['csrf_token'] ?? '')) {
            return $this->json($response, ['success' => false, 'error' => 'CSRF inválido'], 400);
        }
        $categoria = trim($data['categoria'] ?? '');
        $descripcion = trim($data['descripcion'] ?? '');
        if (!$categoria || !$descripcion) {
            return $this->json($response, ['success' => false, 'error' => 'Faltan datos'], 400);
        }
        $db = $this->getDb($request);
        $model = new \App\Models\RiskClassificationModel($db);
        $updated = $model->update($id, $categoria, $descripcion);
        return $this->json($response, ['success' => $updated]);
    }

    public function updateMatrixPost(Request $request, Response $response, array $args): Response
    {
        $id = (int)($args['id'] ?? 0);
        if ($id <= 0) {
            return $this->json($response, ['success' => false, 'error' => 'ID inválido'], 400);
        }
        $data = (array) $request->getParsedBody();
        if (!Csrf::validate($data['csrf_token'] ?? '')) {
            return $this->json($response, ['success' => false, 'error' => 'CSRF inválido'], 400);
        }
        $required = ['proceso', 'descripcion', 'causaRaiz', 'clasificacion', 'probabilidad', 'impacto', 'zonaInherente', 'controles', 'probabilidadResidual', 'impactoResidual', 'zonaResidual', 'actividades', 'responsables', 'fechaImplementacion', 'fechaSeguimiento', 'seguimiento'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                return $this->json($response, ['success' => false, 'error' => "Falta campo: $field"], 400);
            }
        }
        $db = $this->getDb($request);
        $model = new \App\Models\RiskMatrixModel($db);
        $updated = $model->update($id, $data);
        return $this->json($response, ['success' => $updated]);
    }

    public function updateEvaluationPost(Request $request, Response $response, array $args): Response
    {
        $id = (int)($args['id'] ?? 0);
        if ($id <= 0) {
            return $this->json($response, ['success' => false, 'error' => 'ID inválido'], 400);
        }
        $data = (array) $request->getParsedBody();
        if (!Csrf::validate($data['csrf_token'] ?? '')) {
            return $this->json($response, ['success' => false, 'error' => 'CSRF inválido'], 400);
        }
        $required = ['descripcion', 'causaRaiz', 'clasificacion', 'probabilidad', 'pesoProbabilidad', 'impacto', 'pesoImpacto', 'zonaRiesgo', 'controles', 'tipo', 'implementacion', 'documentado', 'frecuencia', 'evidencia', 'valoracion', 'probabilidadResidual', 'impactoResidual', 'zonaResidual'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                return $this->json($response, ['success' => false, 'error' => "Falta campo: $field"], 400);
            }
        }
        $db = $this->getDb($request);
        $model = new \App\Models\RiskEvaluationModel($db);
        $updated = $model->update($id, $data);
        return $this->json($response, ['success' => $updated]);
    }

    // ==================== ANÁLISIS ====================
    public function getAnalisisList(Request $request, Response $response): Response
    {
        $db = $this->getDb($request);
        $model = new \App\Models\RiskAnalisisModel($db);
        $list = $model->getAll();
        return $this->json($response, ['success' => true, 'data' => $list]);
    }

    public function createAnalisis(Request $request, Response $response): Response
    {
        $data = (array) $request->getParsedBody();
        if (!Csrf::validate($data['csrf_token'] ?? '')) {
            return $this->json($response, ['success' => false, 'error' => 'CSRF inválido'], 400);
        }
        $riesgoId = (int)($data['riesgo_id'] ?? 0);
        $nombre = trim($data['nombre'] ?? '');
        if (!$riesgoId || !$nombre) {
            return $this->json($response, ['success' => false, 'error' => 'Faltan datos'], 400);
        }
        $db = $this->getDb($request);
        $model = new \App\Models\RiskAnalisisModel($db);
        $id = $model->create($riesgoId, $nombre);
        return $this->json($response, ['success' => true, 'id' => $id]);
    }

    public function deleteAnalisis(Request $request, Response $response, array $args): Response
    {
        $id = (int)($args['id'] ?? 0);
        if ($id <= 0) {
            return $this->json($response, ['success' => false, 'error' => 'ID inválido'], 400);
        }
        $data = (array) $request->getParsedBody();
        if (!Csrf::validate($data['csrf_token'] ?? '')) {
            return $this->json($response, ['success' => false, 'error' => 'CSRF inválido'], 400);
        }
        $db = $this->getDb($request);
        $model = new \App\Models\RiskAnalisisModel($db);
        $deleted = $model->delete($id);
        return $this->json($response, ['success' => $deleted]);
    }

    // ==================== CHECKLIST TEMPLATES ====================
    public function getChecklistTemplates(Request $request, Response $response): Response
    {
        $db = $this->getDb($request);
        $model = new \App\Models\RiskChecklistTemplateModel($db);
        $templates = $model->getAll();
        return $this->json($response, ['success' => true, 'data' => $templates]);
    }

    public function createChecklistTemplate(Request $request, Response $response): Response
    {
        $data = (array) $request->getParsedBody();
        if (!Csrf::validate($data['csrf_token'] ?? '')) {
            return $this->json($response, ['success' => false, 'error' => 'CSRF inválido'], 400);
        }
        $area = trim($data['area'] ?? '');
        $descripcion = trim($data['descripcion'] ?? '');
        if (!$area || !$descripcion) {
            return $this->json($response, ['success' => false, 'error' => 'Faltan datos'], 400);
        }
        $templateData = [
            'area' => $area,
            'descripcion' => $descripcion,
            'es_critica' => isset($data['es_critica']) ? 1 : 0,
            'peh_riesgo' => (int)($data['peh_riesgo'] ?? 80),
            'penh_riesgo' => (int)($data['penh_riesgo'] ?? 10),
            'orden' => (int)($data['orden'] ?? 0),
        ];
        $db = $this->getDb($request);
        $model = new \App\Models\RiskChecklistTemplateModel($db);
        $id = $model->create($templateData);
        return $this->json($response, ['success' => true, 'id' => $id]);
    }

    public function updateChecklistTemplate(Request $request, Response $response, array $args): Response
    {
        $id = (int)($args['id'] ?? 0);
        if ($id <= 0) {
            return $this->json($response, ['success' => false, 'error' => 'ID inválido'], 400);
        }
        $data = (array) $request->getParsedBody();
        if (!Csrf::validate($data['csrf_token'] ?? '')) {
            return $this->json($response, ['success' => false, 'error' => 'CSRF inválido'], 400);
        }
        $area = trim($data['area'] ?? '');
        $descripcion = trim($data['descripcion'] ?? '');
        if (!$area || !$descripcion) {
            return $this->json($response, ['success' => false, 'error' => 'Faltan datos'], 400);
        }
        $templateData = [
            'area' => $area,
            'descripcion' => $descripcion,
            'es_critica' => isset($data['es_critica']) ? 1 : 0,
            'peh_riesgo' => (int)($data['peh_riesgo'] ?? 80),
            'penh_riesgo' => (int)($data['penh_riesgo'] ?? 10),
            'orden' => (int)($data['orden'] ?? 0),
        ];
        $db = $this->getDb($request);
        $model = new \App\Models\RiskChecklistTemplateModel($db);
        $updated = $model->update($id, $templateData);
        return $this->json($response, ['success' => $updated]);
    }

    public function deleteChecklistTemplate(Request $request, Response $response, array $args): Response
    {
        $id = (int)($args['id'] ?? 0);
        if ($id <= 0) {
            return $this->json($response, ['success' => false, 'error' => 'ID inválido'], 400);
        }
        $data = (array) $request->getParsedBody();
        if (!Csrf::validate($data['csrf_token'] ?? '')) {
            return $this->json($response, ['success' => false, 'error' => 'CSRF inválido'], 400);
        }
        $db = $this->getDb($request);
        $model = new \App\Models\RiskChecklistTemplateModel($db);
        $deleted = $model->delete($id);
        return $this->json($response, ['success' => $deleted]);
    }

    // ==================== CHECKLIST PROGRESS ====================
    public function getChecklistProgress(Request $request, Response $response): Response
    {
        $params = $request->getQueryParams();
        $analisisId = (int)($params['analisis_id'] ?? 0);
        if (!$analisisId) {
            return $this->json($response, ['success' => false, 'error' => 'analisis_id requerido'], 400);
        }
        $db = $this->getDb($request);
        $model = new \App\Models\RiskChecklistProgressModel($db);
        $progress = $model->getProgress($analisisId);
        return $this->json($response, ['success' => true, 'data' => $progress]);
    }

    public function toggleChecklistItem(Request $request, Response $response): Response
    {
        $data = (array) $request->getParsedBody();
        if (!Csrf::validate($data['csrf_token'] ?? '')) {
            return $this->json($response, ['success' => false, 'error' => 'CSRF inválido'], 400);
        }
        $analisisId = (int)($data['analisis_id'] ?? 0);
        $templateId = (int)($data['template_id'] ?? 0);
        $checked = isset($data['checked']) ? (bool)$data['checked'] : false;
        if (!$analisisId || !$templateId) {
            return $this->json($response, ['success' => false, 'error' => 'Faltan datos'], 400);
        }
        $db = $this->getDb($request);
        $model = new \App\Models\RiskChecklistProgressModel($db);
        $ok = $model->setChecked($analisisId, $templateId, $checked);
        return $this->json($response, ['success' => $ok]);
    }

    /// ==================== PRIOR DESDE MATRIZ DE RIESGOS ====================
    public function getPriorFromRisk(Request $request, Response $response, array $args): Response
    {
        $riesgoId = (int)($args['id'] ?? 0);
        if ($riesgoId <= 0) {
            return $this->json($response, ['success' => false, 'error' => 'ID inválido'], 400);
        }
        $db = $this->getDb($request);
        $stmt = $db->prepare("SELECT id, proceso, probabilidad FROM risk_matrix WHERE id = ?");
        $stmt->bind_param('i', $riesgoId);
        $stmt->execute();
        $riesgo = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if (!$riesgo) {
            return $this->json($response, ['success' => false, 'error' => 'Riesgo no encontrado'], 404);
        }
        preg_match('/(\d+)%/', $riesgo['probabilidad'], $matches);
        $priorPorcentaje = $matches[1] ?? 50;
        $prior = $priorPorcentaje / 100;
        return $this->json($response, [
            'success' => true,
            'prior' => round($prior, 4),
            'prior_porcentaje' => (int)$priorPorcentaje,
            'riesgo' => $riesgo
        ]);
    }

    // ==================== BAYES DIRECTO SOBRE RIESGO (checklist) ====================
    public function runBayesAnalysisOnRiesgo(Request $request, Response $response, array $args): Response
    {
        try {
            $riesgoId = (int)($args['id'] ?? 0);
            if ($riesgoId <= 0) {
                return $this->json($response, ['success' => false, 'error' => 'ID de riesgo inválido'], 400);
            }

            $db = $this->getDb($request);

            $stmt = $db->prepare("SELECT id, proceso, descripcion, probabilidad FROM risk_matrix WHERE id = ?");
            $stmt->bind_param('i', $riesgoId);
            $stmt->execute();
            $riesgo = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if (!$riesgo) {
                return $this->json($response, ['success' => false, 'error' => 'Riesgo no encontrado'], 404);
            }

            preg_match('/(\d+)%/', $riesgo['probabilidad'] ?? '50%', $matches);
            $priorPorcentaje = (int)($matches[1] ?? 50);
            $prior = $priorPorcentaje / 100;

            $stmt = $db->prepare("
                SELECT p.template_id, p.checked, t.descripcion, t.peh_riesgo, t.penh_riesgo
                FROM risk_checklist_progress p
                JOIN risk_checklist_templates t ON p.template_id = t.id
                WHERE p.riesgo_id = ? AND p.checked = 1
            ");
            $stmt->bind_param('i', $riesgoId);
            $stmt->execute();
            $result = $stmt->get_result();
            $evidencias = [];
            while ($row = $result->fetch_assoc()) {
                $evidencias[] = $row;
            }
            $stmt->close();

            if (empty($evidencias)) {
                return $this->json($response, [
                    'success' => false,
                    'error' => 'No hay evidencias afirmativas para este riesgo. Marca al menos una evidencia como "Sí".'
                ], 400);
            }

            $posterior = $prior;
            foreach ($evidencias as $e) {
                $peh = max(0.01, min(0.99, (float)$e['peh_riesgo'] / 100));
                $penh = max(0.01, min(0.99, (float)$e['penh_riesgo'] / 100));
                $numerador = $peh * $posterior;
                $denominador = $numerador + $penh * (1 - $posterior);
                $posterior = $numerador / $denominador;
            }

            $posteriorPorcentaje = round($posterior * 100, 2);

            $conclusion = '';
            if ($posteriorPorcentaje >= 70) {
                $conclusion = "⚠️ <strong>ALERTA CRÍTICA</strong>: La probabilidad de materialización del riesgo es muy alta ({$posteriorPorcentaje}%). Se recomienda inspección inmediata y revisión de controles.";
            } elseif ($posteriorPorcentaje >= 50) {
                $conclusion = "🔶 <strong>RIESGO ALTO</strong> ({$posteriorPorcentaje}%). Se requiere inspección reforzada.";
            } elseif ($posteriorPorcentaje >= 30) {
                $conclusion = "🟡 <strong>RIESGO MODERADO</strong> ({$posteriorPorcentaje}%). Se recomienda monitoreo continuo.";
            } else {
                $conclusion = "🟢 <strong>RIESGO BAJO</strong> ({$posteriorPorcentaje}%). Los controles parecen efectivos.";
            }

            $nombreUsuario = $_SESSION['user']['nome'] ?? $_SESSION['user']['email'] ?? 'Usuario';
            $fecha = date('d/m/Y H:i:s');

            return $this->json($response, [
                'success' => true,
                'riesgo' => $riesgo['proceso'] . ' - ' . $riesgo['descripcion'],
                'prior' => $priorPorcentaje,
                'posterior' => $posteriorPorcentaje,
                'usuario' => $nombreUsuario,
                'fecha' => $fecha,
                'resultado' => $conclusion
            ]);
        } catch (\Throwable $e) {
            error_log('Error en runBayesAnalysisOnRiesgo: ' . $e->getMessage());
            return $this->json($response, ['success' => false, 'error' => 'Error interno: ' . $e->getMessage()], 500);
        }
    }

    // ==================== CHECKLIST POR RIESGO (para checklist simple) ====================

    public function getChecklistByRiesgo(Request $request, Response $response, array $args): Response
    {
        $riesgoId = (int)($args['id'] ?? 0);
        $nombre = $request->getQueryParams()['nombre_checklist'] ?? null;
        if ($riesgoId <= 0) {
            return $this->json($response, ['success' => false, 'error' => 'ID inválido'], 400);
        }
        $db = $this->getDb($request);
        if ($nombre) {
            $stmt = $db->prepare("SELECT template_id, checked FROM risk_checklist_progress WHERE riesgo_id = ? AND nombre_checklist = ?");
            $stmt->bind_param('is', $riesgoId, $nombre);
        } else {
            $stmt = $db->prepare("SELECT template_id, checked FROM risk_checklist_progress WHERE riesgo_id = ?");
            $stmt->bind_param('i', $riesgoId);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = ['id' => (int)$row['template_id'], 'checked' => (bool)$row['checked']];
        }
        $stmt->close();
        return $this->json($response, ['success' => true, 'data' => $data]);
    }

    // ==================== TOGGLE CHECKLIST ITEM BY RIESGO (CORREGIDO) ====================
public function toggleChecklistItemByRiesgo(Request $request, Response $response): Response
{
    try {
        $data = (array) $request->getParsedBody();
        
        $riesgoId = (int)($data['riesgo_id'] ?? 0);
        $templateId = (int)($data['template_id'] ?? 0);
        $checked = isset($data['checked']) ? (int)(bool)$data['checked'] : 0;
        $nombre = $data['nombre_checklist'] ?? '';
        $area = $data['area'] ?? '';
        $usuarioId = $_SESSION['user']['id'] ?? null;
        $organizacionId = isset($data['organizacion_id']) && !empty($data['organizacion_id']) ? (int)$data['organizacion_id'] : null;
        $organizacionNombre = isset($data['organizacion_nombre']) && !empty($data['organizacion_nombre']) ? trim($data['organizacion_nombre']) : null;

        // ========== NUEVO: BUSCAR ORGANIZACIÓN AUTOMÁTICAMENTE ==========
        if (!$organizacionId && !$organizacionNombre) {
            $db = $this->getDb($request);
            
            // Buscar organización por nombre del checklist
            $sqlBuscar = "SELECT id, nombre FROM risk_organization WHERE ? LIKE CONCAT('%', nombre, '%') LIMIT 1";
            $stmt = $db->prepare($sqlBuscar);
            $stmt->bind_param("s", $nombre);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($row = $result->fetch_assoc()) {
                $organizacionId = $row['id'];
                $organizacionNombre = $row['nombre'];
            }
        }
        // ==============================================================

        if (!$riesgoId || !$templateId) {
            return $this->json($response, ['success' => false, 'error' => 'Faltan datos: riesgo_id y template_id requeridos'], 400);
        }

        $db = $this->getDb($request);
        
        // Verificar conexión
        if (!$db->ping()) {
            return $this->json($response, ['success' => false, 'error' => 'Error de conexión a la base de datos'], 500);
        }
        
        // Escapar valores para seguridad
        $nombreEscaped = $db->real_escape_string($nombre);
        $areaEscaped = $db->real_escape_string($area);
        $orgNombreEscaped = $db->real_escape_string($organizacionNombre ?? '');
        
        // Preparar valores NULL correctamente
        $usuarioValue = $usuarioId ?: 'NULL';
        $orgIdValue = $organizacionId ?: 'NULL';
        $orgNombreValue = $organizacionNombre ? "'$orgNombreEscaped'" : 'NULL';
        
        // Consulta SQL
        $sql = "
            INSERT INTO risk_checklist_progress 
            (riesgo_id, template_id, checked, updated_at, nombre_checklist, area, usuario_id, organizacion_id, organizacion_nombre) 
            VALUES ($riesgoId, $templateId, $checked, NOW(), '$nombreEscaped', '$areaEscaped', $usuarioValue, $orgIdValue, $orgNombreValue)
            ON DUPLICATE KEY UPDATE 
            checked = VALUES(checked),
            updated_at = NOW(),
            area = VALUES(area),
            usuario_id = VALUES(usuario_id),
            organizacion_id = VALUES(organizacion_id),
            organizacion_nombre = VALUES(organizacion_nombre)
        ";
        
        if (!$db->query($sql)) {
            error_log("Error SQL toggleChecklistItemByRiesgo: " . $db->error);
            return $this->json($response, ['success' => false, 'error' => 'Error al guardar: ' . $db->error], 500);
        }
        
        return $this->json($response, ['success' => true]);
        
    } catch (Exception $e) {
        error_log("EXCEPCIÓN toggleChecklistItemByRiesgo: " . $e->getMessage());
        return $this->json($response, ['success' => false, 'error' => $e->getMessage()], 500);
    }
}
    // ==================== DASHBOARD DE CHECKLISTS ====================
  public function getDashboardChecklists(Request $request, Response $response): Response
{
    try {
        $db = $this->getDb($request);
        
        $sql = "SELECT 
                    p.riesgo_id,
                    p.nombre_checklist,
                    p.area,
                    p.organizacion_id,
                    p.organizacion_nombre,
                    COUNT(p.template_id) as total_evidencias,
                    SUM(p.checked) as afirmativas,
                    MAX(p.updated_at) as updated_at
                FROM risk_checklist_progress p
                WHERE p.nombre_checklist IS NOT NULL AND p.nombre_checklist != ''
                GROUP BY p.riesgo_id, p.nombre_checklist, p.area, p.organizacion_id, p.organizacion_nombre
                ORDER BY updated_at DESC";
        
        $result = $db->query($sql);
        $data = [];
        
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        
        return $this->json($response, ['success' => true, 'data' => $data]);
    } catch (\Exception $e) {
        error_log("ERROR getDashboardChecklists: " . $e->getMessage());
        return $this->json($response, ['success' => false, 'error' => $e->getMessage()], 500);
    }
}


// ==================== ELIMINAR CHECKLIST ====================
public function deleteChecklist(Request $request, Response $response): Response
{
    try {
        $data = (array) $request->getParsedBody();
        
        if (!Csrf::validate($data['csrf_token'] ?? '')) {
            return $this->json($response, ['success' => false, 'error' => 'CSRF inválido'], 400);
        }
        
        $nombreChecklist = $data['nombre_checklist'] ?? '';
        $riesgoId = (int)($data['riesgo_id'] ?? 0);
        
        if (!$nombreChecklist || !$riesgoId) {
            return $this->json($response, ['success' => false, 'error' => 'Faltan datos'], 400);
        }
        
        $db = $this->getDb($request);
        
        // Eliminar todos los registros de este checklist
        $stmt = $db->prepare("DELETE FROM risk_checklist_progress WHERE nombre_checklist = ? AND riesgo_id = ?");
        $stmt->bind_param('si', $nombreChecklist, $riesgoId);
        $stmt->execute();
        $deleted = $stmt->affected_rows;
        $stmt->close();
        
        if ($deleted > 0) {
            return $this->json($response, ['success' => true, 'deleted' => $deleted]);
        } else {
            return $this->json($response, ['success' => false, 'error' => 'No se encontró el checklist para eliminar'], 404);
        }
        
    } catch (\Exception $e) {
        error_log("ERROR deleteChecklist: " . $e->getMessage());
        return $this->json($response, ['success' => false, 'error' => $e->getMessage()], 500);
    }
} 


    // ==================== ARCHIVOS DE REPORTES ====================

    /**
     * Subir archivo adjunto a un reporte
     */
    public function uploadReportFile(Request $request, Response $response): Response
    {
        try {
            $params = $request->getQueryParams();
            $reportId = (int)($params['report_id'] ?? 0);
            
            if (!$reportId) {
                return $this->json($response, ['success' => false, 'error' => 'ID de reporte requerido'], 400);
            }
            
            // Verificar CSRF (desde header o desde POST)
            $csrfToken = $request->getHeaderLine('X-CSRF-Token') ?: ($_POST['csrf_token'] ?? '');
            if (!Csrf::validate($csrfToken)) {
                return $this->json($response, ['success' => false, 'error' => 'CSRF inválido'], 400);
            }
            
            // Verificar que el reporte existe
            $db = $this->getDb($request);
            $model = new \App\Models\RiskReportModel($db);
            $report = $model->getById($reportId);
            
            if (!$report) {
                return $this->json($response, ['success' => false, 'error' => 'Reporte no encontrado'], 404);
            }
            
            // Procesar archivo subido
            $uploadedFiles = $request->getUploadedFiles();
            if (empty($uploadedFiles['file'])) {
                return $this->json($response, ['success' => false, 'error' => 'No se recibió ningún archivo'], 400);
            }
            
            $file = $uploadedFiles['file'];
            if ($file->getError() !== UPLOAD_ERR_OK) {
                return $this->json($response, ['success' => false, 'error' => 'Error al subir el archivo'], 400);
            }
            
            $originalName = $file->getClientFilename();
            $fileSize = $file->getSize();
            $fileType = $file->getClientMediaType();
            
            // Validar tamaño (10 MB máximo)
            if ($fileSize > 10 * 1024 * 1024) {
                return $this->json($response, ['success' => false, 'error' => 'El archivo excede el tamaño máximo de 10 MB'], 400);
            }
            
            // Validar extensión
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            $allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'gif', 'txt', 'zip', 'rar'];
            if (!in_array($extension, $allowedExtensions)) {
                return $this->json($response, ['success' => false, 'error' => 'Formato no permitido'], 400);
            }
            
            // Generar nombre seguro
            $safeName = time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
            
            // Crear carpeta por reporte
            $uploadDir = \App\Models\RiskReportFileModel::getFilesBasePath() . 'report_' . $reportId . '/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            // Mover archivo
            $destination = $uploadDir . $safeName;
            $file->moveTo($destination);
            
            // Guardar en BD
            $fileModel = new \App\Models\RiskReportFileModel($db);
            $usuarioId = $_SESSION['user']['id'] ?? null;
            $fileId = $fileModel->create($reportId, $safeName, $originalName, $fileSize, $fileType, $usuarioId);
            
            if ($fileId) {
                return $this->json($response, [
                    'success' => true, 
                    'file' => [
                        'id' => $fileId,
                        'filename' => $safeName,
                        'original_name' => $originalName,
                        'size' => $fileSize,
                        'type' => $fileType,
                        'url' => "/modules/report_files/uploads/report_{$reportId}/{$safeName}"
                    ]
                ]);
            } else {
                // Limpiar archivo físico si falló la BD
                if (file_exists($destination)) unlink($destination);
                return $this->json($response, ['success' => false, 'error' => 'Error al guardar en base de datos'], 500);
            }
            
        } catch (\Exception $e) {
            error_log('Error uploadReportFile: ' . $e->getMessage());
            return $this->json($response, ['success' => false, 'error' => 'Error interno del servidor'], 500);
        }
    }

    /**
     * Obtener archivos de un reporte
     */
    public function getReportFiles(Request $request, Response $response, array $args): Response
    {
        $reportId = (int)($args['id'] ?? 0);
        if (!$reportId) {
            return $this->json($response, ['success' => false, 'error' => 'ID de reporte requerido'], 400);
        }
        
        $db = $this->getDb($request);
        $model = new \App\Models\RiskReportFileModel($db);
        $files = $model->getByReportId($reportId);
        
        // Agregar URLs completas
        foreach ($files as &$file) {
            $file['url'] = "/modules/report_files/uploads/report_{$reportId}/{$file['filename']}";
            $file['download_url'] = "/api/risk/reports/{$reportId}/files/{$file['id']}/download";
        }
        
        return $this->json($response, ['success' => true, 'data' => $files]);
    }

    /**
     * Descargar archivo adjunto
     */
    public function downloadReportFile(Request $request, Response $response, array $args): Response
    {
        $reportId = (int)($args['reportId'] ?? 0);
        $fileId = (int)($args['fileId'] ?? 0);
        
        if (!$reportId || !$fileId) {
            return $this->json($response, ['success' => false, 'error' => 'Parámetros inválidos'], 400);
        }
        
        $db = $this->getDb($request);
        $model = new \App\Models\RiskReportFileModel($db);
        $file = $model->getById($fileId);
        
        if (!$file || $file['report_id'] != $reportId) {
            return $this->json($response, ['success' => false, 'error' => 'Archivo no encontrado'], 404);
        }
        
        $filePath = \App\Models\RiskReportFileModel::getFilesBasePath() . "report_{$reportId}/{$file['filename']}";
        if (!file_exists($filePath)) {
            return $this->json($response, ['success' => false, 'error' => 'El archivo físico no existe'], 404);
        }
        
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $filePath);
        finfo_close($finfo);
        
        $response = $response->withHeader('Content-Type', $mimeType)
                             ->withHeader('Content-Disposition', 'attachment; filename="' . $file['original_name'] . '"')
                             ->withHeader('Content-Length', (string)filesize($filePath));
        
        $response->getBody()->write(file_get_contents($filePath));
        return $response;
    }

    /**
     * Eliminar archivo adjunto
     */
    public function deleteReportFile(Request $request, Response $response, array $args): Response
    {
        $fileId = (int)($args['id'] ?? 0);
        if (!$fileId) {
            return $this->json($response, ['success' => false, 'error' => 'ID inválido'], 400);
        }
        
        $data = (array) $request->getParsedBody();
        if (!Csrf::validate($data['csrf_token'] ?? '')) {
            return $this->json($response, ['success' => false, 'error' => 'CSRF inválido'], 400);
        }
        
        $db = $this->getDb($request);
        $model = new \App\Models\RiskReportFileModel($db);
        $file = $model->getById($fileId);
        
        if (!$file) {
            return $this->json($response, ['success' => false, 'error' => 'Archivo no encontrado'], 404);
        }
        
        // Eliminar archivo físico
        $filePath = \App\Models\RiskReportFileModel::getFilesBasePath() . "report_{$file['report_id']}/{$file['filename']}";
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        
        $deleted = $model->delete($fileId);
        return $this->json($response, ['success' => $deleted]);
    }

}
