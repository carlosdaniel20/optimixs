<?php
declare(strict_types=1);

namespace App\Controllers;

use mysqli;
use App\Support\Csrf;
use App\Support\Log;
use App\Support\RememberMeService;
use App\Support\RouteTranslator;
use App\Models\AuditLogModel;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController
{
    public function loginForm(Request $request, Response $response): Response
    {
        $token = Csrf::ensureToken();
        $params = $request->getQueryParams();
        $returnUrl = $this->sanitizeReturnUrl($params['return_url'] ?? null);

        ob_start();
        $csrf_token = $token;
        $return_url = $returnUrl;
        require __DIR__ . '/../Views/auth/login.php';
        $html = ob_get_clean();

        $response->getBody()->write($html);
        return $response;
    }

    public function login(Request $request, Response $response, mysqli $db): Response
    {
        $data = (array) ($request->getParsedBody() ?? []);
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';
        $remember = !empty($data['remember']);
        $returnUrl = $this->sanitizeReturnUrl($data['return_url'] ?? null);

        if ($email !== '' && $password !== '') {
            $stmt = $db->prepare("SELECT id, email, password, tipo_utente, email_verificata, stato, nome, cognome, locale FROM utenti WHERE LOWER(email) = LOWER(?) LIMIT 1");
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $res = $stmt->get_result();
            $row = $res ? $res->fetch_assoc() : null;
            $stmt->close();

            $dummyHash = '$2y$12$PXZb520pM93TmNGnoJy2TuhssLxu4XversvqtKZ4B7xrm0sAldZE6';
            $hashToCheck = (string) ($row['password'] ?? $dummyHash);

            if (password_verify($password, $hashToCheck) && $row) {
                if (((int) ($row['email_verificata'] ?? 0)) !== 1) {
                    return $response->withHeader('Location', RouteTranslator::route('login') . '?error=email_not_verified')->withStatus(302);
                }
                if (($row['stato'] ?? '') !== 'attivo') {
                    return $response->withHeader('Location', RouteTranslator::route('login') . '?error=account_pending')->withStatus(302);
                }
                
                session_regenerate_id(true);
                Csrf::regenerate();

                $userId = (int)$row['id'];
                $sessionId = session_id();

                $_SESSION['user'] = [
                    'id' => $userId,
                    'email' => $row['email'],
                    'tipo_utente' => $row['tipo_utente'],
                    'name' => trim(($row['nome'] ?? '') . ' ' . ($row['cognome'] ?? '')),
                ];

                if (!empty($row['locale'])) {
                    $requestedLocale = (string) $row['locale'];
                    if (\App\Support\I18n::setLocale($requestedLocale)) {
                        $_SESSION['locale'] = $requestedLocale;
                    }
                }

                // ========== GUARDAR SESIÓN ==========
                $ip = $_SERVER['REMOTE_ADDR'] ?? '';
                $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
                
                // Insertar sesión básica
                $insertStmt = $db->prepare("INSERT INTO user_session_log (user_id, session_id, login_time, ip_address, user_agent) VALUES (?, ?, NOW(), ?, ?)");
                $insertStmt->bind_param('isss', $userId, $sessionId, $ip, $userAgent);
                $insertStmt->execute();
                $lastId = $insertStmt->insert_id;
                $insertStmt->close();
                
                // Obtener geolocalización (usando file_get_contents que es más simple)
                $country = null;
                $city = null;
                
                $geoData = @file_get_contents("http://ip-api.com/json/{$ip}?fields=status,country,city");
                if ($geoData) {
                    $geo = json_decode($geoData, true);
                    if ($geo && isset($geo['status']) && $geo['status'] === 'success') {
                        $country = $geo['country'] ?? null;
                        $city = $geo['city'] ?? null;
                    }
                }
                
                // Actualizar con país y ciudad
                if ($country || $city) {
                    $updateStmt = $db->prepare("UPDATE user_session_log SET country = ?, city = ? WHERE id = ?");
                    $updateStmt->bind_param('ssi', $country, $city, $lastId);
                    $updateStmt->execute();
                    $updateStmt->close();
                }
                // ===================================

                if ($remember) {
                    $rememberMeService = new RememberMeService($db);
                    $rememberMeService->createToken($userId);
                }

                Log::security('login.success', [
                    'email' => $email,
                    'user_id' => $userId,
                    'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
                ]);

                if ($returnUrl !== null) {
                    $redirectUrl = $returnUrl;
                } elseif (in_array($row['tipo_utente'], ['admin', 'staff'], true)) {
                    $redirectUrl = '/admin/dashboard';
                } else {
                    $redirectUrl = '/user/dashboard';
                }

                return $response->withHeader('Location', $redirectUrl)->withStatus(302);
            }
            password_verify($password, $dummyHash);
        }

        return $response->withHeader('Location', RouteTranslator::route('login') . '?error=invalid_credentials')->withStatus(302);
    }

    public function logout(Request $request, Response $response, mysqli $db): Response
    {
        $userId = $_SESSION['user']['id'] ?? null;
        $sessionId = session_id();

        if ($userId) {
            try {
                $auditModel = new AuditLogModel($db);
                $auditModel->logLogout($userId, $sessionId);
            } catch (\Exception $e) {
                error_log("Error en logout: " . $e->getMessage());
            }
        }

        $rememberMeService = new RememberMeService($db);
        $rememberMeService->revokeCurrentToken();

        Csrf::regenerate();
        $_SESSION = [];
        
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
        
        return $response->withHeader('Location', RouteTranslator::route('login'))->withStatus(302);
    }

    private function sanitizeReturnUrl(?string $url): ?string
    {
        if ($url === null) {
            return null;
        }
        $clean = trim(str_replace(["\r", "\n"], '', $url));
        if ($clean === '' || !str_starts_with($clean, '/')) {
            return null;
        }
        if (str_starts_with($clean, '//')) {
            return null;
        }
        return $clean;
    }
}
