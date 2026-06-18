<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\ChatTyping;

class ChatController
{
    private function getDb(Request $request)
    {
        $container = $GLOBALS['container'] ?? $request->getAttribute('container');
        if ($container && $container->has('db')) {
            return $container->get('db');
        }
        $dbConfig = require dirname(__DIR__, 2) . '/config/database.php';
        return new \mysqli($dbConfig['host'], $dbConfig['user'], $dbConfig['password'], $dbConfig['dbname']);
    }
    
    private function json(Response $response, array $data, int $status = 200): Response
    {
        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json')->withStatus($status);
    }
    
    /**
     * Vista principal del chat
     */
    public function index(Request $request, Response $response): Response
    {
        if (empty($_SESSION['user'])) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }
        
        ob_start();
        require dirname(__DIR__, 2) . '/app/Views/chat/index.php';
        $html = ob_get_clean();
        $response->getBody()->write($html);
        return $response;
    }
    
    /**
     * Obtener lista de conversaciones del usuario
     */
    public function getConversations(Request $request, Response $response): Response
    {
        $userId = $_SESSION['user']['id'] ?? 0;
        if (!$userId) {
            return $this->json($response, ['error' => 'No autenticado'], 401);
        }
        
        $db = $this->getDb($request);
        $convModel = new ChatConversation($db);
        $conversations = $convModel->getUserConversations($userId);
        
        // Formatear para la vista
        foreach ($conversations as &$conv) {
            $isUser1 = $conv['user1_id'] == $userId;
            $conv['other_user_id'] = $isUser1 ? $conv['user2_id'] : $conv['user1_id'];
            $conv['other_user_name'] = $isUser1 ? $conv['user2_name'] : $conv['user1_name'];
            $conv['other_user_role'] = $isUser1 ? $conv['user2_role'] : $conv['user1_role'];
            $conv['last_message'] = $conv['last_message'] ?? 'Sin mensajes';
            $conv['unread_count'] = (int)($conv['unread_count'] ?? 0);
        }
        
        return $this->json($response, ['success' => true, 'data' => $conversations]);
    }
    
    /**
     * Obtener mensajes de una conversación
     */
    public function getMessages(Request $request, Response $response, array $args): Response
    {
        $userId = $_SESSION['user']['id'] ?? 0;
        if (!$userId) {
            return $this->json($response, ['error' => 'No autenticado'], 401);
        }
        
        $conversationId = (int)($args['id'] ?? 0);
        if (!$conversationId) {
            return $this->json($response, ['error' => 'ID de conversación inválido'], 400);
        }
        
        $db = $this->getDb($request);
        $messageModel = new ChatMessage($db);
        
        // Marcar mensajes como leídos
        $messageModel->markAsRead($conversationId, $userId);
        
        $messages = $messageModel->getMessages($conversationId);
        
        return $this->json($response, ['success' => true, 'data' => $messages]);
    }
    
    /**
     * Enviar un mensaje (con o sin archivo)
     */
    public function sendMessage(Request $request, Response $response): Response
    {
        $userId = $_SESSION['user']['id'] ?? 0;
        if (!$userId) {
            return $this->json($response, ['error' => 'No autenticado'], 401);
        }
        
        $data = (array) $request->getParsedBody();
        $receiverId = (int)($data['receiver_id'] ?? 0);
        $message = trim($data['message'] ?? '');
        
        if (!$receiverId) {
            return $this->json($response, ['error' => 'Destinatario inválido'], 400);
        }
        
        // Procesar archivo subido
        $uploadedFiles = $request->getUploadedFiles();
        $fileData = null;
        
        if (!empty($uploadedFiles['file'])) {
            $file = $uploadedFiles['file'];
            if ($file->getError() === UPLOAD_ERR_OK) {
                $fileData = [
                    'tmp_name' => $file->getStream()->getMetadata('uri'),
                    'name' => $file->getClientFilename(),
                    'type' => $file->getClientMediaType(),
                    'size' => $file->getSize(),
                    'error' => UPLOAD_ERR_OK
                ];
            }
        }
        
        $db = $this->getDb($request);
        $convModel = new ChatConversation($db);
        $messageModel = new ChatMessage($db);
        
        // Obtener o crear conversación
        $conversationId = $convModel->getOrCreate($userId, $receiverId);
        
        // Enviar mensaje
        $result = $messageModel->send($conversationId, $userId, $receiverId, $message, $fileData);
        
        if (isset($result['error'])) {
            return $this->json($response, ['error' => $result['error']], 400);
        }
        
        return $this->json($response, [
            'success' => true,
            'message_id' => $result['message_id'],
            'conversation_id' => $conversationId
        ]);
    }
    
    /**
     * Obtener usuarios para iniciar conversación
     */
    public function getUsers(Request $request, Response $response): Response
    {
        $currentUserId = $_SESSION['user']['id'] ?? 0;
        $userRole = $_SESSION['user']['tipo_utente'] ?? '';
        
        $db = $this->getDb($request);
        
        if ($userRole === 'admin' || $userRole === 'staff') {
            $stmt = $db->prepare("SELECT id, nome, email, tipo_utente FROM utenti WHERE id != ? AND stato = 'attivo' ORDER BY nome");
            $stmt->bind_param('i', $currentUserId);
        } else {
            $stmt = $db->prepare("SELECT id, nome, email, tipo_utente FROM utenti WHERE tipo_utente IN ('admin', 'staff') AND stato = 'attivo' AND id != ? ORDER BY nome");
            $stmt->bind_param('i', $currentUserId);
        }
        $stmt->execute();
        $users = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        return $this->json($response, ['success' => true, 'data' => $users]);
    }
    
    /**
     * Contar mensajes no leídos
     */
    public function getUnreadCount(Request $request, Response $response): Response
    {
        $userId = $_SESSION['user']['id'] ?? 0;
        if (!$userId) {
            return $this->json($response, ['error' => 'No autenticado'], 401);
        }
        
        $db = $this->getDb($request);
        $messageModel = new ChatMessage($db);
        $count = $messageModel->countUnread($userId);
        
        return $this->json($response, ['success' => true, 'unread' => $count]);
    }
    
    /**
     * Obtener nuevos mensajes (para polling)
     */
    public function getNewMessages(Request $request, Response $response): Response
    {
        $userId = $_SESSION['user']['id'] ?? 0;
        if (!$userId) {
            return $this->json($response, ['error' => 'No autenticado'], 401);
        }
        
        $conversationId = (int)($request->getQueryParams()['conversation_id'] ?? 0);
        $lastMessageId = (int)($request->getQueryParams()['last_id'] ?? 0);
        
        $db = $this->getDb($request);
        
        $stmt = $db->prepare("
            SELECT m.*, u.nome as sender_name 
            FROM chat_messages m
            LEFT JOIN utenti u ON m.sender_id = u.id
            WHERE m.conversation_id = ? AND m.id > ? AND m.sender_id != ?
            ORDER BY m.created_at ASC
        ");
        $stmt->bind_param('iii', $conversationId, $lastMessageId, $userId);
        $stmt->execute();
        $messages = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        foreach ($messages as &$msg) {
            if ($msg['file_path']) {
                $msg['is_image'] = strpos($msg['file_type'] ?? '', 'image/') === 0;
            }
        }
        
        return $this->json($response, ['success' => true, 'data' => $messages]);
    }
    
    /**
     * Establecer estado de escritura
     */
    public function setTyping(Request $request, Response $response): Response
    {
        $userId = $_SESSION['user']['id'] ?? 0;
        if (!$userId) {
            return $this->json($response, ['error' => 'No autenticado'], 401);
        }
        
        $data = (array) $request->getParsedBody();
        $conversationId = (int)($data['conversation_id'] ?? 0);
        $isTyping = (bool)($data['is_typing'] ?? false);
        
        if (!$conversationId) {
            return $this->json($response, ['error' => 'ID de conversación inválido'], 400);
        }
        
        $db = $this->getDb($request);
        $typingModel = new ChatTyping($db);
        $typingModel->setTyping($conversationId, $userId, $isTyping);
        
        return $this->json($response, ['success' => true]);
    }
    
    /**
     * Obtener usuarios escribiendo
     */
    public function getTyping(Request $request, Response $response): Response
    {
        $userId = $_SESSION['user']['id'] ?? 0;
        if (!$userId) {
            return $this->json($response, ['error' => 'No autenticado'], 401);
        }
        
        $conversationId = (int)($request->getQueryParams()['conversation_id'] ?? 0);
        
        if (!$conversationId) {
            return $this->json($response, ['error' => 'ID de conversación inválido'], 400);
        }
        
        $db = $this->getDb($request);
        $typingModel = new ChatTyping($db);
        $typingUsers = $typingModel->getTypingUsers($conversationId, $userId);
        
        return $this->json($response, ['success' => true, 'data' => $typingUsers]);
    }
    
    /**
     * Descargar archivo adjunto
     */
    public function downloadFile(Request $request, Response $response, array $args): Response
    {
        $userId = $_SESSION['user']['id'] ?? 0;
        if (!$userId) {
            return $response->withHeader('Location', '/login')->withStatus(302);
        }
        
        $messageId = (int)($args['id'] ?? 0);
        
        $db = $this->getDb($request);
        $stmt = $db->prepare("SELECT file_path, file_name, file_type, conversation_id FROM chat_messages WHERE id = ?");
        $stmt->bind_param('i', $messageId);
        $stmt->execute();
        $message = $stmt->get_result()->fetch_assoc();
        
        if (!$message || !$message['file_path']) {
            return $response->withStatus(404);
        }
        
        $filePath = '/var/www/optimixs/storage/' . $message['file_path'];
        
        if (!file_exists($filePath)) {
            return $response->withStatus(404);
        }
        
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $filePath);
        finfo_close($finfo);
        
        $response->getBody()->write(file_get_contents($filePath));
        return $response
            ->withHeader('Content-Type', $mimeType)
            ->withHeader('Content-Disposition', 'inline; filename="' . $message['file_name'] . '"');
    }
}
