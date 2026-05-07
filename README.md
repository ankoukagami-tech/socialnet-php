# php-auth-app (Plain PHP + MySQL + Nginx)

Web app đăng ký (`register`), đăng nhập (`login`), hiển thị/cập nhật `description` và lưu vào MySQL.

## 1) File chính

- `register.php`: trang đăng ký (username, email, password, description)
- `login.php`: trang đăng nhập
- `description.php`: trang xem/cập nhật description của user đang đăng nhập
- `logout.php`: đăng xuất
- `app/`: helpers + kết nối DB

## 2) Database

Chạy SQL:

```sql
SOURCE /path/to/php-auth-app/db/init.sql;
```

Sau đó nhớ chỉnh mật khẩu MySQL ở `db/init.sql` (các chỗ `change_me`) cho khớp cấu hình app.

## 3) Cấu hình app trên Kali

Tạo file:

`app/config.local.php`

Ví dụ:

```php
<?php
return [
  'db' => [
    'host' => '127.0.0.1',
    'port' => 3306,
    'name' => 'php_auth_app',
    'user' => 'php_auth_user',
    'pass' => 'CHANGE_ME',
    'charset' => 'utf8mb4',
  ],
];
```

## 4) Nginx

File mẫu cấu hình:

- `nginx/php-auth-app.conf`

Bạn thay `server_name` và chỉnh `fastcgi_pass` (php-fpm sock) cho đúng version PHP trên Kali, rồi bật site.

## 5) Lưu ý bảo mật (tối thiểu)

- Password hash bằng `password_hash()`
- Prepared statements cho truy vấn
- CSRF token cho form

