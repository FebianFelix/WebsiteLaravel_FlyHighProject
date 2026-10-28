# FlyHigh — Laravel Flight Booking Portfolio

FlyHigh adalah aplikasi portfolio full-stack untuk **reservasi penerbangan simulasi**, dikembangkan ulang dari project mahasiswa tahun 2023. Versi ini memakai **Laravel 13 (PHP 8.3+)**, Blade, Eloquent, session authentication, MySQL/SQLite, dan UI responsif.

> **Bukan platform penerbangan sungguhan.** Jadwal, inventori, dan harga adalah data demo; aplikasi tidak mengakses API maskapai, menarik pembayaran, atau menerbitkan tiket resmi.

## Fitur

- Homepage baru + pencarian penerbangan berdasarkan asal, tujuan, dan tanggal.
- Detail penerbangan, ketersediaan kursi simulasi, dan harga transparan.
- Registrasi, login dan logout melalui Laravel Auth, dengan session regeneration dan throttle.
- Reservasi 1–6 penumpang menggunakan transaksi database dan penguncian inventori MySQL.
- Dashboard reservasi pribadi, detail, cetak ringkasan, dan pembatalan.
- Panel admin dilindungi role untuk mengelola jadwal dan memantau seluruh reservasi.
- Migration lengkap untuk users, flights, bookings, passengers + flight demo seeder.
- Pengujian feature untuk akses, autentikasi, inventory dan reservasi.

## Instalasi lokal

Persyaratan: **PHP 8.3+**, Composer 2, ekstensi PHP yang dibutuhkan Laravel 13, dan MySQL 8+ (atau SQLite).

```bash
git clone https://github.com/FebianFelix/WebsiteLaravel_FlyHighProject.git
cd WebsiteLaravel_FlyHighProject
composer install
cp .env.example .env
php artisan key:generate
```

Konfigurasi database pada `.env`. Untuk MySQL, buat database kosong `flyhigh` dan sesuaikan `DB_USERNAME` serta `DB_PASSWORD`. Untuk SQLite:

```dotenv
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/your/project/database/database.sqlite
```

Buat file SQLite terlebih dahulu. Kemudian jalankan:

```bash
php artisan migrate --seed
php artisan serve
```

Buka **http://localhost:8000**. Buat akun melalui halaman Daftar. Untuk mendapatkan akses admin:

```bash
php artisan flyhigh:make-admin your-email@example.com
```

Perintah ini membutuhkan konfirmasi interaktif dan hanya dapat dijalankan dari CLI oleh operator server.

## Pengujian

```bash
php artisan test
```

Test memakai SQLite in-memory via `phpunit.xml`, tidak menggunakan database pengembangan.

## Deploy (persiapan)

1. Set PHP 8.3+, install dependencies melalui `composer install --no-dev --optimize-autoloader`.
2. Set document root ke direktori `public/`, bukan root repository.
3. Set `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://domain-anda`, `APP_KEY` baru, dan database credential khusus.
4. Jalankan `php artisan migrate --force`, serta `php artisan config:cache` dan `php artisan route:cache`.
5. Gunakan HTTPS, permission storage/bootstrap/cache yang sesuai, dan backup database rutin.

### Migrasi dari versi lama

Struktur data v1 (`tbl_user`, `tbl_ticket`, dll.) **tidak otomatis dipindahkan** ke skema v2; script migrasi data harus disusun setelah tersedia dump SQL lama. Jangan menjalankan perubahan ini di atas database lama yang berisi data penting tanpa backup. File `.env` lama pernah tersimpan di Git history: semua secret/APP_KEY terdahulu harus dirotasi dan history dibersihkan secara terpisah jika repository akan tetap publik.

### Catatan pengelolaan dependencies

Repo lama meng-commit direktori `vendor/` dan `composer.lock` Laravel 10. Keduanya sengaja dikeluarkan dari source tree saat upgrade lintas versi. Jalankan `composer update` atau `composer install` untuk menghasilkan lock file sesuai Laravel 13 di mesin dengan akses Packagist. **Commit kembali `composer.lock` yang sudah terverifikasi** untuk deployment yang reproducible. Jalankan pengujian setelah instalasi; peningkatan ini belum tervalidasi di lingkungan production.

## License

MIT.
