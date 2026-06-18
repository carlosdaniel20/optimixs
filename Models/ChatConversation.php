<?php

namespace App\Models;

class ChatConversation
{
    private $db;
    
    public function __construct($db)
    {
        $this->db = $db;
    }
    
    /**
     * Obtener o crear una conversación entre dos usuarios
     */
    public function getOrCreate($user1Id, $user2Id)
    {
        $stmt = $this->db->prepare("
            SELECT id FROM chat_conversations 
            WHERE (user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?)
        ");
        $stmt->bind_param('iiii', $user1Id, $user2Id, $user2Id, $user1Id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            return $row['id'];
        }
        
        // Crear nueva conversación
        $stmt = $this->db->prepare("INSERT INTO chat_conversations (user1_id, user2_id) VALUES (?, ?)");
        $stmt->bind_param('ii', $user1Id, $user2Id);
        $stmt->execute();
        
        return $this->db->insert_id;
    }
    
    /**
     * Obtener todas las conversaciones de un usuario
     */
    public function getUserConversations($userId)
    {
        $stmt = $this->db->prepare("
            SELECT c.*, 
                   u1.nome as user1_name, u1.email as user1_email, u1.tipo_utente as user1_role,
                   u2.nome as user2_name, u2.email as user2_email, u2.tipo_utente as user2_role,
                   (SELECT COUNT(*) FROM chat_messages WHERE conversation_id = c.id AND receiver_id = ? AND is_read = 0) as unread_count
            FROM chat_conversations c
            LEFT JOIN utenti u1 ON c.user1_id = u1.id
            LEFT JOIN utenti u2 ON c.user2_id = u2.id
            WHERE c.user1_id = ? OR c.user2_id = ?
            ORDER BY c.last_message_at DESC
        ");
        $stmt->bind_param('iii', $userId, $userId, $userId);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    /**
     * Obtener el otro usuario de la conversación
     */
    public function getOtherUser($conversationId, $currentUserId)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM chat_conversations WHERE id = ?
        ");
        $stmt->bind_param('i', $conversationId);
        $stmt->execute();
        $conv = $stmt->get_result()->fetch_assoc();
        
        if (!$conv) return null;
        
        $otherId = ($conv['user1_id'] == $currentUserId) ? $conv['user2_id'] : $conv['user1_id'];
        
        $stmt = $this->db->prepare("
            SELECT id, nome, email, tipo_utente FROM utenti WHERE id = ?
        ");
        $stmt->bind_param('i', $otherId);
        $stmt->execute();
        
        return $stmt->get_result()->fetch_assoc();
    }
    
    /**
     * Actualizar última actualización de conversación
     */
    public function updateLastMessage($conversationId, $message)
    {
        $stmt = $this->db->prepare("
            UPDATE chat_conversations SET last_message = ?, last_message_at = NOW() WHERE id = ?
        ");
        $stmt->bind_param('si', $message, $conversationId);
        return $stmt->execute();
    }
}
