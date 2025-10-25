# 🚀 Customer Visit Tracking - Quick Start Guide

## ⚡ Mulai Cepat dalam 5 Menit!

### 1️⃣ Database Setup (2 menit)

```bash
# Jalankan 2 file SQL ini di MySQL:
mysql -u root -p nama_database < database/migrations/create_customer_visit_tables.sql
mysql -u root -p nama_database < database/migrations/insert_customer_visit_modules.sql
```

**Atau via phpMyAdmin:**

1. Buka phpMyAdmin
2. Pilih database Anda
3. Tab "SQL"
4. Copy-paste isi `create_customer_visit_tables.sql`, Execute
5. Copy-paste isi `insert_customer_visit_modules.sql`, Execute

---

### 2️⃣ File Permission (30 detik)

```bash
# Buat folder upload dan set permission
mkdir -p assets/uploads/customer-visits
chmod 755 assets/uploads/customer-visits
```

**Windows:**

- Buat folder manual: `assets/uploads/customer-visits`
- Right-click → Properties → pastikan tidak read-only

---

### 3️⃣ Testing (2 menit)

#### A. Test sebagai **Admin** - Create Customer:

1. Login sebagai Admin
2. Menu → **Customer Management** → **Customer Data**
3. Klik **"Tambah Customer"**
4. Isi form:
   - Kode: `CUST001`
   - Nama: `Toko ABC`
   - Alamat: `Jl. Sudirman No. 123`
   - (Optional) Klik **"Ambil Lokasi Sekarang"** untuk GPS
5. **Save**

#### B. Test sebagai **Marketing** - Visit Customer:

1. Login sebagai Marketing (atau create user role marketing)
2. Menu → **Customer Visits** → **Mulai Kunjungan Baru**
3. Pilih customer dari list
4. Pilih tujuan kunjungan → **Konfirmasi**
5. **Allow GPS permission** di browser
6. Check-in berhasil!
7. Isi form hasil kunjungan
8. (Optional) Upload foto
9. **Selesaikan Kunjungan**

#### C. Test sebagai **Admin** - Monitor:

1. Login sebagai Admin
2. Menu → **Visit Monitoring**
3. Lihat dashboard statistics, charts, marketing performance

---

### 4️⃣ Troubleshooting Cepat

#### GPS tidak terdeteksi?

```
✅ Pastikan menggunakan HTTPS (atau localhost)
✅ Klik "Allow" saat browser minta permission
✅ Aktifkan GPS di device
```

#### Upload foto gagal?

```
✅ Check folder assets/uploads/customer-visits/ ada & writable
✅ Check PHP upload_max_filesize di php.ini (min 10MB)
```

#### Menu tidak muncul?

```
✅ Pastikan SQL insert_customer_visit_modules.sql sudah dijalankan
✅ Logout dan login ulang
✅ Clear cache browser (Ctrl+F5)
```

---

## 📱 **Akses untuk Testing**

### Marketing Access:

```
URL: http://yourdomain.com/customer-visits
Features:
- Dashboard marketing
- Mulai kunjungan baru
- Riwayat kunjungan
- Customer list
```

### Admin Access:

```
URL: http://yourdomain.com/customers
     http://yourdomain.com/customer-visits-monitoring
Features:
- Customer management (CRUD)
- Visit monitoring dashboard
- Marketing performance
- Reports
```

---

## 🎯 **Use Cases**

### Scenario 1: Marketing Visit Customer

```
1. Open app di HP Android
2. Customer Visits → Mulai Kunjungan Baru
3. Search customer "Toko ABC"
4. Pilih → Sales/Penawaran → Konfirmasi
5. GPS auto-detect → Check-in
6. (Meeting dengan customer...)
7. Isi form hasil → Upload foto toko
8. Check-out → Done!
```

### Scenario 2: Admin Monitor Performance

```
1. Login admin dashboard
2. Visit Monitoring
3. Filter bulan: Oktober 2024
4. Lihat:
   - Total visits: 150
   - Success rate: 75%
   - Top marketing: John Doe (50 visits)
5. Klik "Detail" pada marketing
6. View performance details & visit history
```

### Scenario 3: Admin Create Customer

```
1. Customers → Tambah Customer
2. Isi data customer baru
3. Klik "Ambil Lokasi Sekarang" (GPS)
4. Assign ke marketing: John Doe
5. Save → Customer siap dikunjungi!
```

---

## 📊 **Data Flow**

```
Marketing App (Mobile)
    ↓
Select Customer → Check-in (GPS) → Active Visit → Fill Form → Upload Photos → Check-out (GPS)
    ↓
Database: customer_visits
    ↓
Admin Dashboard: Monitoring & Reports
```

---

## ✅ **Verification Checklist**

Pastikan semua ini berfungsi:

- [ ] Login sebagai Marketing berhasil
- [ ] Menu "Customer Visits" muncul
- [ ] Bisa pilih customer dari list
- [ ] GPS terdeteksi saat check-in
- [ ] Timer berjalan di active visit
- [ ] Bisa upload foto (max 5)
- [ ] Check-out berhasil
- [ ] Data muncul di history
- [ ] Login sebagai Admin berhasil
- [ ] Menu "Customer Management" muncul
- [ ] Menu "Visit Monitoring" muncul
- [ ] Dashboard monitoring menampilkan data
- [ ] Chart trends tampil
- [ ] Bisa create/edit customer

---

## 🎉 **Done!**

Jika semua checklist ✅, maka sistem **READY TO USE!**

**Need Help?**

- Check: `CUSTOMER_VISIT_IMPLEMENTATION.md` (technical details)
- Check: `CUSTOMER_VISIT_DEPLOYMENT_GUIDE.md` (full deployment)
- Check: `CUSTOMER_VISIT_SUMMARY.md` (project summary)

---

**Happy Tracking! 🚀📍📸**
