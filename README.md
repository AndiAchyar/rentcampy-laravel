# RentCampy (Laravel)

Aplikasi sewa perlengkapan camping, versi **Laravel 12 + Blade + PostgreSQL**.
Ini adalah pindahan dari versi React + Express. Fiturnya sama: katalog, keranjang,
checkout dengan cek stok per tanggal, riwayat pesanan, dan panel admin
(ringkasan, kelola produk, peminjaman, kelola user).

## Kebutuhan

- PHP 8.2+ dengan ekstensi `pdo_pgsql`
- Composer
- PostgreSQL
- Node.js (hanya untuk build CSS/JS)

## Cara menjalankan pertama kali

```bash
composer install
npm install
npm run build

cp .env.example .env        # lalu sesuaikan DB_* dan ADMIN_*
php artisan key:generate

# Buat database dulu di PostgreSQL, contoh:
#   psql -U postgres -c "CREATE DATABASE rentcampy_laravel"
php artisan migrate --seed  # membuat tabel, akun admin, dan 30 produk contoh
php artisan storage:link    # agar gambar produk yang diunggah bisa tampil

php artisan serve
```

Buka http://127.0.0.1:8000. Kalau port 8000 sudah dipakai, jalankan
`php artisan serve --port=8765`.

Akun admin awal diambil dari `.env` (`ADMIN_EMAIL` / `ADMIN_PASSWORD`).
Nilai bawaannya `admin@gmail.com` / `admin123`. **Ganti sebelum dipakai sungguhan.**

Saat mengubah tampilan, jalankan `npm run dev` supaya CSS ikut diperbarui otomatis.

## Struktur penting

| Bagian | Lokasi |
|---|---|
| Route | `routes/web.php` |
| Controller publik | `app/Http/Controllers/` (Home, Cart, Order, Auth) |
| Controller admin | `app/Http/Controllers/Admin/` |
| Model | `app/Models/` (User, Product, RentalOrder, RentalOrderItem) |
| Keranjang (session) | `app/Support/Cart.php` |
| Helper `rupiah()`, `tanggal()` | `app/helpers.php` |
| Tampilan Blade | `resources/views/` |
| JavaScript (Alpine.js + SweetAlert2) | `resources/js/app.js` |
| Migrasi & data contoh | `database/migrations/`, `database/seeders/DatabaseSeeder.php` |

## Perbedaan dari versi React

- Login memakai **session Laravel** (bukan JWT di localStorage), dan role admin selalu dicek dari database.
- Keranjang disimpan di **session server**, jadi tetap ada setelah pengunjung login. Keranjang kosong lagi setelah logout.
- Gambar produk disimpan sebagai **file** di `storage/app/public/products`, bukan base64 di database.
- Kategori produk disimpan sebagai satu teks (`Tenda`, `Alat Tidur`, `Peralatan Masak`).
- Pencarian dan pagination di halaman admin diproses di server.
- Stok produk contoh diisi 5 unit (di versi lama 0, sehingga semua produk tampil "Stok habis").
