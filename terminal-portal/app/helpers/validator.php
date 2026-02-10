<?php

function validate_machine(array $data, PDO $pdo, ?int $id = null): array
{
    $errors = [];

    $required = ['code', 'name', 'status'];
    foreach ($required as $field) {
        if (empty(trim((string)($data[$field] ?? '')))) {
            $errors[$field] = 'Bu alan zorunludur.';
        }
    }

    $criticality = $data['criticality'] ?? null;
    if ($criticality !== null && $criticality !== '' && !in_array($criticality, ['low', 'med', 'high'], true)) {
        $errors['criticality'] = 'Geçersiz kritiklik değeri.';
    }

    $status = $data['status'] ?? null;
    if ($status !== null && $status !== '' && !in_array($status, ['active', 'passive'], true)) {
        $errors['status'] = 'Geçersiz durum değeri.';
    }

    if (!empty($data['power_kw']) && !is_numeric($data['power_kw'])) {
        $errors['power_kw'] = 'Güç alanı sayısal olmalıdır.';
    }

    if (!empty($data['code'])) {
        $query = 'SELECT id FROM machines WHERE code = :code';
        $params = ['code' => $data['code']];
        if ($id !== null) {
            $query .= ' AND id <> :id';
            $params['id'] = $id;
        }
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        if ($stmt->fetch()) {
            $errors['code'] = 'Makine kodu zaten kullanılıyor.';
        }
    }

    return $errors;
}
