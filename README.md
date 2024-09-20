# Nginx-Laravel11 - filament

## Cài Filament
```cmd
docker exec -it Laravel11-web /bin/sh -c "composer require filament/filament"

composer require filament/filament
```

## Tài panel quản lý
```cmd
php artisan filament:install --panels
```

#### URL route mong muốn:
http://dev.org/dashboard/login
```cmd
 ┌ What is the ID? ─────────────────────────────────────────────┐
 │ dashboard                                                    │
 └──────────────────────────────────────────────────────────────┘
```
Mặc định là admin

![Login panel](./img/login-panel.png)

## Tạo user
```cmd
php artisan make:filament-user
```
 ┌ Name ────────────────────────────────────────────────────────┐
 │ vstars                                                       │
 └──────────────────────────────────────────────────────────────┘

 ┌ Email address ───────────────────────────────────────────────┐
 │ admin@gmail.com                                              │
 └──────────────────────────────────────────────────────────────┘

 ┌ Password ────────────────────────────────────────────────────┐
 │ ••••••••                                                     │
 └──────────────────────────────────────────────────────────────┘

Sau khi đăng nhập xong:

![Login panel](./img/dashboard-panel.png)

Chuyển môi trường để từ hạn chế cho phép vào dashboard

```env
APP_ENV=local 
->
APP_ENV=production 
```

## Tuỳ chỉnh cho panel

app/Providers/Filament/AdminPanelProvider.php:

```php
class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('dashboard')
            ->path('dashboard')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            // ... more settings
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
```

Thay đổi:
```php
return $panel
    ->path('dashboard') //đổi ở đây
    ->path('admin') 
```

Thêm chức năng:
```php
return $panel
    ->login() // thêm ở bên dưới
    ->registration() 
    ->passwordReset() 
    ->emailVerification() 
    ->profile()
```

Đổi màu:
```php
return $panel
    ->colors([
        'primary' => Color::Amber,
        'danger' => Color::Red,
        'gray' => Color::Zinc,
        'info' => Color::Blue,
        'success' => Color::Green,
        'warning' => Color::Amber,
    ])
```


