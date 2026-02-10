<?php

function json_success($data = null, string $message = '', int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode([
        'ok' => true,
        'data' => $data,
        'message' => $message,
    ]);
    exit;
}

function json_error(string $code, string $message, $details = null, int $status = 400): void
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode([
        'ok' => false,
        'error' => [
            'code' => $code,
            'message' => $message,
            'details' => $details,
        ],
    ]);
    exit;
}
