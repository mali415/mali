<?php

require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Permission.php';
require_once __DIR__ . '/../helpers/response.php';

function require_permission(string $code, bool $is_api = false): void
{
    $user = current_user();
    if (!$user) {
        if ($is_api) {
            json_error('auth.required', 'Giriş yapılmalı.', null, 401);
        }
        header('Location: /login');
        exit;
    }

    $pdo = get_pdo();
    if (!Permission::userHasPermission($pdo, (int)$user['id'], $code)) {
        if ($is_api) {
            json_error('auth.forbidden', 'Yetkiniz yok.', null, 403);
        }
        http_response_code(403);
        echo 'Yetkiniz yok.';
        exit;
    }
}
