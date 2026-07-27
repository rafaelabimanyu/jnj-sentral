# Tech Stack & Environment Setup (Panduan Lingkungan Kerja) - J&J Sentral
**Kode Dokumen:** TSD-JNJ-SENTRAL-05  
**Versi:** 1.0.0  
**Tanggal:** 27 Juli 2026  
**Status:** Siap Ditinjau  
**Target Pembaca:** Tim Developer Inti & Anak Magang (PKL)  
**Pembuat:** Lead DevOps Engineer & Senior Technical Architect  

---

## 1. Spesifikasi Tech Stack Utama

Aplikasi internal **J&J Sentral** dikembangkan menggunakan kombinasi teknologi yang stabil, aman, dan mudah dipelihara. Pilihan teknologi ini dirancang agar dapat dijalankan dengan lancar di komputer lokal berspesifikasi standar.

### 1.1 Spesifikasi Server & Platform
*   **Backend:** PHP 8.2 atau 8.3 dengan framework **Laravel 11.x**.
*   **Database Engine:** **MySQL 8.0+** atau MariaDB 10.4+.
*   **Frontend Engine:** **Laravel Blade Templating** dipadukan dengan **Vite** sebagai *assets bundler*.
*   **CSS Framework:** **Tailwind CSS v3.x** (atau **Bootstrap 5.x** sebagai alternatif).
*   **Runtime Dependency Manager:**
    *   *PHP Package Manager:* **Composer v2.x**.
    *   *JS Package Manager:* **Node.js v20.x** (LTS) & **NPM v10.x**.

### 1.2 Identitas Desain Brand (Brand Identity Rules)
> [!IMPORTANT]
> **Palet Warna & Gaya Visual:**  
> Antarmuka aplikasi harus mengadopsi gaya *Industrial-Modern* yang mencerminkan karakteristik Rooterin (layanan mekanikal eco-plumbing pipa modern). Palet warna wajib yang digunakan adalah:
> - **Warna Navy (Latar Belakang / Navigasi / Elemen Kuat):** Hex `#0F2A44` (atau `bg-[#0F2A44]` di Tailwind).
> - **Warna Hijau (Aksen / Status / Tombol Utama):** Hex `#1FAF5A` (atau `text-[#1FAF5A]` / `bg-[#1FAF5A]` di Tailwind).
> - **Tipografi:** Menggunakan font modern tanpa berkaki seperti **Inter** atau **Outfit** untuk meningkatkan keterbacaan data numerik keuangan.

---

## 2. Panduan Setup Environment Lokal (Localhost)

Pengembangan lokal sangat disarankan menggunakan utilitas **Laragon** (untuk pengguna Windows). Laragon secara otomatis akan membuatkan *Virtual Host* server web lokal dan mengelola service MySQL tanpa konflik port.

### 2.1 Tahapan Instalasi Awal
Buka terminal (Git Bash, Command Prompt, atau terminal bawaan VS Code) di dalam folder root web server Laragon Anda (`C:\laragon\www\`), lalu jalankan perintah berikut:

```bash
# 1. Kloning Repositori dari GitHub
git clone https://github.com/rafaelabimanyu/jnj-sentral.git
cd jnj-sentral

# 2. Instalasi Dependensi Backend (PHP)
composer install

# 3. Instalasi Dependensi Frontend (JavaScript/CSS)
npm install
```

### 2.2 Konfigurasi Environment File (`.env`)
Salin file templat konfigurasi `.env.example` menjadi `.env`, lalu lakukan generate *application key* unik Laravel:

```bash
# Salin konfigurasi environment
cp .env.example .env

# Generate Application Key
php artisan key:generate
```

Buka berkas `.env` yang baru dibuat menggunakan editor teks Anda (misalnya VS Code) dan sesuaikan konfigurasi database Anda seperti di bawah ini:

```env
APP_NAME="J&J Sentral"
APP_ENV=local
APP_KEY=base64:GENERATE_KEY_SELESAI
APP_DEBUG=true
APP_URL=http://jj-sentral.test

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jnj_sentral
DB_USERNAME=root
DB_PASSWORD=
```

> [!NOTE]
> *Pastikan Anda telah membuat database kosong bernama `jnj_sentral` di MySQL menggunakan aplikasi administrasi database seperti **HeidiSQL** atau **phpMyAdmin** bawaan Laragon sebelum melanjutkan ke tahap migrasi.*

Jalankan migrasi database beserta pengisian data awal (*seeder*) menggunakan perintah:

```bash
# Migrasi tabel dan jalankan database seeder
php artisan migrate --seed
```

### 2.3 Menjalankan Aplikasi di Lokal
Terdapat dua cara untuk mengakses aplikasi di komputer lokal:

#### Opsi A: Menggunakan Virtual Host Laragon (Sangat Direkomendasikan)
1.  Buka aplikasi **Laragon**.
2.  Klik tombol **"Stop"** lalu klik **"Start All"** kembali untuk memicu deteksi folder baru oleh Laragon.
3.  Laragon akan mendeteksi folder `jnj-sentral` dan secara otomatis mendaftarkan domain lokal: [http://jj-sentral.test](http://jj-sentral.test).
4.  Buka browser Anda dan akses tautan tersebut.

#### Opsi B: Menggunakan Perintah Artisan Serve (Alternatif)
Jika Laragon tidak diinstal atau Anda menggunakan sistem operasi non-Windows, jalankan server bawaan Laravel:

```bash
# Menjalankan PHP local server
php artisan serve
```
Aplikasi kemudian dapat diakses pada alamat [http://127.0.0.1:8000](http://127.0.0.1:8000).

Untuk mengompilasi dan menjalankan aset CSS/JS secara dinamis selama pengembangan, buka terminal baru dan jalankan:

```bash
# Menjalankan Vite Development Server
npm run dev
```

---

## 3. Standar Kolaborasi & Git Workflow

Untuk mencegah terjadinya tumpang tindih kode (*conflict*) saat tim inti dan anak PKL melakukan pekerjaan secara paralel, aturan alur kerja Git berikut wajib ditaati:

### 3.1 Aturan Umum Git
*   **Dilarang Melakukan Commit Langsung ke Branch `main`:** Cabang `main` dilindungi dan hanya digunakan untuk kode produksi yang stabil. Seluruh pengerjaan fitur harus melalui proses *Pull Request* (PR).
*   **Selalu Lakukan Pull Sebelum Mulai Bekerja:** Biasakan memperbarui branch lokal Anda dengan repositori utama untuk meminimalkan potensi konflik.

### 3.2 Konvensi Penamaan Branch (Branch Naming Convention)
Sebelum mulai membuat kode, buatlah cabang baru dari `main` dengan format nama berikut:
*   Untuk fitur baru: `feature/nama-fitur` (contoh: `feature/tambah-pengeluaran`).
*   Untuk perbaikan bug: `bugfix/deskripsi-bug` (contoh: `bugfix/koreksi-kalkulasi-laba`).
*   Untuk optimasi/refaktor: `refactor/bagian-diubah` (contoh: `refactor/optimasi-query-dashboard`).

Alur kerja kolaborasi di terminal:
```bash
# 1. Pindah ke branch main
git checkout main

# 2. Tarik update terbaru
git pull origin main

# 3. Buat branch fitur baru
git checkout -b feature/tambah-pengeluaran

# ... Lakukan koding dan perubahan berkas ...

# 4. Stage dan Commit perubahan
git add .
git commit -m "feat: implementasi form pengeluaran operasional"

# 5. Push ke GitHub
git push origin feature/tambah-pengeluaran
```

---

## 4. Rekomendasi Struktur Direktori Kerja

Bagi anggota tim baru (khususnya anak PKL), berikut adalah peta struktur folder Laravel tempat pengerjaan kode akan paling sering dilakukan:

```text
jnj-sentral/
├── app/
│   ├── Http/
│   │   ├── Controllers/         <-- Letakkan file logika alur / Controller di sini
│   │   ├── Middleware/          <-- Tempat middleware keamanan dan RBAC
│   │   └── Requests/            <-- Kelas validasi formulir input
│   └── Models/                  <-- Tempat representasi tabel & relasi Eloquent
│
├── database/
│   ├── migrations/              <-- Tempat file skema tabel database
│   └── seeders/                 <-- Mengisi data default awal (seperti data akun default)
│
├── resources/
│   ├── css/                     <-- Berisi file CSS utama (app.css)
│   ├── js/                      <-- Berisi file JavaScript utama (app.js)
│   └── views/                   <-- Tempat file tampilan HTML/Blade (.blade.php)
│       ├── layouts/             <-- Layout global / template dasar
│       ├── dashboard/           <-- Halaman dasbor visual
│       └── projects/            <-- Halaman daftar dan form input transaksi
│
└── routes/
    └── web.php                  <-- Mendefinisikan URL/Rute akses aplikasi web
```

---

## 5. Checklist Setup Developer Baru

Berikut adalah daftar periksa yang harus diselesaikan oleh tim developer baru sebelum menyatakan lingkungan kerjanya siap (*ready for coding*):

- [ ] Telah menginstal **PHP 8.2+** dan **MySQL 8.0+** di sistem lokal (Laragon terkonfigurasi).
- [ ] Telah menginstal **Composer** dan **Node.js (LTS)**.
- [ ] Berhasil melakukan kloning repositori `jnj-sentral` dan berada di cabang `main`.
- [ ] Berhasil menjalankan perintah `composer install` tanpa error dependency.
- [ ] Berhasil membuat berkas `.env` dan melakukan konfigurasi nama database ke `jnj_sentral`.
- [ ] Berhasil membuat *application key* dengan `php artisan key:generate`.
- [ ] Berhasil membuat database kosong di MySQL dan menjalankan `php artisan migrate --seed`.
- [ ] Berhasil mengakses domain [http://jj-sentral.test](http://jj-sentral.test) atau [http://127.0.0.1:8000](http://127.0.0.1:8000) di browser.
- [ ] Berhasil menjalankan `npm install` dan `npm run dev`.
- [ ] Telah membaca dan memahami aturan penamaan *branch* Git.
