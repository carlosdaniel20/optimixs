<?php
declare(strict_types=1);

namespace App\Models;

use mysqli;

class RiskChecklistTemplateModel
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    public function getAll(): array
    {
        $result = $this->db->query("SELECT * FROM risk_checklist_templates ORDER BY area, orden, id");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM risk_checklist_templates WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
        return $data ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO risk_checklist_templates (area, descripcion, es_critica, peh_riesgo, penh_riesgo, orden) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssiiii', $data['area'], $data['descripcion'], $data['es_critica'], $data['peh_riesgo'], $data['penh_riesgo'], $data['orden']);
        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        return $id;
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("UPDATE risk_checklist_templates SET area=?, descripcion=?, es_critica=?, peh_riesgo=?, penh_riesgo=?, orden=? WHERE id=?");
        $stmt->bind_param('ssiiiii', $data['area'], $data['descripcion'], $data['es_critica'], $data['peh_riesgo'], $data['penh_riesgo'], $data['orden'], $id);
        $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();
        return $affected > 0;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM risk_checklist_templates WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();
        return $affected > 0;
    }
}
