<?php declare(strict_types=1);

require __DIR__ . '/app/auth.php';

// Clear auth data.
$_SESSION = [];
session_destroy();

redirect_to('/login.php');

