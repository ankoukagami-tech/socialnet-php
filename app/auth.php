<?php declare(strict_types=1);

$config = require __DIR__ . '/config.php';
require_once __DIR__ . '/helpers.php';

function app_session_start(): void
{
  static $started = false;
  if ($started) {
    return;
  }

  $secure = (bool)($config['session']['cookie_secure'] ?? false);
  $samesite = (string)($config['session']['cookie_samesite'] ?? 'Lax');

  if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
      'secure' => $secure,
      'httponly' => true,
      'samesite' => $samesite,
    ]);
    session_start();
  }

  $started = true;
}

app_session_start();

function require_login(): void
{
  if (empty($_SESSION['user_id'])) {
    redirect_to('/login.php');
  }
}

function current_user_id(): ?int
{
  $id = $_SESSION['user_id'] ?? null;
  if ($id === null) {
    return null;
  }
  return (int)$id;
}

function current_username(): ?string
{
  $u = $_SESSION['username'] ?? null;
  return is_string($u) ? $u : null;
}

function csrf_hidden_field(): string
{
  if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }
  return '<input type="hidden" name="csrf_token" value="' . h($_SESSION['csrf_token']) . '">';
}

function csrf_verify_or_die(?string $token): void
{
  $expected = $_SESSION['csrf_token'] ?? '';
  if (!$token || !is_string($token) || !hash_equals($expected, $token)) {
    http_response_code(403);
    echo 'CSRF token invalid.';
    exit;
  }
}

