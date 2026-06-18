<?php

namespace App\Models;

use App\Security\Crypto;

class ChatMessage
{
    private $db;
    private $uploadDir;
    private $uploadUrl;
    
    public function __construct($db)
    {
        $this->db = $db;
        $this->uploadDir = '/var/www/optimixs/storage/chat_uploads/';
        $this->uploadUrl = '/storage/chat_uploads/';
        
        if (!file_exists($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }
    
    /**
     * Enviar un mensaje con o sin archivo (CON ENCRIPTACIÓN)
     */
    public function send($conversationId, $senderId, $receiverId, $message, $fileData = null)
    {
        $filePath = null;
        $fileName = null;
        $fileType = null;
        $fileSize = null;
        
        // ENCRIPTAR el mensaje antes de guardar (si hay mensaje)
        $encryptedMessage = null;
        if (!empty($message)) {
            try {
                $encryptedMessage = Crypto::encrypt($message);
            } catch (\Exception $e) {
                error_log("Error encrypting message: " . $e->getMessage());
                $encryptedMessage = $message; // Fallback a texto plano si falla encriptación
            }
        }
        
        if ($fileData && isset($fileData['tmp_name']) && $fileData['error'] === UPLOAD_ERR_OK) {
            $fileName = basename($fileData['name']);
            $fileType = $fileData['type'];
            $fileSize = $fileData['size'];
            
            // Validar tamaño máximo (30MB)
            if ($fileSize > 30 * 1024 * 1024) {
                return ['error' => 'El archivo no puede superar los 30MB'];
            }
            
            // Validar extensiones permitidas
            $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'zip'];
            $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            if (!in_array($ext, $allowedExts)) {
                return ['error' => 'Tipo de archivo no permitido'];
            }
            
            // Generar nombre único
            $uniqueName = uniqid() . '_' . time() . '.' . $ext;
            $filePath = 'chat_uploads/' . $uniqueName;
            $fullPath = $this->uploadDir . $uniqueName;
            
            if (!move_uploaded_file($fileData['tmp_name'], $fullPath)) {
                return ['error' => 'Error al subir el archivo'];
            }
        }
        
        // Guardar mensaje ENCRIPTADO en la base de datos
        $stmt = $this->db->prepare("
            INSERT INTO chat_messages (conversation_id, sender_id, receiver_id, message, file_path, file_name, file_type, file_size, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->bind_param('iiissssi', $conversationId, $senderId, $receiverId, $encryptedMessage, $filePath, $fileName, $fileType, $fileSize);
        
        if (!$stmt->execute()) {
            return ['error' => 'Error al guardar el mensaje: ' . $this->db->error];
        }
        
        $messageId = $stmt->insert_id;
        
        // Actualizar última actualización de la conversación (el mensaje visible en la lista)
        $displayMessage = $message ?: ($fileType && strpos($fileType, 'image/') === 0 ? '📷 Imagen' : '📎 Archivo adjunto');
        $this->updateConversationLastMessage($conversationId, $displayMessage);
        
        return ['success' => true, 'message_id' => $messageId];
    }
    
    private function updateConversationLastMessage($conversationId, $message)
    {
        $stmt = $this->db->prepare("
            UPDATE chat_conversations SET last_message = ?, last_message_at = NOW() WHERE id = ?
        ");
        $stmt->bind_param('si', $message, $conversationId);
        return $stmt->execute();
    }
    
    /**
     * Obtener mensajes de una conversación (CON DESENCRIPTACIÓN)
     */
    public function getMessages($conversationId, $limit = 50, $offset = 0)
    {
        $stmt = $this->db->prepare("
            SELECT m.*, u.nome as sender_name, u.email as sender_email, u.tipo_utente as sender_role
            FROM chat_messages m
            LEFT JOIN utenti u ON m.sender_id = u.id
            WHERE m.conversation_id = ?
            ORDER BY m.created_at ASC
            LIMIT ? OFFSET ?
        ");
        $stmt->bind_param('iii', $conversationId, $limit, $offset);
        $stmt->execute();
        
        $messages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        // DESENCRIPTAR mensajes y añadir URL para archivos
        foreach ($messages as &$msg) {
            // Desencriptar el mensaje si existe
            if (!empty($msg['message'])) {
                try {
                    $decrypted = Crypto::decrypt($msg['message']);
                    // Si la desencriptación fue exitosa y no devolvió el mensaje de error
                    if ($decrypted !== '[Mensaje encriptado no disponible]') {
                        $msg['message'] = $decrypted;
                    }
                } catch (\Exception $e) {
                    error_log("Error decrypting message {$msg['id']}: " . $e->getMessage());
                    // Si falla, mantener el mensaje original (podría ser texto plano de antes)
                }
            }
            
            // Añadir URL para archivos
            if ($msg['file_path']) {
                $msg['file_url'] = $this->uploadUrl . basename($msg['file_path']);
                $msg['is_image'] = strpos($msg['file_type'] ?? '', 'image/') === 0;
            }
        }
        
        return $messages;
    }
    
    /**
     * Obtener nuevos mensajes desde un ID específico (para polling) - CON DESENCRIPTACIÓN
     */
    public function getNewMessages($conversationId, $lastId)
    {
        $stmt = $this->db->prepare("
            SELECT m.*, u.nome as sender_name 
            FROM chat_messages m
            LEFT JOIN utenti u ON m.sender_id = u.id
            WHERE m.conversation_id = ? AND m.id > ?
            ORDER BY m.created_at ASC
        ");
        $stmt->bind_param('ii', $conversationId, $lastId);
        $stmt->execute();
        $messages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        // DESENCRIPTAR nuevos mensajes
        foreach ($messages as &$msg) {
            if (!empty($msg['message'])) {
                try {
                    $decrypted = Crypto::decrypt($msg['message']);
                    if ($decrypted !== '[Mensaje encriptado no disponible]') {
                        $msg['message'] = $decrypted;
                    }
                } catch (\Exception $e) {
                    error_log("Error decrypting new message {$msg['id']}: " . $e->getMessage());
                }
            }
            if ($msg['file_path']) {
                $msg['is_image'] = strpos($msg['file_type'] ?? '', 'image/') === 0;
            }
        }
        
        return $messages;
    }
    
    /**
     * Marcar mensajes como leídos
     */
    public function markAsRead($conversationId, $userId)
    {
        $stmt = $this->db->prepare("
            UPDATE chat_messages SET is_read = 1 
            WHERE conversation_id = ? AND receiver_id = ? AND is_read = 0
        ");
        $stmt->bind_param('ii', $conversationId, $userId);
        return $stmt->execute();
    }
    
    /**
     * Contar mensajes no leídos
     */
    public function countUnread($userId)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count FROM chat_messages 
            WHERE receiver_id = ? AND is_read = 0
        ");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        
        return $result['count'] ?? 0;
    }
    
    /**
     * Obtener últimos mensajes para notificaciones (últimos 5 segundos) - CON DESENCRIPTACIÓN
     */
    public function getRecentMessages($conversationId, $since = null)
    {
        $since = $since ?: date('Y-m-d H:i:s', strtotime('-5 seconds'));
        
        $stmt = $this->db->prepare("
            SELECT m.*, u.nome as sender_name 
            FROM chat_messages m
            LEFT JOIN utenti u ON m.sender_id = u.id
            WHERE m.conversation_id = ? AND m.created_at > ?
            ORDER BY m.created_at ASC
        ");
        $stmt->bind_param('is', $conversationId, $since);
        $stmt->execute();
        
        $messages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        // DESENCRIPTAR mensajes recientes
        foreach ($messages as &$msg) {
            if (!empty($msg['message'])) {
                try {
                    $decrypted = Crypto::decrypt($msg['message']);
                    if ($decrypted !== '[Mensaje encriptado no disponible]') {
                        $msg['message'] = $decrypted;
                    }
                } catch (\Exception $e) {
                    // Si falla, mantener como está
                }
            }
        }
        
        return $messages;
    }
}
