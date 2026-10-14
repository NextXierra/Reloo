# Panduan Menjalankan Project Reloo

Panduan lengkap instalasi dan konfigurasi lokal untuk tim pengembang Reloo Marketplace.

---

## 1. Prasyarat Sistem (Prerequisites)

Pastikan lingkungan lokal Anda telah terpasang:
- **PHP >= 8.3**
  - Ekstensi yang wajib aktif: `pdo_sqlite`, `curl`, `mbstring`, `openssl`, `fileinfo`, `tokenizer`.
- **Composer >= 2.x**
- **Node.js** (v18.x atau v20.x+) & **NPM**
- **Git**

> *Catatan: Tidak diperlukan instalasi database server eksternal (MySQL/PostgreSQL) karena sistem secara default telah dikonfigurasi menggunakan **SQLite**.*

---

## 2. Langkah Instalasi Cepat

Jalankan perintah berikut secara berurutan pada terminal:

### a. Clone Repository
```bash
git clone https://github.com/NextXierra/Reloo.git
cd Reloo
```

### b. Install Dependensi PHP (Composer)
```bash
composer install
```

### c. Konfigurasi Environment File
Salin file template environment ke `.env`:
```bash
# macOS / Linux / Git Bash:
cp .env.example .env

# Windows PowerShell:
Copy-Item .env.example .env
```

### d. Generate Application Key
```bash
php artisan key:generate
```

### e. Siapkan Database SQLite
Buat file database kosong:
```bash
# macOS / Linux / Git Bash:
touch database/database.sqlite

# Windows PowerShell:
New-Item database/database.sqlite -ItemType File -Force
```

### f. Jalankan Migrasi & Database Seeder
Perintah ini akan membuat skema database serta menyuntikkan data katalog produk, kategori, alamat default, dan akun demo:
```bash
php artisan migrate --seed
```

### g. Tautkan Direktori Storage
Untuk memastikan gambar produk dan aset publik dapat diakses browser:
```bash
php artisan storage:link
```

### h. Install Dependensi Frontend & Compile Aset
```bash
npm install
npm run build
```

---

## 3. Menjalankan Server Lokal

Jalankan server aplikasi dan asset watcher sekaligus:
```bash
composer run dev
```

Aplikasi dapat langsung diakses melalui browser:
- **URL Aplikasi**: [http://localhost:8000](http://localhost:8000)

*(Alternatif: buka dua tab terminal terpisah dan jalankan `php artisan serve` pada tab 1 dan `npm run dev` pada tab 2).*

---

## 4. Akun Pengguna Demo

Gunakan akun berikut untuk menguji berbagai modul sistem:

| Peran (Role) | Email | Password | Kegunaan & Akses |
|---|---|---|---|
| **Admin** | `admin@reloo.id` | `password` | Akses panel admin, validasi pesanan, request pickup kurir, cetak resi/label pengiriman (`/admin/orders`) |
| **Customer** | `user@reloo.id` | `password` | Eksplorasi katalog, keranjang belanja, checkout berfitur ongkir real-time, dan cek status pesanan (`/catalog`, `/checkout`) |

---

## 5. Integrasi Layanan Pihak Ketiga (Sandbox)

Semua kredensial sandbox sudah terkonfigurasi di file `.env.example`:

1. **RajaOngkir API (Komerce ID)**:
   - Pencarian kecamatan / kota tujuan pengiriman secara dinamis (auto-complete).
   - Perhitungan tarif ongkir domestik live dari multi-kurir (JNE, J&T, SiCepat, dll).
2. **Komerce Delivery Order**:
   - Pembuatan tiket order kurir, penjadwalan pickup kurir, cetak label resi (airway bill), dan live tracking riwayat paket.
3. **QRISly Payment Gateway**:
   - Simulasi pembayaran checkout QRIS sandbox.

---

## 6. Verifikasi Test Suite

Untuk memastikan seluruh alur sistem dan integrasi berjalan tanpa error:
```bash
php artisan test
```
*Seluruh unit & feature tests (termasuk validasi checkout, kalkulasi shipping, dan webhook delivery) harus berstatus PASSED.*
