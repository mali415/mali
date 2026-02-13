<?php

require_once __DIR__ . '/../models/User.php';

class AuthService
{
    public static function attempt(PDO $pdo, string $email, string $password): ?array
    {
        $user = User::findByEmail($pdo, $email);
        if (!$user || !(int)$user['is_active']) {
            return null;
        }

        if (!password_verify($password, $user['password_hash'])) {
            return null;
        }

        return $user;
    }
}
