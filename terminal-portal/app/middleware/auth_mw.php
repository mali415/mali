<?php

require_once __DIR__ . '/../config/auth.php';

function require_login(): void
{
    if (!current_user()) {
        header('Location: /login');
        exit;
    }
}
