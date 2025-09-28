# Lock Screen Module - Usage Guide

## Overview

Lock Screen module adalah fitur keamanan yang memungkinkan pengguna untuk mengunci layar mereka setelah periode tidak aktif. Modul ini terinspirasi dari desain [Hando Lock Screen](https://zoyothemes.com/hando/html/auth-lock-screen) dengan beberapa penyesuaian.

## Features

### 1. Auto-Lock Functionality

- **Timeout**: 15 menit tidak aktif (default)
- **Warning**: Peringatan 1 menit sebelum auto-lock
- **Remember Me**: Extended timeout untuk user yang memilih "remember me"
- **Activity Detection**: Mendeteksi mouse, keyboard, scroll, dan touch events

### 2. Lock Screen Interface

- **User Avatar**: Menampilkan foto profil user
- **User Info**: Nama dan email user
- **Password Input**: Input password dengan toggle visibility
- **Remember Me**: Checkbox untuk extended session
- **Fingerprint/Face ID**: Placeholder untuk biometrik (UI only)

### 3. Security Features

- **CSRF Protection**: Token validation untuk semua requests
- **Session Management**: Proper session handling
- **Password Verification**: Secure password verification
- **Auto Logout**: Logout otomatis jika diperlukan

## File Structure

```
app/
├── controllers/
│   └── LockScreenController.php     # Controller untuk lock screen
├── views/
│   └── auth/
│       └── lock-screen.php         # View untuk lock screen
assets/
├── css/
│   └── style.css                   # CSS untuk lock screen (ditambahkan di akhir)
└── js/
    └── lock-screen.js              # JavaScript untuk auto-lock
docs/
└── lock-screen-usage.md            # Dokumentasi ini
```

## Usage

### 1. Manual Lock Screen

Untuk mengunci layar secara manual, arahkan user ke:

```
/lock-screen
```

### 2. Auto-Lock Configuration

Auto-lock akan aktif secara otomatis pada semua halaman yang bukan lock screen atau login.

#### Default Settings:

- **Idle Timeout**: 15 menit
- **Warning Time**: 14 menit (1 menit warning)
- **Remember Me Timeout**: 1 jam

#### Customize Timeout:

Edit file `assets/js/lock-screen.js`:

```javascript
this.lockTimeout = 15 * 60 * 1000; // 15 minutes
this.warningTime = 14 * 60 * 1000; // 14 minutes
```

### 3. Routes

Tambahkan routes berikut ke router:

```php
// Lock Screen Routes
$router->get('/lock-screen', 'LockScreenController@index');
$router->post('/unlock', 'LockScreenController@unlock');
$router->get('/lock', 'LockScreenController@lock');
$router->post('/lock-screen/logout', 'LockScreenController@logout');
```

### 4. Include JavaScript

Tambahkan script berikut ke layout utama (setelah login):

```html
<!-- Lock Screen Auto-Lock -->
<script src="<?php echo APP_URL; ?>/assets/js/lock-screen.js"></script>
```

## API Endpoints

### GET /lock-screen

Menampilkan halaman lock screen.

**Requirements:**

- User harus sudah login
- Session harus aktif

**Response:**

- Lock screen form dengan user data

### POST /unlock

Membuka kunci layar dengan password.

**Parameters:**

- `password` (required): Password user
- `remember_me` (optional): Boolean untuk extended session
- `_token` (required): CSRF token

**Response:**

- Redirect ke dashboard jika berhasil
- Redirect ke lock screen jika gagal

### GET /lock

Mengunci layar secara manual.

**Response:**

- Redirect ke lock screen

### POST /lock-screen/logout

Logout dari lock screen.

**Response:**

- Redirect ke login page

## Styling

### CSS Classes

- `.lock-screen-container`: Container utama
- `.lock-screen-wrapper`: Wrapper form
- `.lock-form-section`: Section form
- `.lock-header`: Header dengan avatar dan info user
- `.user-avatar`: Container avatar
- `.avatar-img`: Gambar avatar
- `.lock-title`: Judul "Hello [Name]!"
- `.lock-subtitle`: Subtitle dengan email
- `.btn-unlock`: Tombol unlock

### Customization

CSS untuk lock screen ditambahkan di akhir file `assets/css/style.css` dengan prefix yang jelas untuk menghindari konflik dengan CSS utama.

## Security Considerations

1. **CSRF Protection**: Semua form menggunakan CSRF token
2. **Session Validation**: Memvalidasi session sebelum akses
3. **Password Verification**: Menggunakan `password_verify()` untuk keamanan
4. **Auto-Cleanup**: Membersihkan timer dan event listeners
5. **Remember Me**: Optional extended session dengan cookie

## Browser Support

- **Modern Browsers**: Chrome, Firefox, Safari, Edge
- **Mobile**: iOS Safari, Chrome Mobile
- **Features**: ES6 classes, modern DOM APIs

## Troubleshooting

### 1. Auto-Lock Tidak Berfungsi

- Pastikan `lock-screen.js` di-include setelah login
- Cek console untuk error JavaScript
- Pastikan user sudah login

### 2. Styling Issues

- Pastikan CSS lock screen ditambahkan dengan benar
- Cek untuk CSS conflicts
- Pastikan Bootstrap dan Font Awesome tersedia

### 3. Session Issues

- Pastikan session management berfungsi dengan benar
- Cek CSRF token generation
- Pastikan user model berfungsi

## Future Enhancements

1. **Biometric Authentication**: Integrasi fingerprint/Face ID
2. **Multiple Timeouts**: Berbagai timeout berdasarkan role user
3. **Activity Logging**: Log aktivitas user untuk audit
4. **Custom Messages**: Pesan kustom untuk warning
5. **Mobile Optimization**: Optimasi khusus untuk mobile
