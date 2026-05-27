<?php

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}


function csrf_verify(): void {
    $submitted = $_POST['csrf_token'] ?? '';
    $stored = $_SESSION['csrf_token'] ?? '';
    
    if (!$stored || !hash_equals($stored, $submitted)) {
        http_response_code(403);
        die('Invalid CSRF token.');
    }

    unset($_SESSION['csrf_token']);
}

function requireAuth(): void {
    if (!isset($_SESSION["user_id"])) {
        header("Location: ?route=login");
        exit;
    }
}

function require_role(string $role): void {

    if (!isset($_SESSION["role"])) {
        header("Location: ?route=login");
        exit;
    }

    if ($_SESSION["role"] !== $role) {
        http_response_code(403);

        exit("You do not have permission to view this page.");
    }
}

define('IDLE_TIMEOUT', 15 * 60);   // Satt på 15 min            // För Testa 5 sek kör:   define('IDLE_TIMEOUT', 5);
define('ABSOLUTE_TIMEOUT', 8 * 60 * 60);  // Satt på  8 timmar

function check_idle_timeout(): void {

    if (!isset($_SESSION['last_active'])) {
        return;
    }

    if (time() - $_SESSION['last_active'] > IDLE_TIMEOUT) {

        session_unset();
        session_destroy();

        header('Location: ?route=login&reason=timeout');
        exit;
    }

    $_SESSION['last_active'] = time();
}

function check_absolute_timeout(): void {

    if (!isset($_SESSION['login_time'])) {
        return;
    }

    if (time() - $_SESSION['login_time'] > ABSOLUTE_TIMEOUT) {

        session_unset();
        session_destroy();

        header('Location: ?route=login&reason=expired');
        exit;
    }
}


?>