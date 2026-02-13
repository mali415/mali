<?php

class Machine
{
    public static function all(PDO $pdo): array
    {
        $stmt = $pdo->query('SELECT * FROM machines ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    public static function find(PDO $pdo, int $id): ?array
    {
        $stmt = $pdo->prepare('SELECT * FROM machines WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $machine = $stmt->fetch();
        return $machine ?: null;
    }

    public static function create(PDO $pdo, array $data): int
    {
        $stmt = $pdo->prepare(
            'INSERT INTO machines (code, name, location, type, manufacturer, model, serial_no, power_kw, criticality, status, notes, created_at, updated_at)
             VALUES (:code, :name, :location, :type, :manufacturer, :model, :serial_no, :power_kw, :criticality, :status, :notes, NOW(), NOW())'
        );
        $stmt->execute([
            'code' => $data['code'],
            'name' => $data['name'],
            'location' => $data['location'] ?? null,
            'type' => $data['type'] ?? null,
            'manufacturer' => $data['manufacturer'] ?? null,
            'model' => $data['model'] ?? null,
            'serial_no' => $data['serial_no'] ?? null,
            'power_kw' => $data['power_kw'] !== '' ? $data['power_kw'] : null,
            'criticality' => $data['criticality'] ?? null,
            'status' => $data['status'],
            'notes' => $data['notes'] ?? null,
        ]);

        return (int)$pdo->lastInsertId();
    }

    public static function update(PDO $pdo, int $id, array $data): bool
    {
        $stmt = $pdo->prepare(
            'UPDATE machines SET
                code = :code,
                name = :name,
                location = :location,
                type = :type,
                manufacturer = :manufacturer,
                model = :model,
                serial_no = :serial_no,
                power_kw = :power_kw,
                criticality = :criticality,
                status = :status,
                notes = :notes,
                updated_at = NOW()
             WHERE id = :id'
        );

        return $stmt->execute([
            'id' => $id,
            'code' => $data['code'],
            'name' => $data['name'],
            'location' => $data['location'] ?? null,
            'type' => $data['type'] ?? null,
            'manufacturer' => $data['manufacturer'] ?? null,
            'model' => $data['model'] ?? null,
            'serial_no' => $data['serial_no'] ?? null,
            'power_kw' => $data['power_kw'] !== '' ? $data['power_kw'] : null,
            'criticality' => $data['criticality'] ?? null,
            'status' => $data['status'],
            'notes' => $data['notes'] ?? null,
        ]);
    }

    public static function delete(PDO $pdo, int $id): bool
    {
        $stmt = $pdo->prepare('DELETE FROM machines WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    public static function counts(PDO $pdo): array
    {
        $total = (int)$pdo->query('SELECT COUNT(*) FROM machines')->fetchColumn();
        $active = (int)$pdo->query("SELECT COUNT(*) FROM machines WHERE status = 'active'")->fetchColumn();
        $passive = (int)$pdo->query("SELECT COUNT(*) FROM machines WHERE status = 'passive'")->fetchColumn();

        return [
            'total' => $total,
            'active' => $active,
            'passive' => $passive,
        ];
    }
}
