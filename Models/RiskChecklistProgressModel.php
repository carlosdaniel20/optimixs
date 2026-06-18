<?php
declare(strict_types=1);

namespace App\Models;

use mysqli;

class RiskChecklistProgressModel
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    public function getProgress(int $analisisId): array
    {
        $stmt = $this->db->prepare("SELECT template_id, checked FROM risk_checklist_progress WHERE analisis_id = ?");
        $stmt->bind_param('i', $analisisId);
        $stmt->execute();
        $result = $stmt->get_result();
        $progress = [];
        while ($row = $result->fetch_assoc()) {
            $progress[$row['template_id']] = (bool)$row['checked'];
        }
        $stmt->close();
        return $progress;
    }

    public function setChecked(int $analisisId, int $templateId, bool $checked): bool
    {
        $stmt = $this->db->prepare("INSERT INTO risk_checklist_progress (analisis_id, template_id, checked) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE checked = VALUES(checked), updated_at = CURRENT_TIMESTAMP");
        $stmt->bind_param('iii', $analisisId, $templateId, $checked);
        $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();
        return $affected >= 0;
    }

    public function deleteByAnalisis(int $analisisId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM risk_checklist_progress WHERE analisis_id = ?");
        $stmt->bind_param('i', $analisisId);
        $stmt->execute();
        $stmt->close();
        return true;
    }
}
