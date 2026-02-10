<?php

class Permission
{
    public static function userHasPermission(PDO $pdo, int $userId, string $code): bool
    {
        $stmt = $pdo->prepare(
            'SELECT COUNT(*) FROM permissions p
             INNER JOIN role_permissions rp ON rp.permission_id = p.id
             INNER JOIN users u ON u.role_id = rp.role_id
             WHERE u.id = :user_id AND p.code = :code'
        );
        $stmt->execute(['user_id' => $userId, 'code' => $code]);
        return (int)$stmt->fetchColumn() > 0;
    }
}
