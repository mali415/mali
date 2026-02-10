<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../services/AuthService.php';

class AuthController
{
    public static function login(): void
    {
        $pdo = get_pdo();
        $email = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        $user = AuthService::attempt($pdo, $email, $password);
        if (!$user) {
            $_SESSION['error'] = 'E-posta veya şifre hatalı.';
            header('Location: /login');
            exit;
        }

        set_current_user([
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role_id' => $user['role_id'],
        ]);

        $stmt = $pdo->prepare('UPDATE users SET last_login_at = NOW() WHERE id = :id');
        $stmt->execute(['id' => $user['id']]);

        header('Location: /dashboard');
        exit;
    }

    public static function logout(): void
    {
        clear_current_user();
        header('Location: /login');
        exit;
    }

    public static function apiLogin(): void
    {
        $pdo = get_pdo();
        $body = json_decode((string)file_get_contents('php://input'), true) ?? [];
        $email = trim((string)($body['email'] ?? ''));
        $password = (string)($body['password'] ?? '');

        $user = AuthService::attempt($pdo, $email, $password);
        if (!$user) {
            json_error('auth.invalid', 'E-posta veya şifre hatalı.', null, 401);
        }

        set_current_user([
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role_id' => $user['role_id'],
        ]);

        json_success(['id' => $user['id'], 'name' => $user['name'], 'email' => $user['email']]);
    }

    public static function apiLogout(): void
    {
        clear_current_user();
        json_success(null, 'Çıkış yapıldı.');
    }

    public static function apiMe(): void
    {
        $user = current_user();
        if (!$user) {
            json_error('auth.required', 'Giriş yapılmalı.', null, 401);
        }
        json_success($user);
    }
}
