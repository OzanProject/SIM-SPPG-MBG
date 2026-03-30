# SIM-SPPG-MBG (MBG AkunPro)

[![Laravel Version](https://img.shields.io/badge/Laravel-13.x-red.svg)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-8.3-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

**SIM-SPPG-MBG** (Sistem Informasi Manajemen Strategis SPPG) adalah platform SaaS (Software as a Service) berbasis **Manual Multi-Tenancy** yang dirancang khusus untuk mengelola operasional jaringan dapur dalam program **Makan Bergizi Gratis (MBG)**. 

Platform ini memungkinkan integrasi data yang kuat antara manajemen pusat (Super Admin) dan operasional harian di tingkat cabang dapur (Tenant), mencakup aspek keuangan, logistik, SDM, hingga dokumentasi distribusi.

---

## 🚀 Fitur Utama

### 🛠️ Super Admin (Manajemen Pusat)
*   **Multi-Tenant Management**: Pendaftaran dan kontrol penuh terhadap entitas dapur cabang.
*   **Subscription & Feature Gating**: Pengaturan paket langganan (Free, Lite, Pro) dengan kontrol akses fitur dinamis bagi setiap tenant.
*   **Landing Page CMS**: Manajemen konten landing page secara real-time (Header, Features, Testimonials, FAQ, dll).
*   **Billing & Invoice System**: Pelacakan pembayaran langganan dan notifikasi tagihan otomatis.
*   **Global Audit Logs**: Pemantauan aktivitas keamanan dan log transaksi di seluruh sistem.
*   **Database Backup**: Fitur pencadangan data otomatis untuk menjamin keamanan aset informasi.

### 🍳 Tenant / Dashboard Dapur (Operasional)
*   **Accounting Full (Double-Entry)**: Manajemen Bagan Akun (COA), Jurnal Umum, Buku Besar, hingga Laporan Neraca dan Laba Rugi.
*   **Budget Management**: Perencanaan anggaran bulanan dan fitur monitoring realisasi anggaran secara real-time.
*   **Inventory & Recipe (BOM)**: Pengelolaan stok bahan baku dan kalkulasi biaya resep (Bill of Materials).
*   **POS & Penjualan**: Sistem kasir sederhana untuk mencatat transaksi harian.
*   **Distribusi Menu Circle (MBG)**: Modul khusus untuk perencanaan porsi makan harian dan dokumentasi foto distribusi ke sekolah/penerima.
*   **Procurement**: Manajemen supplier dan pembuatan Purchase Order (PO) yang terintegrasi dengan inventori.
*   **HR & Payroll**: Manajemen data karyawan dan perhitungan penggajian otomatis.
*   **Support Center**: Sistem tiket dukungan terintegrasi untuk komunikasi dengan tim teknis pusat.

---

## 🛠️ Tech Stack

*   **Core Framework**: Laravel 13.x
*   **Architecture**: Manual Multi-Tenancy (Path-based) with `stancl/tenancy`
*   **Database**: MySQL 8.x (Central User Pool & Isolated Tenant Databases)
*   **UI Dashboard**: AdminLTE 3 (Bootstrap 4)
*   **Authentication**: multi-guard (Super Admin vs Tenant Users)
*   **Permissions**: Spatie Laravel-Permission
*   **Exports**: Laravel Excel & Barryvdh DomPDF

---

## 📥 Panduan Instalasi

### Prasyarat
*   PHP >= 8.3
*   Composer
*   MySQL 8.x
*   Node.js & NPM

### Langkah-langkah
1.  **Clone Repositori**
    ```bash
    git clone https://github.com/OzanProject/SIM-SPPG-MBG.git
    cd SIM-SPPG-MBG
    ```

2.  **Instalasi Dependency**
    ```bash
    composer install
    npm install && npm run build
    ```

3.  **Konfigurasi Environment**
    ```bash
    cp .env.example .env
    # Sesuaikan konfigurasi DB_DATABASE (pusat) dan DB_USERNAME/PASSWORD
    php artisan key:generate
    ```

4.  **Migrasi Database**
    ```bash
    # Migrasi tabel pusat
    php artisan migrate
    
    # Menjalankan seeder awal (Super Admin & Data Dasar)
    php artisan db:seed
    ```

5.  **Jalankan Server**
    ```bash
    php artisan serve
    ```

---

## 🏗️ Arsitektur Tenancy
Aplikasi ini menggunakan pendekatan **Path-based Identification**. 
*   **Central**: `http://localhost:8000/super-admin`
*   **Tenant**: `http://localhost:8000/{tenant_id}/dashboard`

Setiap tenant memiliki database yang terisolasi secara fisik, namun berbagi pool user di database pusat untuk mendukung skalabilitas login global.

---

## 📝 Lisensi
Proyek ini dilisensikan di bawah [MIT License](LICENSE).

## 👨‍💻 Developer
Dikembangkan oleh **OzanProject** - [ozanproject.site](https://www.ozanproject.site/)
