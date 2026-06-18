<?php
declare(strict_types=1);

namespace App\Models;

use mysqli;

class RiskReportFileModel
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    /**
     * Obtener la ruta base de almacenamiento de archivos
     */
    public static function getFilesBasePath(): string
    {
        // Usar una ruta dentro de storage donde tu usuario tenga permisos
        return dirname(__DIR__, 2) . '/storage/report_files/';
    } 

    /**
     * Crear un nuevo archivo asociado a un reporte
     */
    public function create(int $reportId, string $filename, string $originalName, int $fileSize, ?string $fileType = null, ?int $uploadedBy = null): ?int
    {
        $stmt = $this->db->prepare("
            INSERT INTO risk_report_files (report_id, filename, original_name, file_size, file_type, uploaded_by, uploaded_at) 
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->bind_param('issiis', $reportId, $filename, $originalName, $fileSize, $fileType, $uploadedBy);
        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        return $id > 0 ? $id : null;
    }

    /**
     * Obtener todos los archivos de un reporte
     * MODIFICADO: Normaliza los nombres de campos para el frontend
     */
    public function getByReportId(int $reportId): array
    {
        $stmt = $this->db->prepare("
            SELECT f.*, u.nome as uploaded_by_nombre, u.email as uploaded_by_email
            FROM risk_report_files f
            LEFT JOIN utenti u ON f.uploaded_by = u.id
            WHERE f.report_id = ?
            ORDER BY f.uploaded_at DESC
        ");
        $stmt->bind_param('i', $reportId);
        $stmt->execute();
        $result = $stmt->get_result();
        $files = [];
        
        while ($row = $result->fetch_assoc()) {
            // Normalizar nombres de campos para el frontend
            $files[] = [
                'id' => $row['id'],
                'report_id' => $row['report_id'],
                'filename' => $row['filename'],
                'nombre' => $row['filename'],                    // ← para frontend
                'nombre_original' => $row['original_name'],     // ← para frontend
                'original_name' => $row['original_name'],
                'size' => (int)$row['file_size'],
                'tamaño' => (int)$row['file_size'],             // ← para frontend
                'type' => $row['file_type'],
                'tipo' => $row['file_type'],                    // ← para frontend
                'uploaded_by' => $row['uploaded_by'],
                'uploaded_by_nombre' => $row['uploaded_by_nombre'],
                'uploaded_by_email' => $row['uploaded_by_email'],
                'uploaded_at' => $row['uploaded_at'],
                'url' => "/storage/report_files/report_{$row['report_id']}/{$row['filename']}",
                'download_url' => "/api/risk/reports/{$row['report_id']}/files/{$row['id']}/download"
            ];
        }
        $stmt->close();
        return $files;
    }

    /**
     * Obtener un archivo por ID
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM risk_report_files WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $file = $result->fetch_assoc();
        $stmt->close();
        return $file ?: null;
    }

    /**
     * Eliminar un archivo por ID
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM risk_report_files WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();
        return $affected > 0;
    }

    /**
     * Eliminar todos los archivos de un reporte
     */
    public function deleteByReportId(int $reportId): int
    {
        $stmt = $this->db->prepare("DELETE FROM risk_report_files WHERE report_id = ?");
        $stmt->bind_param('i', $reportId);
        $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();
        return $affected;
    }
}
