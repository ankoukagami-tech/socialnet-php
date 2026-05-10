# Social Network (PHP + MySQL + Nginx + Linux)

This repo implements the “Social Network” assignment.

## Required URLs

- Admin page: `/admin/newuser.php`
- SignIn: `/socialnet/signin.php`
- Home: `/socialnet/index.php`
- Setting: `/socialnet/setting.php`
- Profile: `/socialnet/profile.php` (optional query: `?owner=some_user`)
- About: `/socialnet/about.php`
- SignOut: `/socialnet/signout.php`

MenuBar is included in Home/Setting/Profile/About.

## Database

Import `db.sql` to create database `socialnet` and table `account`.

The assignment mentions a `password` column; this schema stores the bcrypt hash in `password_hash`, which meets the same requirement and is safer than storing plaintext passwords.

Example:

```sql
SOURCE /var/www/php-auth-app/db.sql;
```

## Configuration

Create `includes/config.local.php` on the server (not committed).

Example:

```php
<?php
return [
  'db' => [
    'host' => '127.0.0.1',
    'port' => 3306,
    'name' => 'socialnet',
    'user' => 'socialnet_user',
    'pass' => 'CHANGE_ME',
    'charset' => 'utf8mb4',
  ],
  'student' => [
    'name' => 'YOUR_NAME',
    'number' => 'YOUR_STUDENT_NUMBER',
  ],
];
```

## Deploy on Linux (Nginx)

1. Copy or clone this repository to `/var/www/php-auth-app` and ensure the web user can read it (often `www-data`).
2. Import `db.sql` and create `includes/config.local.php` as described above.
3. Copy `nginx/php-auth-app.conf` into your Nginx `sites-available`, enable the site, then:

```bash
sudo nginx -t && sudo systemctl reload nginx
```

4. Set `fastcgi_pass` to the PHP-FPM socket that exists on your machine. Check with:

```bash
ls -la /run/php/
```

Examples: `php8.2-fpm.sock`, `php8.3-fpm.sock`, `php8.4-fpm.sock`. The template uses **PHP 8.4** (`/run/php/php8.4-fpm.sock`), which matches typical current Kali installs; change the filename if yours differs.

## Quick demo (same machine)

With Nginx listening on port 80 and the document root set to this project:

- `http://127.0.0.1/admin/newuser.php` — create a user  
- `http://127.0.0.1/socialnet/signin.php` — sign in and test the rest of the app

