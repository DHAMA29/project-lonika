# Lonika Store - Marketplace Peminjaman Barang

## Sistem Login Tunggal dengan Role Management

Project ini telah berhasil dikonfigurasi dengan sistem login tunggal yang akan mengarahkan user berdasarkan role mereka setelah login:

### ✅ **FIXES APPLIED:**

1. **Database Schema Fixed**: Kolom `jenis_barang_id` sudah diperbaiki di migration dan model
2. **Single Login System**: Login Filament dihapus, menggunakan login tunggal dari Breeze
3. **Role-based Access**: Admin otomatis diarahkan ke Filament, User ke Marketplace

### Login Credentials

**Admin (Akses ke Filament Admin Panel):**

-   Email: `admin@lonika.com`
-   Password: `password`
-   Role: `admin`
-   Redirect: `/admin` (Filament Admin Panel - tanpa login terpisah)

**User (Akses ke Marketplace):**

-   Email: `user@lonika.com`
-   Password: `password`
-   Role: `user`
-   Redirect: `/peminjaman` (Marketplace)

**User Tambahan:**

-   Email: `john@example.com` / Password: `password`
-   Email: `jane@example.com` / Password: `password`

### Fitur yang Telah Diimplementasi

#### 1. **Sistem Login Tunggal**

-   Satu halaman login untuk semua user
-   Otomatis redirect berdasarkan role setelah login
-   Admin → Filament Admin Panel (tanpa login ulang)
-   User → Marketplace Peminjaman

#### 2. **Marketplace Peminjaman (seperti Shopee/ELS.ID)**

-   **Halaman Utama**: 15 produk dalam 5 kategori dengan filter
-   **Detail Produk**: Informasi lengkap dengan deskripsi dan add to cart
-   **Keranjang Belanja**: Manajemen item dengan perhitungan real-time
-   **Checkout**: Form lengkap dengan periode dan metode pembayaran
-   **Halaman Sukses**: Konfirmasi pesanan dengan detail lengkap
-   **Pesanan Saya**: Riwayat peminjaman user dengan status

#### 3. **Filament Admin Panel (Single Login)**

-   **Manajemen Jenis Barang**: CRUD kategori produk
-   **Manajemen Barang**: CRUD produk dengan stok dan gambar
-   **Manajemen Peminjam**: Data customer
-   **Manajemen Transaksi**: Monitor semua peminjaman
-   **Dashboard**: Widgets dan statistik

#### 4. **Database Schema Terperbaiki**

-   Kolom `jenis_barang_id` konsisten di migration dan model
-   Relasi antar model sudah benar
-   15 sample barang dalam 5 kategori (Kamera, Audio, Lighting, Drone, Komputer)

### Database Content

**Sample Data:**

-   **5 Jenis Barang**: Kamera, Audio, Lighting, Drone, Komputer
-   **15 Produk**:
    -   Kamera: Canon EOS R6, Sony A7 III, Nikon Z6 II, Fujifilm X-T4
    -   Audio: Mic Rode Wireless Go, Audio Technica AT2020, Shure SM7B
    -   Lighting: Godox SL-60W, Aputure AL-M9, Neewer 660 LED
    -   Drone: DJI Mavic Air 2, DJI Mini 3 Pro
    -   Komputer: MacBook Pro 16", iMac 24", iPad Pro 12.9"

### Struktur URL

```
/ → Login page atau redirect berdasarkan role
/login → Halaman login tunggal (Breeze)
/register → Halaman register
/admin → Filament Admin Panel (admin only, no separate login)
/peminjaman → Marketplace Home (user only)
/peminjaman/barang/{id} → Detail produk
/peminjaman/cart → Keranjang
/peminjaman/checkout → Proses checkout
/peminjaman/orders → Pesanan saya
```

### Cara Testing (Updated - Bug Fixed)

1. **Login sebagai Admin:**

    - Buka: `http://127.0.0.1:8000`
    - Login dengan: `admin@lonika.com` / `password`
    - Otomatis masuk ke Filament Admin Panel tanpa login ulang

2. **Test Logout & Login sebagai User:**

    - Logout dari admin panel menggunakan menu user di kanan atas
    - Session akan di-clear otomatis
    - Login dengan: `user@lonika.com` / `password`
    - Otomatis masuk ke Marketplace (TIDAK ke /admin lagi - BUG FIXED)

3. **Test Role Protection:**

    - Admin yang coba akses `/peminjaman` akan di-redirect ke `/admin`
    - User yang coba akses `/admin` akan di-redirect ke `/peminjaman`
    - No more 403 errors - proper redirects

4. **Test Marketplace Flow:**
    - Browse 15 produk di homepage
    - Filter berdasarkan 5 kategori
    - Klik detail produk dengan deskripsi lengkap
    - Tambah ke keranjang dengan quantity
    - Checkout dengan periode peminjaman
    - Lihat konfirmasi dan riwayat pesanan

### Error Fixes Applied

✅ **SQLSTATE[42S22]: Column not found: 'jenis_barang_id'** - FIXED

-   Migration table `barang` diperbaiki
-   Model relationship diperbaiki
-   Seeder data diperbaiki

✅ **Dual Login System** - REMOVED

-   Login Filament dihapus
-   Menggunakan satu login untuk semua user
-   Role-based redirect otomatis

✅ **403 Access Denied Bug** - FIXED

-   AdminMiddleware diperbaiki untuk redirect yang tepat (tidak lagi abort 403)
-   RedirectIfAuthenticated menggunakan `$user->role` bukan `$user->isAdmin()`
-   Session logout dibersihkan dengan benar
-   Role-based routing dengan middleware yang proper
-   User sekarang di-redirect dengan benar setelah logout

### File Penting

-   `database/migrations/2025_09_06_131526_create_barang_table.php` - Schema diperbaiki
-   `app/Models/Barang.php` - Relasi diperbaiki
-   `app/Providers/Filament/AdminPanelProvider.php` - Login tunggal
-   `app/Http/Middleware/AdminMiddleware.php` - Admin access control
-   `database/seeders/` - Data sample lengkap

### Server Status

**Running on**: `http://127.0.0.1:8000`

Project siap production dengan sistem login tunggal yang robust dan database schema yang konsisten.
