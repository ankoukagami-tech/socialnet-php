<?php declare(strict_types=1);

function h(string $s): string
{
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function redirect_to(string $path): void
{
  header('Location: ' . $path);
  exit;
}

function session_start_secure(): void
{
  if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params([
      'httponly' => true,
      'samesite' => 'Lax',
      'secure' => false,
    ]);
    session_start();
  }
}

function csrf_token(): string
{
  session_start_secure();
  if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }
  return (string)$_SESSION['csrf_token'];
}

function csrf_hidden_field(): string
{
  return '<input type="hidden" name="csrf_token" value="' . h(csrf_token()) . '">';
}

function csrf_verify_or_die(?string $token): void
{
  session_start_secure();
  $expected = (string)($_SESSION['csrf_token'] ?? '');
  if (!$token || !hash_equals($expected, $token)) {
    http_response_code(403);
    echo 'CSRF token invalid.';
    exit;
  }
}

