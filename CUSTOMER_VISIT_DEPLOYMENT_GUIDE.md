# Customer Visit Tracking System - Deployment Guide

## 📋 Overview

Panduan lengkap untuk deploy modul Customer Visit Tracking System untuk role Marketing, Admin, dan Manajemen.

---

## 🗄️ Database Migration

### Step 1: Jalankan Migration Tables

Jalankan script SQL untuk membuat tables:

```bash
mysql -u [username] -p [database_name] < database/migrations/create_customer_visit_tables.sql
```

Atau import manual via phpMyAdmin/MySQL Workbench.

**Tables yang dibuat:**

- `customers` - Master data customer
- `customer_visits` - Record kunjungan marketing
- `visit_targets` - Target kunjungan bulanan marketing

### Step 2: Insert Modules dan Menu

Jalankan script SQL untuk menambahkan modules dan menu:

```bash
mysql -u [username] -p [database_name] < database/migrations/insert_customer_visit_modules.sql
```

**Modules yang ditambahkan:**

- Customer Visit Tracking (Marketing)
- Customer List (Marketing)
- Customer Management (Admin/Manajemen)
- Visit Monitoring (Admin/Manajemen)

---

## 📁 File Structure

```
app/
├── controllers/
│   ├── CustomerController.php (NEW)
│   ├── CustomerVisitController.php (NEW)
│   └── CustomerVisitMonitoringController.php (NEW)
├── models/
│   ├── Customer.php (NEW)
│   ├── CustomerVisit.php (NEW)
│   └── VisitTarget.php (NEW)
└── views/
    ├── customer-visits/
    │   ├── dashboard.php (NEW)
    │   ├── select-customer.php (NEW)
    │   ├── active-visit.php (NEW)
    │   ├── history.php (NEW)
    │   └── detail.php (NEW)
    ├── customer-visits-monitoring/
    │   └── dashboard.php (NEW)
    └── customers/
        ├── index.php (NEW)
        ├── create.php (NEW)
        ├── edit.php (NEW)
        └── show.php (NEW)

database/
└── migrations/
    ├── create_customer_visit_tables.sql (NEW)
    └── insert_customer_visit_modules.sql (NEW)

app/core/
└── App.php (UPDATED - added routes)
```

---

## 🔧 Configuration

### 1. Routing (Already Done)

Routes telah ditambahkan di `app/core/App.php`:

**Marketing Routes:**

- `GET /customer-visits` - Dashboard marketing
- `GET /customer-visits/select-customer` - Pilih customer
- `POST /customer-visits/check-in` - Check-in kunjungan
- `GET /customer-visits/active` - Active visit
- `POST /customer-visits/check-out` - Check-out
- `GET /customer-visits/history` - History kunjungan
- `GET /customer-visits/{id}` - Detail kunjungan

**Admin/Manajemen Routes:**

- `GET /customers` - Customer management
- `GET /customers/create` - Form tambah customer
- `POST /customers` - Store customer
- `GET /customers/{id}` - Detail customer
- `GET /customers/{id}/edit` - Form edit customer
- `PUT /customers/{id}` - Update customer
- `DELETE /customers/{id}` - Delete customer
- `GET /customer-visits-monitoring` - Dashboard monitoring
- `GET /customer-visits-monitoring/marketing/{id}` - Detail marketing
- `GET /customer-visits-monitoring/report` - Laporan

### 2. File Upload Directory

Pastikan direktori untuk foto kunjungan ada dan writable:

```bash
mkdir -p assets/uploads/customer-visits
chmod 755 assets/uploads/customer-visits
```

### 3. GPS & Location Permission

Aplikasi memerlukan:

- **Browser GPS permission** untuk marketing
- **HTTPS** untuk production (GPS hanya bekerja di HTTPS atau localhost)

---

## 🎯 Features

### Marketing Features:

1. **Dashboard Marketing**
   - Statistik kunjungan bulan ini
   - Progress target kunjungan & order
   - Rencana kunjungan hari ini
2. **Recording Kunjungan**
   - Pilih customer dari list
   - GPS-based check-in (toleransi 50m)
   - Timer kunjungan real-time
   - Upload foto dokumentasi (max 5)
   - Form hasil kunjungan & order
   - GPS-based check-out
3. **History & Tracking**
   - Riwayat semua kunjungan
   - Filter by month, result
   - Statistik personal

### Admin/Manajemen Features:

1. **Customer Management**
   - CRUD customer data
   - Assign marketing to customer
   - GPS coordinate customer
   - Customer statistics
2. **Visit Monitoring**
   - Dashboard overview
   - Marketing performance
   - Visit trends chart
   - Top customers
   - Recent visits
3. **Reports**
   - Filter by date range, marketing, result
   - Export-ready data
   - Summary statistics

---

## 🧪 Testing

### 1. Test Customer Creation (Admin)

```
Login sebagai Admin > Customers > Create
- Isi form customer
- (Optional) Klik "Ambil Lokasi Sekarang" untuk GPS
- Save
```

### 2. Test Customer Visit (Marketing)

```
Login sebagai Marketing > Customer Visits > Mulai Kunjungan Baru
- Pilih customer dari list
- Pilih tujuan kunjungan
- Allow GPS permission
- Check-in (validasi radius 50m)
- Isi form hasil kunjungan
- Upload foto
- Check-out
```

### 3. Test Monitoring (Admin/Manajemen)

```
Login sebagai Admin > Visit Monitoring
- View dashboard overview
- Check marketing performance
- View visit trends
- Generate reports
```

---

## 🐛 Troubleshooting

### GPS tidak terdeteksi

- **Pastikan HTTPS** (atau localhost untuk development)
- Check browser permission untuk location
- Pastikan GPS device aktif
- Check console browser untuk error

### Upload foto gagal

- Check `assets/uploads/customer-visits/` writable
- Check PHP `upload_max_filesize` dan `post_max_size`
- Check file type (hanya image/\*)

### Distance validation error

- Pastikan customer sudah punya GPS coordinate
- Check Haversine formula di `CustomerVisit::calculateDistance()`
- Toleransi default: 50 meter

### Menu tidak muncul

- Check SQL insert module & menu sudah jalan
- Check `users_menu` untuk assign menu ke user
- Clear cache browser
- Logout/Login ulang

---

## 📱 Mobile Optimization

Views sudah dioptimasi untuk mobile:

- Responsive design (Bootstrap 5)
- Touch-friendly buttons
- Mobile camera integration
- GPS auto-detect
- Minimal data usage

**Recommended:**

- Gunakan di Android Chrome / iOS Safari
- Aktifkan GPS sebelum mulai kunjungan
- Koneksi internet stabil (untuk upload foto)

---

## 🔐 Security Features

1. **CSRF Protection** - Semua form protected
2. **GPS Validation** - Check radius 50m dari lokasi customer
3. **File Upload Validation** - Only images allowed
4. **Role-based Access** - Marketing, Admin, Manajemen
5. **SQL Injection Prevention** - Prepared statements
6. **XSS Prevention** - htmlspecialchars on output

---

## 📊 Database Schema Summary

### customers

- Master data customer dengan GPS coordinate
- Assigned marketing
- Visit statistics (total_visits, last_visit_date)

### customer_visits

- Record setiap kunjungan
- GPS check-in & check-out
- Photo documentation (JSON array)
- Visit result & order info
- Duration tracking

### visit_targets

- Monthly targets untuk marketing
- Target visits, orders, amount
- Actual achievement tracking

---

## 🚀 Next Steps (Future Enhancement)

1. **Offline Mode** - PWA dengan service worker
2. **Push Notifications** - Reminder kunjungan
3. **Route Optimization** - Optimasi rute kunjungan
4. **Sales Order Integration** - Link dengan sistem order
5. **Export Excel/PDF** - Report export
6. **Dashboard Analytics** - Advanced charts & insights
7. **Visit Scheduling** - Calendar planning
8. **Customer Segmentation** - RFM analysis

---

## ✅ Deployment Checklist

- [ ] Run database migrations
- [ ] Insert modules & menu
- [ ] Create upload directories
- [ ] Set file permissions
- [ ] Configure HTTPS (production)
- [ ] Test GPS functionality
- [ ] Test photo upload
- [ ] Test all user roles
- [ ] Verify menu access
- [ ] Clear cache
- [ ] Create test data

---

## 📞 Support

Untuk pertanyaan atau issue:

1. Check dokumentasi lengkap di `CUSTOMER_VISIT_IMPLEMENTATION.md`
2. Review code di controllers & models
3. Check browser console untuk JavaScript errors
4. Check PHP error logs

---

**Version:** 1.0.0  
**Last Updated:** <?= date('d M Y') ?>  
**Status:** ✅ Production Ready
