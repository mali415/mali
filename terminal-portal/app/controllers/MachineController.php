<?php

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../helpers/response.php';
require_once __DIR__ . '/../helpers/validator.php';
require_once __DIR__ . '/../models/Machine.php';
require_once __DIR__ . '/../services/AuditService.php';

class MachineController
{
    public static function index(): void
    {
        $pdo = get_pdo();
        $machines = Machine::all($pdo);
        json_success($machines);
    }

    public static function show(int $id): void
    {
        $pdo = get_pdo();
        $machine = Machine::find($pdo, $id);
        if (!$machine) {
            json_error('machine.not_found', 'Makine bulunamadı.', null, 404);
        }
        json_success($machine);
    }

    public static function store(): void
    {
        $pdo = get_pdo();
        $data = json_decode((string)file_get_contents('php://input'), true) ?? [];
        $errors = validate_machine($data, $pdo, null);
        if (!empty($errors)) {
            json_error('validation.failed', 'Doğrulama hatası.', $errors, 400);
        }

        $id = Machine::create($pdo, $data);
        $user = current_user();
        AuditService::log($pdo, (int)$user['id'], 'machines', $id, 'create', ['after' => $data]);

        json_success(['id' => $id], 'Makine oluşturuldu.', 201);
    }

    public static function update(int $id): void
    {
        $pdo = get_pdo();
        $existing = Machine::find($pdo, $id);
        if (!$existing) {
            json_error('machine.not_found', 'Makine bulunamadı.', null, 404);
        }

        $data = json_decode((string)file_get_contents('php://input'), true) ?? [];
        $errors = validate_machine($data, $pdo, $id);
        if (!empty($errors)) {
            json_error('validation.failed', 'Doğrulama hatası.', $errors, 400);
        }

        Machine::update($pdo, $id, $data);
        $user = current_user();
        AuditService::log($pdo, (int)$user['id'], 'machines', $id, 'update', ['before' => $existing, 'after' => $data]);

        json_success(null, 'Makine güncellendi.');
    }

    public static function destroy(int $id): void
    {
        $pdo = get_pdo();
        $existing = Machine::find($pdo, $id);
        if (!$existing) {
            json_error('machine.not_found', 'Makine bulunamadı.', null, 404);
        }

        Machine::delete($pdo, $id);
        $user = current_user();
        AuditService::log($pdo, (int)$user['id'], 'machines', $id, 'delete', ['before' => $existing]);

        json_success(null, 'Makine silindi.');
    }
}
