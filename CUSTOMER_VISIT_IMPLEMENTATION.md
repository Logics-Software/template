# 📍 CUSTOMER VISIT TRACKING SYSTEM - IMPLEMENTATION GUIDE

## ✅ SUDAH SELESAI (Backend & Routing)

### 1. **Database Schema** ✅

**File**: `database/migrations/create_customer_visit_tables.sql`

**Tables Created:**

- ✅ `customers` - Master data customer
- ✅ `customer_visits` - Data kunjungan dengan GPS tracking
- ✅ `visit_targets` - Target kunjungan bulanan per marketing

**Sample Data:**

- ✅ 8 Customer dummy (Toko, Apotek, Rumah Sakit, Distributor)
- ✅ 1 Target untuk user marketing (ID: 28)

**Cara Install:**

```sql
-- Import ke database MySQL
SOURCE database/migrations/create_customer_visit_tables.sql;
-- atau
mysql -u root -p template < database/migrations/create_customer_visit_tables.sql
```

---

### 2. **Models** ✅

**Files Created:**

#### `app/models/Customer.php`

- ✅ CRUD operations customer
- ✅ Search & filter customer
- ✅ Get customers by marketing
- ✅ Haversine formula (find nearby customers)
- ✅ Update visit statistics
- ✅ Customer with stats (total visits, orders, amount)
- ✅ Generate customer code auto (CUST0001, CUST0002, dst)

#### `app/models/CustomerVisit.php`

- ✅ Check-in visit (GPS tracking)
- ✅ Check-out visit (complete visit)
- ✅ Calculate distance (Haversine formula)
- ✅ **Validate GPS location (radius 50m)**
- ✅ Visit statistics & reporting
- ✅ Get active visit (yang belum checkout)
- ✅ Visit history with pagination
- ✅ Generate visit code auto (VST202510220001, dst)
- ✅ Multi-photo upload handler

#### `app/models/VisitTarget.php`

- ✅ Set monthly target per marketing
- ✅ Auto calculate actual vs target
- ✅ Achievement percentage
- ✅ Update actuals from visits table

---

### 3. **Controllers** ✅

**Files Created:**

#### `app/controllers/CustomerVisitController.php`

**Methods:**

- ✅ `index()` - Dashboard marketing (today visits, targets, stats)
- ✅ `selectCustomer()` - Pilih customer untuk kunjungan
- ✅ `checkIn()` - Start visit dengan GPS validation
- ✅ `activeVisit()` - Form kunjungan aktif
- ✅ `checkOut()` - Complete visit dengan foto & notes
- ✅ `history()` - Riwayat kunjungan dengan filter
- ✅ `detail()` - Detail kunjungan
- ✅ `customers()` - List customer marketing
- ✅ `searchCustomers()` - API search customer (AJAX)
- ✅ `validateLocation()` - API validate GPS (AJAX)
- ✅ Photo upload handler (max 5 foto, 2MB each)

#### `app/controllers/CustomerController.php`

**For Admin/Manajemen only:**

- ✅ `index()` - List semua customer
- ✅ `create()` - Tambah customer baru
- ✅ `store()` - Save customer
- ✅ `edit()` - Edit customer
- ✅ `update()` - Update customer
- ✅ `destroy()` - Delete customer
- ✅ `show()` - Detail customer with visit stats

---

### 4. **Routing** ✅

**File**: `app/core/App.php`

**Customer Visit Routes (Marketing):**

```
GET  /customer-visits                      → Dashboard
GET  /customer-visits/select-customer      → Pilih customer
POST /customer-visits/check-in             → Start visit
GET  /customer-visits/active               → Active visit form
GET  /customer-visits/active/{id}          → Specific active visit
POST /customer-visits/check-out            → Complete visit
GET  /customer-visits/history              → Riwayat kunjungan
GET  /customer-visits/{id}                 → Detail kunjungan
GET  /customer-visits-customers            → List customer

# API Routes
GET  /api/customer-visits/search-customers → Search customer AJAX
POST /api/customer-visits/validate-location → Validate GPS AJAX
```

**Customer Management Routes (Admin/Manajemen):**

```
GET    /customers              → List customers
GET    /customers/create       → Form tambah
POST   /customers              → Save customer
GET    /customers/{id}         → Detail customer
GET    /customers/{id}/edit    → Form edit
PUT    /customers/{id}         → Update customer
DELETE /customers/{id}         → Delete customer
```

---

## 🎯 FITUR UTAMA YANG SUDAH TERSEDIA

### A. **GPS Tracking & Validation**

- ✅ Haversine formula untuk calculate distance
- ✅ Validate radius 50 meter dari lokasi customer
- ✅ Check-in & check-out dengan GPS coordinates
- ✅ Store GPS accuracy
- ✅ Reverse geocoding address (siap untuk Google Maps API)

### B. **Visit Management**

- ✅ Start visit (check-in)
- ✅ Complete visit (check-out)
- ✅ Visit duration calculation (otomatis)
- ✅ Visit purpose tracking
- ✅ Visit result (order success, follow up, rejected, dll)
- ✅ Order tracking (has_order, amount, notes)
- ✅ Customer feedback
- ✅ Problems/keluhan
- ✅ Next action & next visit plan
- ✅ Multi-photo documentation (max 5, 2MB each)

### C. **Target & Statistics**

- ✅ Monthly target setting
- ✅ Auto calculate actuals
- ✅ Achievement percentage
- ✅ Visit stats (today, this month, total)
- ✅ Conversion rate tracking
- ✅ Order success tracking

### D. **Customer Management**

- ✅ Customer CRUD (admin/manajemen)
- ✅ Customer assignment to marketing
- ✅ Customer categorization
- ✅ Customer GPS coordinates
- ✅ Customer visit statistics
- ✅ Search & filter customer

---

## 📋 YANG MASIH PERLU DIBUAT

### 1. **Views** (Belum ada)

Perlu dibuat file-file view:

```
app/views/customer-visits/
├── dashboard.php          → Dashboard marketing
├── select-customer.php    → Pilih customer
├── active-visit.php       → Form kunjungan aktif
├── history.php            → Riwayat kunjungan
├── detail.php             → Detail kunjungan
└── customers.php          → List customer

app/views/customers/
├── index.php              → List customer (admin)
├── create.php             → Form tambah customer
├── edit.php               → Form edit customer
└── show.php               → Detail customer
```

### 2. **JavaScript GPS Tracker** (Belum ada)

```javascript
// File: assets/js/gps-tracker.js
// - navigator.geolocation.getCurrentPosition()
// - Validate GPS accuracy
// - Show loading indicator
// - Handle GPS errors
// - Photo capture & compress
```

### 3. **Mobile-Friendly CSS** (Belum ada)

```css
/* File: assets/css/components/customer-visits.css */
/* - Mobile-first design
   - Touch-friendly buttons
   - GPS status indicators
   - Map display
   - Photo preview
   - Progress indicators */
```

### 4. **Module Registration** (Belum ada)

Perlu tambah ke database `modules`:

```sql
INSERT INTO modules (caption, logo, link, admin, manajemen, user, marketing, customer)
VALUES
('Customer Visits', 'fas fa-map-marked-alt', '/customer-visits', 0, 1, 0, 1, 0),
('Customer Management', 'fas fa-users-cog', '/customers', 1, 1, 0, 0, 0);
```

---

## 🚀 NEXT STEPS - CARA IMPLEMENTASI

### Step 1: Import Database ✅ SELESAI

```bash
# Login ke MySQL dan import
mysql -u root -p template < database/migrations/create_customer_visit_tables.sql
```

### Step 2: Test Routing

```bash
# Test di browser/Postman:
GET http://localhost/template/customer-visits
# Harus ada (meskipun belum ada view)
```

### Step 3: Buat Views (TODO)

- Dashboard marketing
- Form check-in
- Form check-out
- History & detail

### Step 4: JavaScript GPS (TODO)

- GPS detector
- Location validator
- Photo handler

### Step 5: Register Module (TODO)

- Tambah ke `modules` table
- Assign ke menu group
- Set permissions

---

## 📱 TEKNOLOGI YANG DIGUNAKAN

### Backend:

- ✅ PHP 8+ dengan PDO
- ✅ MVC Framework (custom)
- ✅ GPS calculation (Haversine)
- ✅ Transaction management
- ✅ File upload handler

### Frontend (Coming):

- 📱 HTML5 Geolocation API
- 📷 Camera API (MediaDevices)
- 🗺️ Leaflet.js atau Google Maps
- 📊 Chart.js untuk dashboard
- 🎨 Bootstrap 5 responsive

---

## 🔒 SECURITY FEATURES

- ✅ CSRF token protection
- ✅ Session validation
- ✅ Role-based access control
- ✅ SQL injection prevention (prepared statements)
- ✅ File upload validation (type, size)
- ✅ GPS data validation
- ✅ User ownership validation

---

## 📊 DATABASE RELATIONSHIPS

```
users (marketing)
  ├── customers (assigned_marketing_id)
  │   └── customer_visits (customer_id)
  │       └── photos (JSON array)
  └── customer_visits (marketing_id)
  └── visit_targets (marketing_id)
```

---

## 🎯 WORKFLOW

### Marketing User:

1. Login → Dashboard (lihat target & today visits)
2. Klik "Mulai Kunjungan"
3. Pilih customer dari list
4. Allow GPS → Check-in (validate radius 50m)
5. Kunjungan berlangsung...
6. Selesai → Fill form checkout
7. Upload foto dokumentasi
8. Submit → Data tersimpan
9. Stats & target auto-update

### Admin/Manajemen:

1. Login → Dashboard monitoring
2. Lihat semua visits (map view)
3. Lihat reports & analytics
4. Manage customers
5. Set targets per marketing

---

## 📞 SUPPORT & TROUBLESHOOTING

### GPS Not Working:

- Check HTTPS (GPS butuh secure connection)
- Check browser permissions
- Check GPS hardware aktif di device

### Upload Failed:

- Check folder permissions (755)
- Check max upload size di php.ini
- Check file type & size

### Distance Validation Failed:

- Check customer GPS coordinates di database
- Check radius setting (default 50m)
- Manual override jika emergency

---

**Status**: Backend 100% Complete ✅
**Next**: Views & JavaScript Implementation
**Timeline**: Siap untuk testing setelah views dibuat

---

Dibuat: 22 Oktober 2025
Developer: AI Assistant
Project: Customer Visit Tracking System for Marketing Team
