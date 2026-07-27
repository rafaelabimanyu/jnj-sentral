# Product Requirements Document (PRD) - J&J Sentral
**Kode Dokumen:** PRD-JNJ-SENTRAL-01  
**Versi:** 1.1.0  
**Tanggal:** 27 Juli 2026  
**Status:** Draf Disetujui / Siap Implementasi  
**Target Platform:** Web-Based (Laravel 11.x)  
**Pembuat:** Senior Technical Writer & Lead Developer  

---

## 1. Executive Summary & Objective

### 1.1 Ringkasan Eksekutif
Aplikasi **J&J Sentral** dirancang sebagai sistem ERP (*Enterprise Resource Planning*) internal berskala mikro untuk **J&J Group**, dengan fokus utama pada manajemen operasional dan finansial unit bisnis **Rooterin**. Rooterin adalah layanan *eco-plumbing* modern yang mengandalkan teknologi inspeksi CCTV pipa dan mechanical pembersihan saluran tanpa bahan kimia korosif. 

Saat ini, ketiadaan sistem pencatatan terpusat menyebabkan inefisiensi pengolahan data lapangan dan laporan arus kas (*cash flow*). Dengan hadirnya **J&J Sentral**, perusahaan dapat mengonsolidasikan laporan harian teknisi, menghitung pengeluaran operasional serta overhead, menentukan fee marketing secara dinamis, dan menyajikan metrik laba bersih (*net income*) secara *real-time* kepada pihak manajemen.

### 1.2 Tujuan Sistem
1. **Sentralisasi Operasional Lapangan:** Mengintegrasikan dokumentasi pekerjaan teknisi (termasuk rekaman inspeksi CCTV) dengan data keuangan proyek secara langsung.
2. **Kalkulasi Laba Bersih Otomatis:** Mengurangi risiko kesalahan manusia (*human error*) dalam penghitungan keuangan dengan menerapkan kalkulasi otomatis di tingkat aplikasi.
3. **Peningkatan Kontrol Internal:** Mencegah terjadinya kebocoran kas (misalnya pada pengeluaran tak terduga dan pembayaran *fee marketing*) melalui alur kerja persetujuan (*approval workflow*) terpusat.
4. **Isolasi Data Sensitif:** Menjamin data keuangan sensitif perusahaan tidak dapat diakses atau dibaca oleh personil non-eksekutif, termasuk pihak developer sistem.

---

## 2. Target Audience & User Personas

Sistem ini membedakan peran dan wewenang pengguna secara tegas dengan detail persona sebagai berikut:

### 2.1 Owner (Level Eksekutif)
*   **Profil:** Pemilik J&J Group.
*   **Perilaku:** Memantau dashboard secara berkala (harian/mingguan), melakukan *review* pengeluaran di atas batas normal, dan menggunakan data laba bersih untuk pengambilan keputusan strategis.
*   **Kebutuhan Utama:** Dashboard visual interaktif, ekspor data PDF/Excel untuk pembukuan eksternal, dan antarmuka persetujuan cepat (*quick approval*).

### 2.2 Admin Operasional (Level Manajerial/Harian)
*   **Profil:** Manajer Operasional atau Staf Admin Kantor.
*   **Perilaku:** Berinteraksi aktif dengan aplikasi setiap hari untuk menginput data pesanan, menjadwalkan teknisi, mencatat bukti transfer pendapatan kotor, serta mendokumentasikan nota pengeluaran lapangan.
*   **Kebutuhan Utama:** Formulir entri data yang cepat dengan fitur *autocomplete* pelanggan, pelacakan status proyek, dan penanda waktu batas revisi data.

### 2.3 Admin Website / Developer (Level Teknis)
*   **Profil:** Tim TI Internal atau Pihak Ketiga Developer.
*   **Perilaku:** Mengelola performa sistem, memantau *error log* aplikasi di server, dan mengatur pembuatan/pengaturan ulang akun staf admin operasional.
*   **Kebutuhan Utama:** Halaman manajemen pengguna, akses ke *log viewer*, dan jaminan keamanan kode tanpa harus melihat angka finansial perusahaan yang bersifat rahasia.

---

## 3. Scope of Work (Ruang Lingkup Kerja)

### 3.1 In-Scope (Dalam Cakupan)
*   **Otentikasi Pengguna & RBAC:** Halaman Login dengan otentikasi sesi aman, proteksi *brute-force*, dan pembatasan hak akses berbasis *middleware* Laravel.
*   **Manajemen Proyek & Pendapatan:** Pencatatan kontrak/layanan B2B dan residensial beserta total pendapatan kotor (*gross income*).
*   **Manajemen Pengeluaran (Expenses):** Pencatatan 9 variabel pengeluaran utama dengan validasi format dan tipe data yang ketat.
*   **Dashboard Finansial:** Visualisasi grafik laba bersih, total pengeluaran, dan pendapatan kotor berbasis grafik interaktif (misalnya Chart.js atau ApexCharts).
*   **Log Audit (Audit Trail):** Pencatatan otomatis setiap aktivitas perubahan data finansial oleh sistem.

### 3.2 Out-of-Scope (Luar Cakupan)
*   Integrasi otomatisasi pembayaran via API bank (Virtual Account atau E-Wallet). Seluruh status pembayaran dilakukan secara manual melalui pencatatan verifikasi Admin Operasional.
*   Modul absensi GPS untuk teknisi di lapangan secara *real-time*.
*   Sistem manajemen persediaan material fisik / gudang (*Inventory Management*).

---

## 4. Variabel Keuangan & Struktur Data

Setiap transaksi finansial (pendapatan dan pengeluaran) harus didefinisikan secara eksplisit di dalam skema database. Berikut adalah tabel pemetaan variabel keuangan beserta tipe data dan aturan validasinya di Laravel:

### 4.1 Tabel Spesifikasi Data Finansial

| Variabel Sistem | Nama Kolom Database | Tipe Data SQL | Deskripsi | Aturan Validasi Laravel |
| :--- | :--- | :--- | :--- | :--- |
| **Gross Income** | `gross_income` | `DECIMAL(12,2)` | Pendapatan kotor dari jasa proyek | `required|numeric|min:0` |
| **Client Type** | `client_type` | `ENUM('B2B', 'Residensial')` | Kategori segmentasi klien | `required|in:B2B,Residensial` |
| **Client Name** | `client_name` | `VARCHAR(255)` | Nama instansi atau perorangan | `required|string|max:255` |
| **Ads Expense** | `expense_ads` | `DECIMAL(12,2)` | Biaya iklan digital (Google/FB Ads) | `nullable|numeric|min:0` |
| **Entertain Expense** | `expense_entertain` | `DECIMAL(12,2)` | Biaya menjamu akuisisi klien B2B | `nullable|numeric|min:0` |
| **Infrastructure** | `expense_infra` | `DECIMAL(12,2)` | Biaya overhead (WiFi, air, listrik) | `nullable|numeric|min:0` |
| **Field Operations** | `expense_field_ops` | `DECIMAL(12,2)` | Bensin, parkir, tol di lapangan | `nullable|numeric|min:0` |
| **Technician Wage** | `expense_tech_wage` | `DECIMAL(12,2)` | Upah harian untuk 2 orang teknisi | `required|numeric|min:0` |
| **Bonus** | `expense_bonus` | `DECIMAL(12,2)` | Bonus lokasi/kerja malam teknisi | `nullable|numeric|min:0` |
| **Marketing Fee** | `marketing_fee` | `DECIMAL(12,2)` | Pengeluaran komisi makelar | `required|numeric|min:0` |
| **Welfare** | `expense_welfare` | `DECIMAL(12,2)` | Tunjangan/Family Gathering | `nullable|numeric|min:0` |
| **Unexpected** | `expense_unexpected` | `DECIMAL(12,2)` | Pengeluaran tak terduga/darurat | `nullable|numeric|min:0` |
| **Net Income** | `net_income` | `DECIMAL(12,2)` | Nilai laba bersih proyek (Kalkulasi) | `required|numeric` |
| **Approval Status** | `approval_status` | `ENUM` | Status persetujuan transaksi finansial | `required|in:Auto-Approve,Pending,Approved,Rejected` |

### 4.2 Formula Perhitungan Finansial Absolut
Sistem wajib menghitung nilai `net_income` secara otomatis pada tingkat aplikasi sebelum menyimpan atau memperbarui baris data di database.

```text
Net Income = Gross Income - (Ads + Entertain + Infra + Field Ops + Tech Wage + Bonus + Marketing Fee + Welfare + Unexpected)
```

Secara pemrograman Laravel (Model Eloquent), formulanya ditulis sebagai berikut:

```php
protected static function booted()
{
    static::saving(function ($project) {
        $totalExpenses = $project->expense_ads 
            + $project->expense_entertain 
            + $project->expense_infra 
            + $project->expense_field_ops 
            + $project->expense_tech_wage 
            + $project->expense_bonus 
            + $project->marketing_fee 
            + $project->expense_welfare 
            + $project->expense_unexpected;
            
        $project->net_income = $project->gross_income - $totalExpenses;
    });
}
```

---

## 5. Role-Based Access Control (RBAC) Matrix

Akses terhadap data dalam aplikasi diatur menggunakan matriks izin tingkat tinggi berikut:

| Modul / Fitur | Aksi CRUD | Owner | Admin Operasional | Admin Website / Dev |
| :--- | :--- | :---: | :---: | :---: |
| **Laporan Proyek & Finansial** | Create | ❌ No |  Yes | ❌ No |
| | Read |  Yes |  Yes | ❌ No (Finansial disembunyikan) |
| | Update | ❌ No |  Yes (Batas 24 Jam) | ❌ No |
| | Delete | ❌ No | ❌ No (Soft Delete saja) | ❌ No |
| **Persetujuan (Approval)** | Read & Update |  Yes | ❌ No | ❌ No |
| **Log Aktivitas & Error** | Read |  Yes | ❌ No |  Yes |
| **Manajemen Pengguna (User)** | Create, Read, Update, Delete | ❌ No (Hanya Read) | ❌ No |  Yes |

---

## 6. Business Rules (Aturan Bisnis)

Berikut adalah aturan bisnis mutlak yang harus diterapkan pada kode backend Laravel:

### 6.1 Batas Waktu Koreksi Transaksi
> [!IMPORTANT]
> **Aturan Penguncian Data (24-Hour Edit Lock):**  
> Admin Operasional hanya diperbolehkan melakukan *Update* pada transaksi finansial yang dibuat dalam waktu $\le 24$ jam sebelumnya. Begitu perbedaan waktu antara `created_at` dengan waktu server saat ini $> 24$ jam, tombol edit pada antarmuka pengguna akan dinonaktifkan, dan API backend harus melempar pengecualian (*exception*) HTTP 403 (Unauthorized Action).

### 6.2 Mekanisme Penghapusan Data (Soft Delete)
> [!WARNING]
> **Larangan Hard Delete:**  
> Penghapusan data finansial secara fisik (`DELETE FROM`) dilarang keras di semua tingkatan pengguna (termasuk *Owner*). Penghapusan harus menggunakan trait `Illuminate\Database\Eloquent\SoftDeletes` dari Laravel. Hal ini memastikan integritas data audit dan kepatuhan finansial.

### 6.3 Skema Persetujuan (Approval Workflow)
> [!CAUTION]
> **Batas Persetujuan Biaya & Fee Marketing:**  
> 1. Setiap pengeluaran pada kolom `expense_unexpected` bernilai $> 0$ secara otomatis mengubah status transaksi menjadi `Pending Approval`.
> 2. Pilihan persentase *Fee Marketing* yang diinput oleh Admin Operasional dibatasi pada angka **5%, 10%, 20%, 30%, 40%**.
> 3. Transaksi dengan persentase *Fee Marketing* $\le 20\%$ akan mendapatkan status `Auto-Approve`.
> 4. Transaksi dengan persentase *Fee Marketing* $> 20\%$ (opsi 30% atau 40%) secara otomatis mengubah status transaksi menjadi `Pending Approval` dan tidak boleh dihitung ke dalam total profit bulanan sebelum disetujui secara manual oleh *Owner*.

---

## 7. Arsitektur Teknis & Struktur Direktori Laravel

### 7.1 Struktur Folder Proyek
Berikut adalah rancangan struktur folder pada proyek Laravel J&J Sentral yang menunjukkan letak file-file penting yang harus dibuat untuk mengakomodasi PRD ini:

```text
jnj-sentral/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   └── UserController.php (Untuk Admin Website/Dev)
│   │   │   ├── FinancialController.php (Untuk Admin Operasional & Owner)
│   │   │   └── ApprovalController.php (Untuk Owner)
│   │   ├── Middleware/
│   │   │   ├── RestrictFinancialAccess.php (Menghalangi Developer melihat nominal keuangan)
│   │   │   └── CheckEditWindow.php (Mengecek batas waktu edit 24 jam)
│   │   └── Requests/
│   │       └── StoreFinancialRequest.php
│   └── Models/
│       ├── User.php
│       ├── Project.php (Representasi data operasional & finansial)
│       └── AuditLog.php
├── database/
│   ├── migrations/
│   │   ├── 2026_07_27_000001_create_projects_table.php
│   │   └── 2026_07_27_000002_create_audit_logs_table.php
│   └── seeders/
│       └── RoleSeeder.php
├── resources/
│   └── views/
│       ├── layouts/
│       ├── dashboard/ (Untuk Owner)
│       ├── projects/ (Untuk Admin Operasional)
│       └── errors/
└── routes/
    └── web.php
```

### 7.2 Implementasi Proteksi Keuangan bagi Developer (Middleware)
Developer (*Admin Website*) dapat login ke sistem untuk memelihara aplikasi namun dilarang keras melihat nilai numerik dari keuangan. Keamanan ini dicapai menggunakan `Middleware` Laravel:

```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RestrictFinancialAccess
{
    public function handle(Request $request, Closure $next)
    {
        // Memeriksa apakah user yang masuk memiliki role 'developer'
        if ($request->user() && $request->user()->role === 'developer') {
            // Jika merujuk ke API data finansial, balikkan respon error atau lakukan masking
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Akses finansial dilarang untuk tim teknis.'], 403);
            }
            abort(403, 'Akses finansial dilarang untuk tim teknis.');
        }

        return $next($request);
    }
}
```

---

## 8. Development Roadmap & Task Lists

Berikut adalah rencana kerja (*Roadmap*) terperinci bagi tim developer untuk mengimplementasikan aplikasi J&J Sentral:

### 8.1 Fase 1: Inisiasi & Desain Database (Minggu 1)
- [ ] Menyusun ERD (*Entity Relationship Diagram*) proyek.
- [ ] Membuat migrasi database untuk tabel `users` (dengan kolom `role`).
- [ ] Membuat migrasi database untuk tabel `projects` dengan semua kolom finansial lengkap.
- [ ] Membuat migrasi database untuk tabel `audit_logs` guna mencatat perubahan data.
- [ ] Menulis `RoleSeeder` untuk inisialisasi akun *Owner*, *Admin Operasional*, dan *Developer*.

### 8.2 Fase 2: Logika Bisnis & Backend Laravel (Minggu 2)
- [ ] Mengimplementasikan *Middleware* penguncian edit data 24 jam (`CheckEditWindow`).
- [ ] Mengimplementasikan *Middleware* pembatasan finansial Developer (`RestrictFinancialAccess`).
- [ ] Membuat model `Project.php` beserta fungsi otomatisasi perhitungan `net_income` pada event `saving`.
- [ ] Memasang fitur `SoftDeletes` di model `Project.php`.
- [ ] Membuat API & Controller untuk penanganan transaksi keuangan harian (`FinancialController`).
- [ ] Membuat fungsi persetujuan transaksi (`ApprovalController`) khusus untuk *Owner*.

### 8.3 Fase 3: Pembuatan Antarmuka & Dashboard (Minggu 3)
- [ ] Membuat layout visual modern menggunakan Tailwind CSS / Bootstrap 5.
- [ ] Membangun antarmuka dashboard finansial interaktif untuk *Owner* (grafik batang dan garis).
- [ ] Membangun form entri data operasional/keuangan yang responsif untuk *Admin Operasional*.
- [ ] Membuat sistem notifikasi status transaksi (`Pending`, `Approved`, `Rejected`).
- [ ] Membangun antarmuka manajemen pengguna (*User Management*) bagi *Developer*.

### 8.4 Fase 4: Pengujian & Penyempurnaan (Minggu 4)
- [ ] Melakukan Unit Testing pada fungsi perhitungan `net_income` dan *middleware* 24 jam.
- [ ] Melakukan integrasi pengujian (Integration Testing) untuk alur kerja persetujuan (*Approval Workflow*).
- [ ] Memverifikasi bahwa data rupiah tidak bocor ke log error yang dapat dibaca *Developer*.
- [ ] Melakukan optimasi query database (indeksasi kolom pencarian klien dan tanggal).

### 8.5 Fase 5: Deployment & Go-Live (Minggu 5)
- [ ] Mengatur berkas konfigurasi `.env` pada server produksi (Laragon / VPS).
- [ ] Menjalankan migrasi database produksi dengan aman.
- [ ] Melakukan pengujian keamanan (*Penetration Testing* skala kecil) untuk otentikasi.
- [ ] Penyerahan dokumentasi panduan penggunaan aplikasi (*User Guide*) ke tim J&J Group.

<!-- test commit -->