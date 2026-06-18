<?php
declare(strict_types=1);

namespace App\Models;

use mysqli;

class RiskAnalisisModel
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    public function getAll(): array
    {
        $result = $this->db->query("
            SELECT a.*, r.proceso as riesgo_proceso, r.probabilidad as riesgo_probabilidad,
                   u.nombre as created_by_name
            FROM risk_analisis a
            LEFT JOIN risk_matrix r ON a.riesgo_id = r.id
            LEFT JOIN utenti u ON a.created_by = u.id
            ORDER BY a.created_at DESC
        ");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT a.*, r.proceso as riesgo_proceso, r.probabilidad as riesgo_probabilidad,
                   u.nombre as created_by_name
            FROM risk_analisis a
            LEFT JOIN risk_matrix r ON a.riesgo_id = r.id
            LEFT JOIN utenti u ON a.created_by = u.id
            WHERE a.id = ?
        ");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
        return $data ?: null;
    }

    public function create(int $riesgoId, string $nombre, ?int $userId = null): int
    {
        $stmt = $this->db->prepare("INSERT INTO risk_analisis (riesgo_id, nombre, created_by) VALUES (?, ?, ?)");
        $stmt->bind_param('isi', $riesgoId, $nombre, $userId);
        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        return $id;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM risk_analisis WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();
        return $affected > 0;
    }
}
