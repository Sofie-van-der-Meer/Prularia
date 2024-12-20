<?php

declare(strict_types=1);

require_once 'bootstrap.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$userName = isset($_SESSION['user']) ? htmlspecialchars($_SESSION['user']) : null;