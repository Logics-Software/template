# F12 Lock Screen Feature

## Overview

Fitur F12 Lock Screen adalah fitur keamanan yang secara otomatis mengunci layar saat mendeteksi aktivitas developer tools atau shortcut keyboard yang berpotensi berbahaya.

## Features

### 1. F12 Key Detection

- Mendeteksi ketika user menekan tombol F12
- Secara otomatis trigger lock screen setelah delay tertentu

### 2. Developer Tools Detection

- **Window Size Detection**: Mendeteksi perubahan ukuran window yang menandakan developer tools terbuka
- **Console Detection**: Menggunakan `debugger` statement untuk mendeteksi console yang aktif
- **Right-click Disable**: Menonaktifkan context menu untuk mencegah inspect element

### 3. Keyboard Shortcuts Detection

Mendeteksi shortcut keyboard developer tools:

- `F12` - Developer Tools
- `Ctrl+I` - Inspect Element
- `Ctrl+J` - Console
- `Ctrl+C` - Copy (dalam context tertentu)
- `Ctrl+U` - View Source
- `Ctrl+K` - Search in Console

## Configuration

Fitur ini dapat dikonfigurasi melalui file `assets/js/app.js`:

```javascript
const config = {
  enabled: true, // Set to false to disable F12 lock screen
  warningDelay: 1000, // Delay before locking screen (ms)
  detectionMethods: {
    f12Key: true,
    windowSize: true,
    console: true,
    rightClick: true,
    shortcuts: true,
  },
};
```

### Configuration Options

- **enabled**: `true/false` - Enable/disable seluruh fitur
- **warningDelay**: `number` - Delay dalam milidetik sebelum lock screen aktif
- **detectionMethods**: Object untuk enable/disable method detection tertentu
  - **f12Key**: Deteksi tombol F12
  - **windowSize**: Deteksi perubahan ukuran window
  - **console**: Deteksi console aktif
  - **rightClick**: Disable right-click context menu
  - **shortcuts**: Deteksi keyboard shortcuts

## Usage

### Enable/Disable Feature

Untuk menonaktifkan fitur ini, ubah konfigurasi di `assets/js/app.js`:

```javascript
const config = {
  enabled: false, // Disable F12 lock screen
  // ... other config
};
```

### Customize Detection Methods

Untuk menonaktifkan method detection tertentu:

```javascript
const config = {
  enabled: true,
  detectionMethods: {
    f12Key: true,
    windowSize: false, // Disable window size detection
    console: true,
    rightClick: false, // Allow right-click
    shortcuts: true,
  },
};
```

## Lock Screen Behavior

### When Triggered

1. **Warning Message**: Menampilkan alert warning
2. **Delay**: Menunggu sesuai konfigurasi `warningDelay`
3. **Redirect**: Redirect ke halaman lock screen (`/lock-screen`)
4. **Lock Screen Page**: Menggunakan halaman lock screen yang sudah ada

### Lock Screen Behavior

- **Redirect**: Otomatis redirect ke `http://localhost:8000/lock-screen`
- **Existing Page**: Menggunakan halaman lock screen yang sudah ada di aplikasi
- **Password Required**: User harus memasukkan password untuk unlock
- **Session Protection**: Melindungi session user yang sedang login

### Unlock Process

1. User diarahkan ke halaman lock screen
2. User memasukkan password
3. Setelah password benar, user diarahkan kembali ke dashboard
4. Session tetap aman dan terproteksi

## Security Considerations

### Pros

- **Developer Tools Protection**: Mencegah akses ke developer tools
- **Source Code Protection**: Mencegah view source dan inspect element
- **Right-click Protection**: Mencegah context menu access
- **Multiple Detection Methods**: Berbagai cara deteksi untuk efektivitas

### Cons

- **User Experience**: Dapat mengganggu developer yang legitimate
- **Bypass Methods**: Dapat di-bypass dengan beberapa teknik
- **Performance**: Sedikit impact pada performance karena interval checks

## Bypass Prevention

Fitur ini menggunakan multiple detection methods untuk mempersulit bypass:

1. **F12 Key Detection**: Mencegah direct F12 press
2. **Window Size Detection**: Mendeteksi developer tools yang sudah terbuka
3. **Console Detection**: Mendeteksi console yang aktif
4. **Shortcut Detection**: Mencegah berbagai keyboard shortcuts

## Browser Compatibility

Fitur ini bekerja di semua browser modern yang mendukung:

- `addEventListener`
- `performance.now()`
- `debugger` statement
- CSS `backdrop-filter`

## Troubleshooting

### Feature Not Working

1. Check apakah `enabled: true` di konfigurasi
2. Pastikan user sudah login (fitur hanya aktif untuk logged in users)
3. Check console untuk error JavaScript

### False Positives

1. Disable method detection yang menyebabkan false positive
2. Increase `warningDelay` untuk memberikan waktu lebih
3. Check window size threshold jika window size detection bermasalah

### Performance Issues

1. Reduce interval frequency di detection methods
2. Disable method detection yang tidak diperlukan
3. Optimize threshold values

## Example Usage

### Basic Usage

```javascript
// Default configuration - semua detection methods aktif
const config = {
  enabled: true,
  warningDelay: 1000,
  detectionMethods: {
    f12Key: true,
    windowSize: true,
    console: true,
    rightClick: true,
    shortcuts: true,
  },
};
```

### Custom Configuration

```javascript
// Hanya F12 dan window size detection
const config = {
  enabled: true,
  warningDelay: 2000,
  detectionMethods: {
    f12Key: true,
    windowSize: true,
    console: false,
    rightClick: false,
    shortcuts: false,
  },
};
```

## Integration

Fitur ini terintegrasi dengan:

- **Session Management**: Hanya aktif untuk logged in users
- **Alert System**: Menggunakan sistem alert yang ada
- **Theme System**: Mengikuti theme yang aktif
- **CSS Framework**: Menggunakan Bootstrap untuk styling
