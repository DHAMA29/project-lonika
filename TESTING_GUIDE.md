# Testing Manual untuk Sistem Stock Management Rental

## 1. Testing Database Schema

### Cek apakah kolom baru sudah ada

```sql
DESCRIBE peminjaman;
```

Pastikan kolom berikut ada:

-   `stock_reserved` (tinyint)
-   `stock_deducted` (tinyint)
-   `stock_returned` (tinyint)
-   `stock_deduction_date` (timestamp)
-   `stock_return_date` (timestamp)

## 2. Testing StockAvailabilityService

### Test 1: Availability Checking

```bash
# Jalankan command artisan untuk test service
php artisan tinker

# Di dalam tinker:
$service = new App\Services\StockAvailabilityService();

# Test basic availability
$result = $service->checkAvailability(1, '2025-09-25', '2025-09-27', 1);
print_r($result);
```

### Test 2: Overlapping Rentals

1. Buat peminjaman A: 25-27 September
2. Cek availability untuk 26-28 September
3. Harus menunjukkan conflict/reduced availability

## 3. Testing Booking Process

### Test 3: Immediate Stock Reservation (Bukan Deduction)

1. Buat booking baru dengan tanggal masa depan
2. Cek database: `stock_reserved = 1`, `stock_deducted = 0`
3. Cek stok barang: seharusnya TIDAK berubah

```sql
-- Cek stok sebelum booking
SELECT stok FROM barang WHERE id = [ID_BARANG];

-- Lakukan booking

-- Cek lagi stok setelah booking
SELECT stok FROM barang WHERE id = [ID_BARANG];
-- Stok harus SAMA

-- Cek status peminjaman
SELECT stock_reserved, stock_deducted, stock_returned FROM peminjaman WHERE id = [ID_PEMINJAMAN];
-- stock_reserved = 1, stock_deducted = 0, stock_returned = 0
```

## 4. Testing Automated Stock Management

### Test 4: Manual Stock Deduction

```bash
# Test command manual
php artisan rental:manage-stock

# Untuk test dengan data masa lalu, buat peminjaman dengan tanggal_pinjam = hari ini
```

### Test 5: Date-based Stock Operations

1. Buat data test dengan tanggal yang sudah lewat
2. Jalankan command
3. Verifikasi stock deduction/return

## 5. Testing Availability API

### Test 6: AJAX Availability Check

```javascript
// Test di browser console
fetch("/peminjaman/check-availability", {
    method: "POST",
    headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
            .content,
    },
    body: JSON.stringify({
        barang_id: 1,
        tanggal_pinjam: "2025-09-25",
        tanggal_kembali: "2025-09-27",
        quantity: 1,
    }),
})
    .then((r) => r.json())
    .then(console.log);
```

## 6. Testing Edge Cases

### Test 7: Conflicting Bookings

1. Buat booking A: 25-27 September
2. Coba buat booking B: 26-28 September untuk barang sama
3. Sistem harus menolak atau mengurangi available quantity

### Test 8: Stock Return

1. Buat booking dengan tanggal_kembali = kemarin
2. Jalankan rental:manage-stock
3. Cek apakah stok dikembalikan

## 7. Testing UI Integration

### Test 9: Checkout Validation

1. Tambah barang ke cart
2. Pilih tanggal yang conflict dengan booking existing
3. System harus menampilkan warning

### Test 10: Real-time Availability

1. Buka halaman product detail
2. Ubah tanggal booking
3. Cek apakah availability update secara real-time

## Expected Results Summary

| Test                 | Before System           | After System               |
| -------------------- | ----------------------- | -------------------------- |
| Booking created      | Stok langsung berkurang | Stok hanya reserved        |
| Rental starts        | -                       | Stok dikurangi otomatis    |
| Rental ends          | Manual return stok      | Stok dikembalikan otomatis |
| Availability check   | Basic stock count       | Smart date-based check     |
| Overlapping bookings | Allowed incorrectly     | Properly detected          |

## Verification Commands

```bash
# Cek semua peminjaman dengan status stock
SELECT id, kode_transaksi, tanggal_pinjam, tanggal_kembali, stock_reserved, stock_deducted, stock_returned FROM peminjaman ORDER BY created_at DESC LIMIT 10;

# Cek stok barang
SELECT id, nama, stok FROM barang WHERE id IN (SELECT DISTINCT barang_id FROM detail_peminjaman);

# Manual test stock operations
php artisan rental:manage-stock --force
```
