<?php

function start_session(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function current_user(): ?array
{
    start_session();
    return $_SESSION['user'] ?? null;
}

function set_current_user(array $user): void
{
    start_session();
    $_SESSION['user'] = $user;
}

function clear_current_user(): void
{
    start_session();
    unset($_SESSION['user']);
}
