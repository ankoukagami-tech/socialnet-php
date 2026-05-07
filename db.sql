-- Social Network assignment database schema
-- Create database: socialnet
-- Create table: account

CREATE DATABASE IF NOT EXISTS socialnet
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE socialnet;

CREATE TABLE IF NOT EXISTS account (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL,
  fullname VARCHAR(100) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  description TEXT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

