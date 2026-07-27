# Rancangan Antarmuka Pengguna (Wireframe & UI Mockup) - J&J Sentral
**Kode Dokumen:** UIM-JNJ-SENTRAL-04  
**Versi:** 1.0.0  
**Tanggal:** 27 Juli 2026  
**Status:** Siap Ditinjau  
**Target Platform:** Web-Based (Responsive Desktop & Tablet)  
**Pembuat:** UI/UX Designer & Lead Frontend Developer  

---

## 1. Pendahuluan
Dokumen ini mendefinisikan tata letak (*layout*) dan komponen antarmuka pengguna aplikasi **J&J Sentral**. Desain tata letak disajikan dalam bentuk rancangan berbasis teks (ASCII Art) guna memberikan visualisasi tata ruang yang jelas bagi tim developer saat mengimplementasikan halaman *blade view* di Laravel. 

Seluruh rancangan berpusat pada kegunaan (*usability*), efisiensi *data entry*, dan pembatasan visual sesuai peran (*role*) masing-masing pengguna.

---

## 2. Layout Global (Template Dasar Web)

Layout global ini digunakan sebagai struktur dasar (master template) di seluruh modul aplikasi. Struktur ini menerapkan panel navigasi samping (*Sidebar*) yang tetap (*sticky*) dan area konten utama yang responsif.

```text
+-----------------------------------------------------------------------------------------+
|  [Logo J&J]  |  Header: J&J Sentral Dashboard              [Notifikasi (3)]  [Profil V] |
+-----------------------------------------------------------------------------------------+
|              |                                                                          |
|  NAVIGATION  |  MAIN CONTENT AREA                                                       |
|  ----------  |  +--------------------------------------------------------------------+  |
|              |  |                                                                    |  |
|  o Dashboard |  |  Halaman Utama / Sub-Modul akan dimuat secara dinamis              |  |
|  o Transaksi |  |  di dalam area konten ini.                                         |  |
|  o Klien     |  |                                                                    |  |
|  o Pengaturan|  |                                                                    |  |
|              |  |                                                                    |  |
|              |  +--------------------------------------------------------------------+  |
|              |                                                                          |
|  [Log Out]   |  Footer: © 2026 J&J Group - Rooterin. All rights reserved.               |
+--------------+--------------------------------------------------------------------------+
```

### Keterangan Komponen Layout Global:
*   **Sidebar (Panel Kiri):**
    *   `Logo J&J / Rooterin`: Berfungsi sebagai tombol beranda (Home).
    *   `Menu Navigasi`: Menyesuaikan dinamis dengan hak akses pengguna. Item menu aktif diberi penanda status visual (highlight warna berbeda).
    *   `Tombol Log Out`: Ditempatkan di bagian paling bawah sidebar untuk mencegah klik yang tidak sengaja.
*   **Header (Panel Atas):**
    *   `Judul Halaman`: Menunjukkan nama halaman yang sedang diakses pengguna saat ini.
    *   `Notifikasi Icon`: Berwarna merah jika terdapat transaksi baru berstatus `Pending Approval` yang memerlukan perhatian Owner.
    *   `Dropdown Profil`: Menampilkan nama pengguna yang sedang aktif beserta perannya (misal: *Wibowo - Owner*).
*   **Main Content Area (Tengah-Kanan):**
    *   Menampilkan halaman kerja dinamis dengan batas padding aman ($\pm 24\text{px}$) untuk kenyamanan mata.

---

## 3. Dashboard Owner (Level Eksekutif)

Dashboard ini dirancang khusus untuk mempermudah Owner memantau kinerja finansial perusahaan dalam satu pandangan mata (*single pane of glass*) serta memberikan persetujuan dengan cepat.

```text
+-----------------------------------------------------------------------------------------+
|  Dashboard Eksekutif                                                  Periode: [Juli 2026 v] |
+-----------------------------------------------------------------------------------------+
|  +----------------------+  +----------------------+  +-------------------------------+  |
|  | [Pendapatan Kotor]   |  | [Total Pengeluaran]  |  | [Laba Bersih (Net)]           |  |
|  | Rp 150.000.000,00    |  | Rp 45.000.000,00     |  | Rp 105.000.000,00             |  |
|  +----------------------+  +----------------------+  +-------------------------------+  |
+-----------------------------------------------------------------------------------------+
|                                                                                         |
|  [ Grafik Tren Finansial Bulanan - Laba/Rugi ]                                          |
|   150M |     #                                                                          |
|   100M |     #       #                                                                  |
|    50M |     #   #   #                                                                  |
|     0M +-------------------                                                             |
|         Mei Juni Juli                                                                   |
|                                                                                         |
+-----------------------------------------------------------------------------------------+
|  [ Approval Center - Transaksi Menunggu Persetujuan ]                                    |
|  +----+--------------+-------------------+----------------+-------------+------------+  |
|  | ID | Tipe Klien   | Kategori          | Jumlah         | Diajukan    | Aksi       |  |
|  +----+--------------+-------------------+----------------+-------------+------------+  |
|  | 42 | B2B (Rumkit) | Fee Marketing 30% | Rp 6.000.000   | Admin Ardy  | [App] [Rej]|  |
|  | 43 | Residensial  | Biaya Tak Terduga | Rp 1.500.000   | Admin Ardy  | [App] [Rej]|  |
|  +----+--------------+-------------------+----------------+-------------+------------+  |
+-----------------------------------------------------------------------------------------+
```

### Keterangan Komponen Dashboard Owner:
*   **Visualisasi Kartu Metrik (Top Cards):**
    *   `Pendapatan Kotor`: Diambil langsung dari penjumlahan semua data pendapatan yang terdaftar di periode terpilih.
    *   `Total Pengeluaran`: Menjumlahkan seluruh biaya operasional harian, overhead, dan komisi marketing yang disetujui.
    *   `Laba Bersih`: Memiliki warna aksen hijau cerah untuk menandakan kesehatan profitabilitas.
*   **Grafik Tren Finansial:**
    *   Grafik berbasis diagram batang/garis interaktif untuk membandingkan pendapatan kotor vs laba bersih bulanan.
*   **Approval Center (Pusat Persetujuan):**
    *   Hanya menampilkan transaksi dengan status `Pending`.
    *   Tombol `[App]` (Approve - Hijau) untuk menyetujui transaksi dan memperbarui laba bersih.
    *   Tombol `[Rej]` (Reject - Merah) untuk membatalkan pengajuan transaksi.

---

## 4. Halaman Input Transaksi (Admin Operasional)

Formulir ini dioptimalkan untuk kecepatan input data transaksi dengan keyboard-only navigation. Sisi kanan menampilkan riwayat input hari ini sebagai referensi silang cepat staf admin.

```text
+-----------------------------------------------------------------------------------------+
|  Input Transaksi Finansial                                                              |
+------------------------------------+----------------------------------------------------+
|  [ FORM INPUT TRANSAKSI BARU ]     |  [ TRANSAKSI TERBARU HARI INI ]                    |
|                                    |                                                    |
|  Kategori Transaksi:               |  +----+------------------+------------+---+-----+  |
|  [ Pilih Kategori            v ]   |  | ID | Detail/Kategori  | Jumlah     |St | Aks |  |
|                                    |  +----+------------------+------------+---+-----+  |
|  Pilih Klien:                      |  | 78 | RS Duren Sawit   | 10.000.000 | A |[Ed] |  |
|  [ Pilih Klien / Pelanggan   v ]   |  | 79 | Upah Teknisi     |  1.200.000 | A |[Ed] |  |
|                                    |  | 80 | Biaya Tak Terduga|  2.500.000 | P | --  |  |
|  Tanggal Pelayanan:                |  +----+------------------+------------+---+-----+  |
|  [ DD / MM / YYYY              ]   |  Status: A = Approved (Auto-Approve)               |
|                                    |          P = Pending Approval (Menunggu Owner)    |
|  Jumlah Uang (Rupiah):             |                                                    |
|  [ Rp 0,00                     ]   |  Keterangan Batas Waktu Sunting:                   |
|                                    |  - Tombol [Ed] (Edit) aktif selama < 24 jam.       |
|  Bukti Transaksi (Nota/Kuitansi):  |  - Transaksi Pending (P) tidak dapat di-edit.      |
|  [ Choose File...              ]   |                                                    |
|                                    |                                                    |
|  Detail Pekerjaan / Justifikasi:   |                                                    |
|  +-------------------------------+ |                                                    |
|  |                               | |                                                    |
|  +-------------------------------+ |                                                    |
|                                    |                                                    |
|  [ SIMPAN TRANSAKSI ]              |                                                    |
+------------------------------------+----------------------------------------------------+
```

### Keterangan Komponen Halaman Input:
*   **Formulir Input (Sisi Kiri):**
    *   `Kategori`: Dropdown dinamis sesuai data enumerasi di database.
    *   `Pilih Klien`: Dilengkapi fitur search filter agar Admin tidak perlu menggulir ratusan nama klien.
    *   `Jumlah Uang`: Form input otomatis memformat angka menjadi format ribuan rupiah (contoh: *1,200,000*).
    *   `Bukti Transaksi`: Tombol unggah dokumen pendukung (JPEG, PNG, PDF) dengan limitasi file 2MB.
*   **Tabel Transaksi Harian (Sisi Kanan):**
    *   Tabel ringkas yang memuat transaksi yang baru saja dimasukkan oleh admin yang bersangkutan pada hari berjalan.
    *   Tombol `[Ed]` (Edit) akan hilang jika waktu pembuatan data melampaui 24 jam.

---

## 5. Halaman User Management (Admin Website / Developer)

Halaman ini steril dari nominal rupiah perusahaan. Fokus utamanya adalah penambahan pengguna, penonaktifan akun, dan pengaturan ulang kata sandi demi keamanan.

```text
+-----------------------------------------------------------------------------------------+
|  User Management & System Settings                                [ + Tambah Akun Baru ]|
+-----------------------------------------------------------------------------------------+
|  Daftar Pengguna Sistem                                                                 |
|  +----+-----------------------+-------------------+--------------+-------------------+  |
|  | ID | Nama Lengkap          | Email             | Peran (Role) | Status Akun       |  |
|  +----+-----------------------+-------------------+--------------+-------------------+  |
|  | 01 | Wibowo Pratikno       | wibowo@jnj.com    | Owner        | [Aktif]           |  |
|  | 02 | Ardy Saputra          | ardy.ops@jnj.com  | Admin Ops    | [Aktif]           |  |
|  | 03 | Rian Hidayat (PKL)    | rian.pkl@jnj.com  | Admin Ops    | [Nonaktif]        |  |
|  | 04 | Dev Team              | dev@system.com    | Admin Web    | [Aktif]           |  |
|  +----+-----------------------+-------------------+--------------+-------------------+  |
|                                                                                         |
|  Aksi Cepat untuk Baris Terpilih:                                                       |
|  [ Nonaktifkan Akun (Mute) ]     [ Reset Kata Sandi ]                                   |
|                                                                                         |
|  *Catatan Keamanan Sistem:                                                              |
|   1. Pengguna dengan Peran 'Admin Web' tidak memiliki akses visual ke modul keuangan.    |
|   2. Akun nonaktif tidak dapat melakukan login, namun riwayat log transaksi tetap aman. |
+-----------------------------------------------------------------------------------------+
```

### Keterangan Komponen User Management:
*   **Tombol "+ Tambah Akun Baru":** Membuka modal pop-up yang mewajibkan input Nama, Email, Peran (*Owner / Admin Ops / Admin Web*), dan sandi default.
*   **Tabel Pengguna:**
    *   Menunjukkan peran pengguna secara transparan.
    *   Status `Nonaktif` digunakan untuk menangguhkan hak akses (seperti anak PKL yang telah menyelesaikan magangnya) tanpa melakukan *hard delete* guna menjaga integritas relasi foreign key keuangan.
*   **Tombol Aksi Cepat:**
    *   `Reset Kata Sandi`: Menghasilkan kata sandi acak baru yang aman dan dikirimkan via log developer untuk diberikan kepada staf bersangkutan.

---

## 6. Rekomendasi Pustaka Komponen (Component Library)

Untuk mempercepat proses pengembangan antarmuka pada framework Laravel, kami menyarankan pilihan pustaka komponen berikut sesuai dengan pendekatan arsitektur yang akan diambil:

1.  **Filament Admin (Sangat Direkomendasikan):**
    *   *Alasan:* Paket administrasi siap pakai berbasis **Tailwind CSS** dan **Laravel Livewire**. Sangat cocok untuk aplikasi internal ERP/Sentral karena secara otomatis menyediakan CRUD generator, manajemen RBAC, layout dashboard siap pakai, widget grafik, dan komponen filter pencarian yang lengkap tanpa harus menulis Javascript dari nol.
2.  **Tailwind CSS (Untuk Custom Frontend):**
    *   *Alasan:* Jika perusahaan menginginkan desain kustom penuh (*fully-tailored UI*). Dapat dipadukan dengan pustaka UI seperti **Flowbite** atau **Preline UI** untuk menyediakan komponen-komponen antarmuka yang modern, responsif, dan ringan.
3.  **Bootstrap 5 (Opsi Alternatif Klasik):**
    *   *Alasan:* Sederhana, andal, dan sangat mudah digunakan jika tim developer belum terbiasa dengan utilitas kelas Tailwind CSS. Memiliki dokumentasi yang luas untuk grid layout.
