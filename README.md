# WarungWare

Aplikasi website manajemen warung berbasis PHP.

**Status:** Minimal / Basic demo (PHP + MySQL).  
**License:** GPL-3.0.

---

## Ringkasan
WarungWare adalah aplikasi web sederhana untuk membantu pengelolaan warung: pencatatan barang, penjualan, dan manajemen halaman administrasi. Dibangun menggunakan PHP (kode sisi server tradisional) dengan struktur file yang ringan sehingga mudah dipelajari, dikustomisasi, dan di-deploy pada server lokal (XAMPP, MAMP, LAMP) atau shared hosting.

---

## Fitur (dasar)
- Login/logout sederhana (autentikasi berbasis file PHP).
- Halaman manajemen barang / brand.
- Pencatatan penjualan (SQL example file disertakan).
- Upload file/gambar (folder `uploads`).
- Struktur halaman modular di folder `pages` / `includes`.

> Catatan: Fitur dan halaman lengkap dapat dilihat dari isi folder repo (PHP, assets, pages, dsb). :contentReference[oaicite:2]{index=2}

---

## Persyaratan
- PHP 7.0+ (direkomendasikan PHP 7.4 / 8.x tergantung kompatibilitas)
- MySQL / MariaDB
- Web server: Apache / Nginx (XAMPP / MAMP / LAMP cocok untuk development)
- Ekstensi PHP standar (mysqli / PDO jika perlu disesuaikan)

---

## Instalasi (lokal)
1. Clone repo:
   ```bash
   git clone https://github.com/FriendlyNuke/WarungWare.git
   cd WarungWare
