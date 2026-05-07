<?php declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

session_start_secure();

function is_logged_in(): bool
{
  return !empty($_SESSION['account_id']) && is_int($_SESSION['account_id']);
}

function require_login(): void
{
  if (!is_logged_in()) {
    redirect_to('/socialnet/signin.php');
  }
}

function login_user(int $accountId, string $username): void
{
  session_regenerate_id(true);
  $_SESSION['account_id'] = $accountId;
  $_SESSION['username'] = $username;
}

function logout_user(): void
{
  $_SESSION = [];
  session_destroy();
}

function current_account_id(): ?int
{
  return is_logged_in() ? (int)$_SESSION['account_id'] : null;
}

function current_username(): ?string
{
  $u = $_SESSION['username'] ?? null;
  return is_string($u) ? $u : null;
}

