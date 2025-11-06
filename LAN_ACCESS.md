# Cara Mengakses Server PHP melalui LAN/WiFi

## Metode 1: Menggunakan Script (Recommended)

### Windows:
1. Jalankan `start-server-lan.bat`
2. Script akan otomatis menampilkan IP address lokal
3. Akses dari perangkat lain di LAN menggunakan: `http://[IP_ADDRESS]:5000`

### Linux/Mac:
1. Berikan permission: `chmod +x start-server-lan.sh`
2. Jalankan: `./start-server-lan.sh`
3. Akses dari perangkat lain di LAN menggunakan: `http://[IP_ADDRESS]:5000`

## Metode 2: Manual Command

### Windows:
```bash
php -S 0.0.0.0:5000
```

### Linux/Mac:
```bash
php -S 0.0.0.0:5000
```

**Catatan Penting:**
- Gunakan `0.0.0.0` (bukan `localhost`) agar server bisa diakses dari LAN
- `0.0.0.0` berarti server akan listen di semua network interface

## Menemukan IP Address Lokal

### Windows:
```bash
ipconfig
```
Cari "IPv4 Address" di bagian adapter yang aktif (WiFi atau Ethernet)

Atau gunakan:
```bash
ipconfig | findstr IPv4
```

### Linux:
```bash
hostname -I
```
atau
```bash
ip addr show | grep "inet "
```

### Mac:
```bash
ipconfig getifaddr en0    # untuk WiFi
ipconfig getifaddr en1    # untuk Ethernet
```

## Contoh Akses

Jika IP address lokal Anda adalah `192.168.1.100`:
- Dari komputer yang sama: `http://localhost:5000`
- Dari perangkat lain di LAN: `http://192.168.1.100:5000`

## Troubleshooting

### 1. Firewall Blocking
Jika tidak bisa diakses dari LAN, pastikan firewall mengizinkan port 5000:

**Windows:**
- Buka Windows Defender Firewall
- Advanced Settings > Inbound Rules > New Rule
- Pilih "Port" > TCP > Specific local ports: 5000
- Allow the connection
- Apply untuk semua profiles

**Linux (UFW):**
```bash
sudo ufw allow 5000/tcp
```

**Mac:**
- System Preferences > Security & Privacy > Firewall > Firewall Options
- Tambahkan aplikasi PHP atau izinkan port 5000

### 2. Router/Network Settings
- Pastikan semua perangkat berada di network yang sama (LAN/WiFi)
- Beberapa router memiliki "Client Isolation" yang perlu dimatikan

### 3. Port Already in Use
Jika port 5000 sudah digunakan, gunakan port lain:
```bash
php -S 0.0.0.0:8000
```

### 4. Security Warning (HTTPS)
Jika muncul warning tentang HTTPS, itu normal karena menggunakan HTTP. Untuk production, gunakan web server dengan SSL certificate.

## Keamanan

⚠️ **PENTING:**
- Server PHP built-in hanya untuk development/testing
- Jangan gunakan di production environment
- Hanya akses dari LAN yang terpercaya
- Untuk production, gunakan Apache/Nginx dengan konfigurasi keamanan yang tepat

## Quick Start

1. Buka terminal/command prompt di folder project
2. Windows: Jalankan `start-server-lan.bat`
3. Linux/Mac: Jalankan `./start-server-lan.sh`
4. Catat IP address yang ditampilkan
5. Akses dari perangkat lain: `http://[IP_ADDRESS]:5000`



