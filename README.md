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

## Nginx

Use `nginx/php-auth-app.conf` as a template.
Update `fastcgi_pass` to match your installed PHP-FPM socket (e.g. `/run/php/php8.2-fpm.sock`).

