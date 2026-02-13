<?php

class AuditService
{
    public static function log(PDO $pdo, int $userId, string $entity, int $entityId, string $action, array $changes): void
    {
        $stmt = $pdo->prepare(
            'INSERT INTO audit_logs (user_id, entity, entity_id, action, changes_json, ip, user_agent, created_at)
             VALUES (:user_id, :entity, :entity_id, :action, :changes_json, :ip, :user_agent, NOW())'
        );
        $stmt->execute([
            'user_id' => $userId,
            'entity' => $entity,
            'entity_id' => $entityId,
            'action' => $action,
            'changes_json' => json_encode($changes, JSON_UNESCAPED_UNICODE),
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
        ]);
    }
}
