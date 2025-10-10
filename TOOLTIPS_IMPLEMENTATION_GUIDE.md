# 📘 PANDUAN IMPLEMENTASI TOOLTIPS - MINIMAL CLEAN STYLE

**Tanggal:** 10 Oktober 2025  
**Style:** Minimal Clean - Professional & Modern  
**Status:** ✅ Implemented & Ready to Use

---

## ✅ **YANG SUDAH DIIMPLEMENTASI**

### **1. CSS File Created**

📁 **File:** `assets/css/components/tooltips.css`

- ✅ Minimal clean design
- ✅ White background dengan subtle shadow
- ✅ Dark theme support
- ✅ Responsive untuk mobile
- ✅ Smooth animations
- ✅ Color variants (success, warning, error, info)

### **2. CSS Included in Layout**

📁 **File:** `app/views/layouts/app.php` (Line 62)

```html
<link
  href="<?php echo BASE_URL; ?>assets/css/components/tooltips.css"
  rel="stylesheet"
/>
```

### **3. JavaScript Initialization**

📁 **File:** `assets/js/app.js` (Lines 1107-1212)

- ✅ Auto-initialize pada DOMContentLoaded
- ✅ Auto-cleanup untuk dynamic content
- ✅ Function `refreshTooltips()` untuk AJAX content
- ✅ Customizable delay, placement, dan options

---

## 🎯 **CARA MENGGUNAKAN**

### **CONTOH 1: Basic Tooltip**

**Ubah dari:**

```html
<button title="Delete User">
  <i class="fas fa-trash-can"></i>
</button>
```

**Menjadi:**

```html
<button data-bs-toggle="tooltip" data-bs-title="Hapus User">
  <i class="fas fa-trash-can"></i>
</button>
```

---

### **CONTOH 2: Tooltip dengan Custom Placement**

```html
<!-- Top (default) -->
<button
  data-bs-toggle="tooltip"
  data-bs-placement="top"
  data-bs-title="Hapus User"
>
  <i class="fas fa-trash"></i>
</button>

<!-- Bottom -->
<button
  data-bs-toggle="tooltip"
  data-bs-placement="bottom"
  data-bs-title="Edit User"
>
  <i class="fas fa-pencil"></i>
</button>

<!-- Left -->
<button
  data-bs-toggle="tooltip"
  data-bs-placement="left"
  data-bs-title="Lihat Detail"
>
  <i class="fas fa-eye"></i>
</button>

<!-- Right -->
<button
  data-bs-toggle="tooltip"
  data-bs-placement="right"
  data-bs-title="Download"
>
  <i class="fas fa-download"></i>
</button>
```

---

### **CONTOH 3: Tooltip dengan Accessibility (RECOMMENDED)**

```html
<button
  data-bs-toggle="tooltip"
  data-bs-title="Hapus User"
  aria-label="Hapus User"
>
  <i class="fas fa-trash-can" aria-hidden="true"></i>
</button>
```

**Kenapa perlu `aria-label`?**

- Untuk screen readers (aksesibilitas)
- Tooltip tidak bisa dibaca oleh screen readers
- `aria-label` memberikan alternatif text untuk pengguna dengan disabilitas

---

### **CONTOH 4: Tooltip pada Link**

```html
<a
  href="/users/123"
  data-bs-toggle="tooltip"
  data-bs-title="Lihat profil lengkap"
>
  John Doe
</a>
```

---

### **CONTOH 5: Tooltip dengan Icon**

```html
<div data-bs-toggle="tooltip" data-bs-title="Dokumen sudah diverifikasi">
  <i class="fas fa-check-circle text-success"></i>
</div>
```

---

### **CONTOH 6: Text Truncate dengan Tooltip**

```html
<div
  class="fw-bold text-truncate"
  style="max-width: 200px;"
  data-bs-toggle="tooltip"
  data-bs-title="Nama Lengkap Yang Sangat Panjang Sekali"
>
  Nama Lengkap Yang Sangat Panjang Sekali
</div>
```

---

## 🎨 **COLOR VARIANTS (OPTIONAL)**

Untuk tooltip dengan warna berbeda, tambahkan class:

```html
<!-- Success (green) -->
<button
  data-bs-toggle="tooltip"
  data-bs-title="Data berhasil disimpan"
  class="tooltip-success"
>
  <i class="fas fa-check"></i>
</button>

<!-- Warning (yellow) -->
<button
  data-bs-toggle="tooltip"
  data-bs-title="Peringatan: Data akan terhapus"
  class="tooltip-warning"
>
  <i class="fas fa-exclamation-triangle"></i>
</button>

<!-- Error (red) -->
<button
  data-bs-toggle="tooltip"
  data-bs-title="Error: Gagal menyimpan data"
  class="tooltip-error"
>
  <i class="fas fa-times-circle"></i>
</button>

<!-- Info (blue) -->
<button
  data-bs-toggle="tooltip"
  data-bs-title="Informasi tambahan tersedia"
  class="tooltip-info"
>
  <i class="fas fa-info-circle"></i>
</button>
```

**Note:** Class ini ditambahkan ke element trigger (button/link), bukan ke tooltip itu sendiri.

---

## 🔄 **UNTUK DYNAMIC CONTENT (AJAX)**

Jika Anda load content via AJAX, panggil `refreshTooltips()` setelah content di-render:

```javascript
// Setelah AJAX success
fetch("/api/users")
  .then((response) => response.json())
  .then((data) => {
    // Render content
    document.getElementById("user-list").innerHTML = renderUsers(data);

    // Refresh tooltips ✅
    window.refreshTooltips();
  });
```

---

## 📋 **CHECKLIST UPDATE FILE VIEWS**

Berikut file-file yang perlu diupdate:

### **✅ PRIORITAS TINGGI (User-facing)**

- [ ] `app/views/users/index.php`
- [ ] `app/views/modules/index.php`
- [ ] `app/views/callcenter/index.php`
- [ ] `app/views/menu/menu-builder.php`
- [ ] `app/views/messages/create.php`
- [ ] `app/views/components/header.php`

### **✅ PRIORITAS SEDANG (Forms)**

- [ ] `app/views/users/create.php`
- [ ] `app/views/users/edit.php`
- [ ] `app/views/auth/register.php`
- [ ] `app/views/konfigurasi/index.php`

### **✅ PRIORITAS RENDAH (Detail Pages)**

- [ ] `app/views/users/show.php`
- [ ] `app/views/modules/show.php`
- [ ] `app/views/callcenter/show.php`

---

## 🔍 **CONTOH UPDATE SPESIFIK**

### **File: app/views/users/index.php**

**BEFORE (Line 28):**

```html
<button
  type="button"
  class="btn btn-secondary"
  id="searchToggleBtn"
  title="Search"
>
  <i class="fas fa-search" id="searchIcon"></i>
</button>
```

**AFTER:**

```html
<button
  type="button"
  class="btn btn-secondary"
  id="searchToggleBtn"
  data-bs-toggle="tooltip"
  data-bs-title="Cari"
  aria-label="Cari"
>
  <i class="fas fa-search" id="searchIcon" aria-hidden="true"></i>
</button>
```

---

**BEFORE (Line 199):**

```html
<button
  type="button"
  class="btn btn-sm btn-outline-danger btn-action"
  onclick="deleteUser(<?php echo $user['id']; ?>)"
  title="Delete User"
>
  <i class="fas fa-trash-can"></i>
</button>
```

**AFTER:**

```html
<button
  type="button"
  class="btn btn-sm btn-outline-danger btn-action"
  onclick="deleteUser(<?php echo $user['id']; ?>)"
  data-bs-toggle="tooltip"
  data-bs-placement="top"
  data-bs-title="Hapus User"
  aria-label="Hapus User"
>
  <i class="fas fa-trash-can" aria-hidden="true"></i>
</button>
```

---

### **File: app/views/auth/register.php**

**BEFORE (Line 162):**

```html
<button
  type="button"
  class="btn btn-sm btn-danger remove-preview"
  onclick="removePreview()"
  title="Remove"
>
  <i class="fas fa-times"></i>
</button>
```

**AFTER:**

```html
<button
  type="button"
  class="btn btn-sm btn-danger remove-preview"
  onclick="removePreview()"
  data-bs-toggle="tooltip"
  data-bs-title="Hapus"
  aria-label="Hapus gambar"
>
  <i class="fas fa-times" aria-hidden="true"></i>
</button>
```

---

## ⚙️ **CUSTOMIZATION OPTIONS**

Jika ingin mengubah default behavior, edit `assets/js/app.js` di function `initTooltips()`:

```javascript
// Line 1123-1126
delay: {
    show: 500,  // ← Ubah delay sebelum muncul (ms)
    hide: 100,  // ← Ubah delay sebelum hilang (ms)
},
```

```javascript
// Line 1127
placement: "top",  // ← Ubah default position: top, bottom, left, right
```

---

## 🎯 **BEST PRACTICES**

### **✅ DO:**

1. Gunakan tooltip untuk memberikan informasi tambahan
2. Keep text short (max 2-3 baris)
3. Gunakan `aria-label` untuk accessibility
4. Set `aria-hidden="true"` pada icon
5. Gunakan `data-bs-placement` untuk kontrol posisi
6. Test di mobile dan desktop
7. Panggil `refreshTooltips()` setelah AJAX load

### **❌ DON'T:**

1. Jangan gunakan tooltip untuk informasi critical
2. Jangan gunakan HTML dalam tooltip (security risk)
3. Jangan nested tooltips
4. Jangan tooltip pada element disabled
5. Jangan tooltip terlalu panjang
6. Jangan lupa cleanup saat element dihapus (sudah auto)

---

## 🧪 **TESTING CHECKLIST**

Setelah update, test:

- [ ] Tooltip muncul saat hover
- [ ] Tooltip hilang saat mouse keluar
- [ ] Tooltip muncul di posisi yang benar
- [ ] Tooltip tidak keluar dari viewport
- [ ] Tooltip bekerja di mobile (touch)
- [ ] Tooltip bekerja dengan keyboard (focus)
- [ ] Dark theme support
- [ ] Animation smooth
- [ ] Tidak ada error di console
- [ ] Performance tetap baik

---

## 📊 **PREVIEW TAMPILAN**

```
┌─────────────────┐
│  Hapus User     │ ← White background
│                 │ ← Subtle shadow
└─────▼───────────┘ ← Small arrow
   ↑
  Button
```

**Karakteristik:**

- 🎨 White background (#ffffff)
- 🔲 Border: 1px solid #e5e7eb
- ✨ Shadow: 0 4px 12px rgba(0,0,0,0.08)
- 📏 Padding: 0.5rem 0.875rem
- 🔄 Border radius: 8px
- 📝 Font: 0.8125rem, weight 500
- 🌑 Dark theme: #1f2937 background

---

## 🚀 **QUICK START**

1. **Refresh browser** untuk load CSS baru (Ctrl+Shift+R)
2. **Pilih file** yang ingin diupdate dari checklist
3. **Find & Replace:**
   - Cari: `title="`
   - Ganti dengan: `data-bs-toggle="tooltip" data-bs-title="`
4. **Tambahkan** `aria-label` untuk accessibility
5. **Test** di browser
6. **Repeat** untuk file lainnya

---

## 💡 **TIPS**

- Untuk efisiensi, gunakan find & replace di IDE
- Update file-file prioritas tinggi terlebih dahulu
- Test setiap file setelah update
- Jika ada masalah, check console untuk error
- Dokumentasikan perubahan yang Anda buat

---

## 📞 **SUPPORT**

Jika ada masalah atau pertanyaan:

1. Check console browser untuk error
2. Pastikan Bootstrap 5 sudah loaded
3. Pastikan `tooltips.css` sudah di-include
4. Pastikan `app.js` sudah di-load
5. Clear cache browser

---

**Dibuat oleh:** AI Assistant  
**Tanggal:** 10 Oktober 2025  
**Status:** ✅ Ready to implement

---

**SELAMAT MENGGUNAKAN TOOLTIPS! 🎉**
