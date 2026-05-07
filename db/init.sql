-- php-auth-app database schema
-- Run as: SOURCE /path/to/db/init.sql;

CREATE DATABASE IF NOT EXISTS php_auth_app
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

CREATE USER IF NOT EXISTS 'php_auth_user'@'localhost' IDENTIFIED BY 'change_me';
GRANT ALL PRIVILEGES ON php_auth_app.* TO 'php_auth_user'@'localhost';
FLUSH PRIVILEGES;

USE php_auth_app;

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  email VARCHAR(100) NULL,
  password_hash VARCHAR(255) NOT NULL,
  description TEXT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_username (username),
  UNIQUE KEY uniq_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

