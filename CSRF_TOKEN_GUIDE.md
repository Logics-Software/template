# CSRF Token - PANDUAN PERMANEN

**Dokumen ini adalah HUKUM yang TIDAK BOLEH DILANGGAR!**

---

## ❌ JANGAN PERNAH LAKUKAN INI! (PENYEBAB ERROR 403)

### 1. **JANGAN generate token berkali-kali di view:**

```php
<!-- ❌ SALAH - Token bisa berbeda! -->
'X-CSRF-Token': '<?php echo Session::generateCSRF(); ?>'
...
_token: '<?php echo Session::generateCSRF(); ?>'
```

### 2. **JANGAN panggil generateCSRF() di inline PHP:**

```php
<!-- ❌ SALAH - Memanggil 2x! -->
<script>
const token = '<?php echo Session::generateCSRF(); ?>';
</script>
```

### 3. **JANGAN hardcode token di setiap fetch:**

```javascript
// ❌ SALAH - Rawan error
fetch(url, {
  headers: {
    "X-CSRF-Token": "manual-token-disini",
  },
});
```

---

## ✅ SELALU LAKUKAN INI! (SOLUSI BENAR)

### **UNIVERSAL SOLUTION: Gunakan `window.csrf` Helper**

#### **1. Token sudah di-set SATU KALI di layout:**

```php
<!-- app/views/layouts/app.php -->
<script>
    window.csrfToken = '<?php echo $csrf_token ?? ''; ?>';
</script>
```

#### **2. CsrfHelper sudah di-load:**

```html
<!-- app/views/layouts/app.php -->
<script src="assets/js/modules/CsrfHelper.js"></script>
```

#### **3. Gunakan window.csrf untuk semua request:**

**POST Request:**

```javascript
// ✅ BENAR - Auto handle token
window.csrf
  .post("/menu/delete-group", {
    id: groupId,
  })
  .then((response) => response.json())
  .then((data) => {
    // Handle response
  });
```

**PUT Request:**

```javascript
// ✅ BENAR
window.csrf
  .put("/users/update", {
    name: "John",
    email: "john@example.com",
  })
  .then((response) => response.json())
  .then((data) => {
    // Handle response
  });
```

**DELETE Request:**

```javascript
// ✅ BENAR
window.csrf
  .delete("/users/delete", {
    id: userId,
  })
  .then((response) => response.json())
  .then((data) => {
    // Handle response
  });
```

**FormData:**

```javascript
// ✅ BENAR - Tambah token ke FormData
const formData = new FormData(form);
window.csrf.addToFormData(formData);

fetch(url, {
  method: "POST",
  body: formData,
  headers: {
    "X-CSRF-Token": window.csrf.getToken(),
  },
});
```

---

## 🎯 Cara Kerja CsrfHelper

### **Otomatis menambahkan:**

1. ✅ Header `X-CSRF-Token`
2. ✅ Header `X-Requested-With: XMLHttpRequest`
3. ✅ Body `_token` field
4. ✅ Content-Type `application/json`

### **Single source of truth:**

```javascript
window.csrfToken → Set 1x di layout
       ↓
window.csrf → Helper class
       ↓
Semua request → Gunakan token yang sama
```

---

## 🔒 Validasi di Server Side

### **App.php sudah handle:**

```php
// Line 70-73
if (!$this->validateCSRF()) {
    $this->response->json(['error' => 'CSRF token mismatch'], 403);
    return;
}
```

### **Cek multiple sources:**

```php
// Line 225-228
$token = $this->request->input('_token')
        ?: $this->request->input('csrf_token')
        ?: $this->request->header('X-CSRF-Token')
        ?: $this->request->header('X-CSRF-TOKEN');
```

---

## 📋 Checklist Sebelum Request

### **Di JavaScript (View):**

- [ ] Gunakan `window.csrf.post()` / `put()` / `delete()`
- [ ] TIDAK ada `Session::generateCSRF()` di inline PHP
- [ ] TIDAK hardcode token manual
- [ ] Token diambil dari `window.csrfToken`

### **Di Controller (WAJIB!):**

- [ ] **SELALU** kirim `'csrf_token' => $this->csrfToken()` ke view
- [ ] Cek di method `$data` array sebelum `$this->view()`

**CONTOH BENAR:**

```php
public function index() {
    $data = [
        'title' => 'Page Title',
        'items' => $items,
        'csrf_token' => $this->csrfToken() // ← WAJIB!
    ];

    $this->view('template/page', $data);
}
```

---

## 🐛 Debugging

### **Jika masih error 403:**

1. **Cek token tersedia:**

```javascript
console.log("CSRF Token:", window.csrfToken);
```

2. **Cek header dikirim:**

```javascript
fetch(url, window.csrf.createFetchOptions("POST", data)).then((response) => {
  console.log("Status:", response.status);
  return response.json();
});
```

3. **Cek server logs:**

```php
// Di controller
error_log('Token received: ' . $this->request->input('_token'));
error_log('Session token: ' . Session::get('_csrf_token'));
```

---

## 💡 Best Practices

### **1. Single Token per Session:**

- Token di-generate 1x saat login
- Token di-refresh saat session refresh
- Token TIDAK berubah selama session aktif

### **2. Consistent Usage:**

- Selalu gunakan `window.csrf` helper
- Jangan pernah bypass dengan manual token

### **3. Error Handling:**

```javascript
window.csrf
  .post(url, data)
  .then((response) => {
    if (response.status === 403) {
      window.Notify.error("Session expired. Please refresh the page.");
      // Optional: Auto refresh after 2 seconds
      setTimeout(() => location.reload(), 2000);
      return;
    }
    return response.json();
  })
  .then((data) => {
    // Handle success
  })
  .catch((error) => {
    window.Notify.error("Request failed: " + error.message);
  });
```

---

## 🚀 Migration Guide

### **Update existing code:**

**BEFORE (❌ OLD WAY):**

```javascript
fetch(url, {
  method: "POST",
  headers: {
    "Content-Type": "application/json",
    "X-CSRF-Token": "<?php echo Session::generateCSRF(); ?>",
  },
  body: JSON.stringify({
    id: id,
    _token: "<?php echo Session::generateCSRF(); ?>",
  }),
});
```

**AFTER (✅ NEW WAY):**

```javascript
window.csrf.post(url, { id: id });
```

**Hemat 7 baris code + No more 403 errors!**

---

## ⚠️ INGAT SELAMANYA!

1. ❌ **JANGAN** generate token di view
2. ✅ **SELALU** gunakan `window.csrf` helper
3. ✅ Token dari `window.csrfToken` (di-set di layout)
4. ✅ Single source of truth
5. ✅ Consistent across all requests

**"ONE TOKEN TO RULE THEM ALL"**

---

**Dokumen ini adalah solusi PERMANEN untuk masalah CSRF 403 Forbidden!**
