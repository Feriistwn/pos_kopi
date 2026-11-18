# ☕ KopiPOS - Sistem Point of Sales & Manajemen Kedai Kopi

Aplikasi kasir (POS) modern berbasis web yang dirancang khusus untuk manajemen operasional kedai kopi. Dibangun dengan framework **Laravel 11**, styling responsif **Tailwind CSS v4**, dan antarmuka reaktif **Alpine.js** tanpa memerlukan reload halaman saat bertransaksi.

---

## 🌟 Fitur Unggulan

### 1. Multi-Role Autentikasi & Keamanan
- Role **Admin**: Akses penuh ke Dashboard metrik penjualan, Master Data Kategori, Master Data Produk, Laporan Transaksi, dan Layar POS.
- Role **Kasir**: Akses fokus ke Layar Kasir POS dan Riwayat Transaksi. Proteksi otomatis mengalihkan kasir dari rute admin.

### 2. Dashboard Ringkasan & Metrik Admin
- Total Omset / Penjualan Hari Ini (Rupiah).
- Total Pesanan Berhasil Hari Ini.
- Total Menu Aktif & Total Kategori.
- Widget peringatan otomatis **Stok Menipis** (stok &le; 10 porsi).
- Widget 5 transaksi terbaru.

### 3. Master Data (Kategori & Produk)
- **CRUD Kategori**: Pengelompokan menu (Kopi, Non-Kopi, Makanan, Snack) dengan proteksi relasi produk.
- **CRUD Produk**: Manajemen nama, kategori, harga rupiah, stok real-time, dan upload gambar menu ke storage disk.
- **Live Image Preview**: Pratinjau gambar instan sebelum form disubmit menggunakan FileReader Alpine.js.
- **Penghapusan Aman (Permanent Delete with Data Integrity)**: Produk yang dihapus dari master data tidak akan merusak struk transaksi lama (nama produk dan harga historis tetap tersimpan di riwayat transaksi).

### 4. Layar Kasir POS Reaktif (Frontend Alpine.js)
- **Katalog Menu Cepat**: Filter kategori menu berbasis tab pills dan pencarian menu instan.
- **Badge Stok**: Indikator stok aman, menipis, atau habis secara real-time.
- **Keranjang Belanja Interaktif**: Tambah item, ubah kuantitas (+/-), kosongkan keranjang, dan kalkulasi subtotal otomatis.
- **Metode Pembayaran**: Mendukung pembayaran **Tunai (Cash)** dengan tombol uang cepat (Uang Pas, 50.000, 100.000) beserta hitung kembalian otomatis, serta **QRIS**.
- **Keyboard Shortcut**: Tekan tombol **`F8`** untuk langsung membuka dialog pembayaran.
- **Pencegahan Overselling**: Pengecekan stok ganda (client-side & server-side dengan `lockForUpdate` database transaction).

### 5. Cetak Struk Belanja Thermal
- Desain struk belanja kasir ukuran 320px (font Courier Prime) yang rapi, lengkap dengan nama kedai, invoice, nama kasir, rincian menu, metode pembayaran, total bayar, dan media query khusus cetak (`window.print()`).

### 6. Laporan & Riwayat Transaksi Lengkap
- Aksesibel oleh **Admin** maupun **Kasir**.
- Filter multi-kriteria:
  - Rentang tanggal (`tanggal_mulai` & `tanggal_selesai`).
  - Dropdown filter per Kasir.
  - Dropdown filter metode pembayaran (Tunai / QRIS).
  - Pencarian nomor invoice (INV-YYYYMMDD-XXXXX).
- Rangkuman total pendapatan, transaksi, dan komposisi omset tunai vs QRIS secara instan.
- Tombol **Detail Faktur** dan pintasan **Cetak Ulang Struk**.

---

## 🛠️ Kebutuhan Sistem (Prerequisites)

Pastikan lingkungan server lokal Anda telah memenuhi spesifikasi berikut:
- **PHP** >= 8.2 (Disarankan PHP 8.3+)
  - Ekstensi wajib: `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `gd`, `curl`
- **Composer** >= 2.x
- **Node.js** >= 18.x & **NPM**
- **MySQL** / **MariaDB** (bisa menggunakan Laragon, XAMPP, atau MySQL Server bawaan)
- **Web Browser Modern** (Chrome, Firefox, Edge, Safari)

---

## 🚀 Panduan Instalasi Langkah-demi-Langkah

Ikuti langkah-langkah di bawah ini untuk menjalankan project dari awal di komputer Anda:

### 1. Clone Repository
```bash
git clone <URL_REPOSITORY_ANDA>
cd pos_kopi
```

### 2. Install Dependensi PHP (Composer)
```bash
composer install
```

### 3. Install Dependensi JavaScript (Node/NPM)
```bash
npm install
```

### 4. Salin File Konfigurasi Lingkungan (`.env`)
Salin file `.env.example` menjadi `.env`:
```bash
# Pengguna Linux / macOS / Git Bash:
cp .env.example .env

# Pengguna Windows Command Prompt:
copy .env.example .env
```

### 5. Generate Application Encryption Key
```bash
php artisan key:generate
```

### 6. Hubungkan Storage Symlink (Untuk Gambar Menu)
```bash
php artisan storage:link
```

### 7. Konfigurasi Database MySQL
Pastikan service MySQL Anda sudah berjalan, lalu buat database baru:
```sql
CREATE DATABASE pos_kopi;
```

Buka file `.env` di teks editor Anda dan sesuaikan kredensial database jika berbeda:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pos_kopi
DB_USERNAME=root
DB_PASSWORD=
```

### 8. Jalankan Migration & Database Seeder
Perintah ini akan membuat struktur tabel serta mengisinya dengan data awal (akun admin, kasir, 4 kategori menu, dan 10 produk sampel):
```bash
php artisan migrate:fresh --seed
```

### 9. Build Asset Frontend (Tailwind CSS v4 & Alpine.js)
```bash
npm run build
```
*(Opsional: Jika Anda ingin mengembangkan tampilan secara langsung dengan hot-reload, jalankan `npm run dev` di terminal terpisah).*

### 10. Jalankan Server Lokal
```bash
php artisan serve
```
Aplikasi sekarang dapat diakses melalui browser di: **`http://127.0.0.1:8000`**

---

## 🔐 Akun Pengujian Bawaan (Default Credentials)

Database seeder telah menyiapkan 2 akun siap pakai:

| Peran (Role) | Alamat Email | Kata Sandi | Halaman Utama Setelah Login |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@pos.com` | `password` | Dashboard Admin (`/`) |
| **Kasir** | `kasir@pos.com` | `password` | Layar Kasir POS (`/pos`) |

---

## 🧪 Menjalankan Pengujian Otomatis (Feature Tests)

Proyek ini telah dilengkapi dengan suite pengujian otomatis fitur lengkap (Dashboard, CRUD Kategori, CRUD Produk, Login Flow, Transaksi POS, dan Laporan):

```bash
php artisan test
```

Seluruh 17 skenario pengujian diuji dan diverifikasi lulus (100% PASS).

---

## 📁 Struktur Direktori Penting

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php             # Login & Logout logic
│   │   ├── CategoryController.php         # CRUD Kategori Menu
│   │   ├── DashboardController.php        # Metrik KPI Dashboard
│   │   ├── PosController.php              # POS Kasir, Checkout, & Struk
│   │   ├── ProductController.php          # CRUD Produk & Upload Foto
│   │   └── TransactionReportController.php# Riwayat & Filter Laporan
│   └── Middleware/
│       └── RoleMiddleware.php             # Proteksi hak akses multi-role
├── Models/
│   ├── Category.php
│   ├── Product.php
│   ├── Transaction.php
│   ├── TransactionDetail.php
│   └── User.php
database/
├── migrations/                            # Struktur tabel database
└── seeders/                               # Data awal (Users, Kategori, Produk)
resources/
└── views/
    ├── admin/                             # Tampilan Dashboard, Kategori, Produk, Transaksi
    ├── auth/                              # Tampilan Login
    ├── layouts/admin.blade.php            # Layout Master Admin
    ├── pos/                               # Layar Kasir POS & Cetak Struk
    └── vendor/pagination/                 # Tampilan kustom pagination Tailwind
routes/
└── web.php                                # Seluruh rute aplikasi
```

---

## 📄 Lisensi
Proyek ini dibuat untuk keperluan Point of Sales & manajemen kedai kopi di bawah lisensi [MIT](LICENSE).
