<?php declare(strict_types=1);

// Defaults (an toàn để commit lên GitHub). Bạn cần ghi đè bằng app/config.local.php
// trên Kali hoặc server thực tế.
return [
  'db' => [
    'host' => '127.0.0.1',
    'port' => 3306,
    'name' => 'php_auth_app',
    'user' => 'php_auth_user',
    'pass' => 'CHANGE_ME',
    'charset' => 'utf8mb4',
  ],
  'session' => [
    'cookie_secure' => false, // HTTP-only: false. Khi dùng HTTPS: set true.
    'cookie_samesite' => 'Lax',
  ],
];

