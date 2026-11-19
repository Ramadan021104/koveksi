# LAPORAN PROJECT
# WEBSITE KATALOG & CUSTOM ORDER INDRA KONVEKSI

**Disusun untuk memenuhi tugas mata kuliah Pemrograman Web / Rekayasa Perangkat Lunak**

---

```
Nama        : ___________________________
NIM         : ___________________________
Kelas       : ___________________________
Dosen       : ___________________________
Mata Kuliah : ___________________________
```

---

**PROGRAM STUDI TEKNIK INFORMATIKA**
**FAKULTAS …………………**
**UNIVERSITAS …………………**
**TAHUN 2026**

\newpage

## KATA PENGANTAR

Puji syukur kehadirat Tuhan Yang Maha Esa atas berkat dan rahmat-Nya sehingga penyusun dapat menyelesaikan project akhir berjudul **"Website Katalog & Custom Order Indra Konveksi"** ini dengan baik dan tepat waktu.

Project ini disusun sebagai pemenuhan tugas mata kuliah Pemrograman Web, sekaligus penerapan ilmu yang telah dipelajari di bangku kuliah meliputi: PHP, MySQL, HTML, CSS, JavaScript, dan Bootstrap.

Penyusun menyadari laporan ini masih jauh dari sempurna. Kritik dan saran yang membangun sangat diharapkan untuk perbaikan di masa mendatang. Semoga project ini bermanfaat bagi pembaca dan dapat menjadi referensi bagi pengembangan website konveksi lainnya.

Bengkulu, ......................... 2026

Penyusun,



( _________________________ )

\newpage

## DAFTAR ISI

- KATA PENGANTAR
- DAFTAR ISI
- **BAB I PENDAHULUAN**
  - 1.1 Latar Belakang
  - 1.2 Rumusan Masalah
  - 1.3 Tujuan
  - 1.4 Manfaat
  - 1.5 Batasan Masalah
- **BAB II LANDASAN TEORI**
  - 2.1 Website
  - 2.2 PHP
  - 2.3 MySQL
  - 2.4 HTML, CSS, JavaScript
  - 2.5 Bootstrap 5
  - 2.6 XAMPP
  - 2.7 Konveksi
- **BAB III ANALISIS DAN PERANCANGAN**
  - 3.1 Analisis Kebutuhan
  - 3.2 Use Case Diagram
  - 3.3 Flowchart Sistem
  - 3.4 Entity Relationship Diagram (ERD)
  - 3.5 Struktur Tabel Database
  - 3.6 Struktur Folder Project
- **BAB IV IMPLEMENTASI DAN PENGUJIAN**
  - 4.1 Spesifikasi Sistem
  - 4.2 Instalasi
  - 4.3 Hasil Implementasi (Customer)
  - 4.4 Hasil Implementasi (Admin)
  - 4.5 Pengujian (Black Box Testing)
- **BAB V PENUTUP**
  - 5.1 Kesimpulan
  - 5.2 Saran
- DAFTAR PUSTAKA
- LAMPIRAN

\newpage

# BAB I
# PENDAHULUAN

## 1.1 Latar Belakang

Indra Konveksi merupakan usaha konveksi & sablon yang berlokasi di Bengkulu yang melayani pembuatan berbagai produk seperti seragam sekolah (TK, SD, SMP, SMA), almamater, jaket, kaos, topi, ID card, lanyard, batik, hingga seragam dinas TNI/POLRI. Selama ini, proses pemesanan dilakukan secara konvensional melalui WhatsApp, telepon, atau datang langsung ke tempat, sehingga calon pelanggan kesulitan melihat katalog produk secara menyeluruh, membandingkan harga, dan melakukan pemesanan custom dengan detail yang tepat.

Di era digital saat ini, kehadiran sebuah website resmi menjadi kebutuhan strategis bagi pelaku UMKM agar dapat menjangkau pasar yang lebih luas dan meningkatkan profesionalitas usaha. Dengan adanya website katalog produk yang dilengkapi fitur custom order, pelanggan dapat dengan mudah mengakses informasi produk 24 jam, melakukan pemesanan sesuai keinginan, dan menerima notifikasi langsung melalui WhatsApp.

Berdasarkan latar belakang tersebut, dibutuhkan sebuah **Website Katalog & Custom Order** untuk Indra Konveksi yang dibangun menggunakan bahasa pemrograman **PHP** dengan basis data **MySQL** dan framework tampilan **Bootstrap 5**.

## 1.2 Rumusan Masalah

1. Bagaimana merancang dan membangun website katalog produk konveksi yang informatif dan mudah digunakan oleh pelanggan?
2. Bagaimana mengimplementasikan fitur custom order yang dapat menerima detail pesanan (bahan, ukuran, warna, jumlah) beserta upload desain dari pelanggan?
3. Bagaimana membangun panel admin yang dapat mengelola kategori, produk, pesanan, dan pelanggan?
4. Bagaimana menghubungkan website dengan WhatsApp admin untuk konfirmasi pesanan secara otomatis?

## 1.3 Tujuan

Tujuan dari project ini adalah:

1. Membuat website katalog produk Indra Konveksi yang dapat diakses publik.
2. Mengimplementasikan fitur registrasi & login pelanggan.
3. Membuat fitur custom order beserta upload desain.
4. Membuat panel admin lengkap untuk pengelolaan website (CRUD produk, kategori, pesanan, customer).
5. Mengintegrasikan website dengan WhatsApp untuk komunikasi cepat antara customer dan admin.
6. Menyediakan fitur cetak struk pesanan sebagai bukti.

## 1.4 Manfaat

**Bagi Pelanggan:**
- Dapat melihat katalog lengkap produk secara online kapan saja.
- Dapat melakukan pemesanan custom dengan mudah.
- Mendapat struk pesanan otomatis.

**Bagi Pemilik Usaha (Indra Konveksi):**
- Meningkatkan jangkauan pasar.
- Pengelolaan data pesanan, produk, dan pelanggan terpusat dalam satu sistem.
- Meningkatkan profesionalitas usaha.
- Memudahkan dokumentasi pesanan (struk cetak).

**Bagi Penyusun:**
- Sebagai sarana penerapan ilmu pemrograman web.
- Menambah pengalaman dalam pengembangan aplikasi full-stack.

## 1.5 Batasan Masalah

1. Sistem dibangun menggunakan PHP Native (PDO MySQL) tanpa framework PHP (seperti Laravel/CodeIgniter).
2. Tampilan menggunakan Bootstrap 5 dan Bootstrap Icons.
3. Sistem dijalankan secara lokal menggunakan XAMPP, tetapi siap di-deploy ke hosting.
4. Fitur email (lupa password, notifikasi) memerlukan konfigurasi SMTP (Gmail / Mailtrap / SendGrid).
5. Fitur notifikasi WhatsApp otomatis ke admin memerlukan registrasi di provider Fonnte atau Wablas.
6. Fitur login dengan Google memerlukan registrasi OAuth Client ID di Google Cloud Console.
7. Pembayaran tidak terintegrasi secara digital — dilakukan secara manual via WhatsApp.

\newpage

# BAB II
# LANDASAN TEORI

## 2.1 Website

Website adalah kumpulan halaman web yang saling terhubung satu sama lain dan dapat diakses melalui jaringan internet. Website terdiri dari halaman-halaman yang berisi teks, gambar, video, audio, dan elemen interaktif lainnya.

## 2.2 PHP (Hypertext Preprocessor)

PHP adalah bahasa pemrograman server-side yang banyak digunakan untuk membuat website dinamis. PHP dapat berinteraksi langsung dengan database (seperti MySQL) untuk menyimpan, membaca, mengubah, dan menghapus data. Pada project ini digunakan PHP versi 8.x dengan ekstensi PDO (PHP Data Objects) untuk koneksi database yang lebih aman dari SQL Injection.

## 2.3 MySQL

MySQL adalah sistem manajemen basis data relasional (RDBMS) open source yang menggunakan bahasa SQL (Structured Query Language) untuk mengelola data. Pada project ini, MySQL digunakan untuk menyimpan data admin, customer, kategori, produk, pesanan, dan pengaturan situs.

## 2.4 HTML, CSS, dan JavaScript

- **HTML (HyperText Markup Language)** — bahasa markup untuk membuat struktur halaman web.
- **CSS (Cascading Style Sheets)** — bahasa untuk mengatur tampilan visual (warna, ukuran, layout) halaman web.
- **JavaScript** — bahasa pemrograman client-side yang memberikan interaksi pada halaman web (animasi, validasi form, AJAX, dll).

## 2.5 Bootstrap 5

Bootstrap adalah framework front-end open source yang menyediakan komponen UI siap pakai (grid system, button, form, modal, carousel, dsb) untuk mempercepat pengembangan website yang responsive. Versi yang digunakan adalah Bootstrap 5.3.2 (load via CDN).

## 2.6 XAMPP

XAMPP adalah perangkat lunak gratis yang menyediakan paket lengkap untuk pengembangan web di lokal komputer, terdiri dari:
- **A** = Apache (web server)
- **M** = MySQL/MariaDB (database)
- **P** = PHP (bahasa pemrograman)
- **P** = Perl

## 2.7 Konveksi

Konveksi adalah industri pembuatan pakaian jadi dalam jumlah banyak dengan ukuran standar. Pesanan custom di konveksi biasanya meliputi pemilihan bahan kain, ukuran, warna, dan desain (sablon/bordir) sesuai keinginan pemesan.

## 2.8 Keamanan Web (Web Security)

Beberapa standar keamanan yang diimplementasikan di project ini:

- **CSRF (Cross-Site Request Forgery)** — serangan yang membuat user terkena trick untuk mengirim request ke website yang sudah login. Diatasi dengan token unik di setiap form.
- **Brute-Force Attack** — serangan yang mencoba banyak kombinasi password. Diatasi dengan rate limiting (blokir IP setelah N percobaan gagal).
- **Security Headers HTTP** — header seperti `X-Frame-Options` (anti clickjacking), `X-Content-Type-Options` (anti MIME-sniffing), dan `Content-Security-Policy` (anti XSS).
- **Password Hashing** — password disimpan dalam bentuk hash bcrypt (irreversible), bukan plaintext.

## 2.9 OAuth 2.0 (Google Sign-In)

OAuth 2.0 adalah protokol otorisasi terbuka yang memungkinkan user login ke aplikasi pihak ketiga menggunakan akun Google tanpa membagikan password. Aplikasi menerima `id_token` dan `access_token` setelah user setuju memberikan akses.

## 2.10 SMTP & PHPMailer

SMTP (Simple Mail Transfer Protocol) adalah protokol pengiriman email. PHPMailer adalah library populer untuk mengirim email via SMTP dari PHP. Project ini menggunakan implementasi SMTP class yang **kompatibel dengan PHPMailer** namun lebih ringan (~150 baris kode) tanpa memerlukan Composer.

## 2.11 WhatsApp Business API (Fonnte / Wablas)

Fonnte dan Wablas adalah penyedia layanan WhatsApp Business API tidak resmi yang populer di Indonesia. Mereka menyediakan endpoint REST API untuk mengirim pesan WhatsApp otomatis dari aplikasi (cocok untuk notifikasi order, OTP, dll).

\newpage

# BAB III
# ANALISIS DAN PERANCANGAN

## 3.1 Analisis Kebutuhan

### 3.1.1 Kebutuhan Fungsional

| Aktor | Kebutuhan |
|---|---|
| **Pengunjung** | Melihat beranda dengan slider promosi (carousel), browsing katalog, melihat detail produk dengan galeri multi-gambar, melihat halaman Tentang & Kontak |
| **Customer (registered)** | Register / login (manual atau via **Google OAuth**), edit profil, custom order produk dengan **detail ukuran per size (S/M/L/XL/XXL/XXXL)** + upload desain, lihat riwayat pesanan, **lupa password via email reset link**, kirim struk ke WhatsApp admin |
| **Admin** | Login admin (terproteksi rate-limit), lihat dashboard statistik, kelola kategori, kelola produk (dengan **multi-image gallery**), kelola pesanan (search & filter), update status pesanan, **export pesanan ke Excel CSV**, **print struk pesanan**, chat customer via WhatsApp dengan struk lengkap, kelola customer, kelola pengaturan situs, **ganti password sendiri**, **backup database (format SQL & Excel multi-sheet)** |

### 3.1.2 Kebutuhan Non-Fungsional

- **Usability** — antarmuka responsive (mobile & desktop friendly).
- **Security** — password di-hash bcrypt, query dengan PDO Prepared Statement, **CSRF token di semua form**, **rate limit 5 percobaan gagal per 15 menit**, **security headers HTTP** (X-Frame-Options, CSP, dll).
- **Reliability** — fitur **backup database otomatis harian** (dengan dual format: **SQL** untuk restore via phpMyAdmin & **Excel multi-sheet** untuk laporan/arsip), log percobaan login.
- **Maintainability** — kode terstruktur (folder `config`, `includes`, `admin`, `assets`), konfigurasi terpusat (Google OAuth, SMTP, WA API) di folder `config/`.
- **Integration** — Google OAuth (login sosial), SMTP (email reset password), Fonnte/Wablas (notifikasi WA otomatis).

## 3.2 Use Case Diagram

```
                     ┌────────────────────────────────┐
                     │     WEBSITE INDRA KONVEKSI     │
                     │                                │
                     │  ◯ Lihat Beranda + Carousel    │
                     │  ◯ Browse Katalog              │
   Pengunjung ──────▶│  ◯ Lihat Detail Produk        │
                     │  ◯ Lihat Tentang & Kontak     │
                     │                                │
                     │  ◯ Register Akun               │
   Customer ────────▶│  ◯ Login / Logout             │
                     │  ◯ Custom Order (+ upload)    │
                     │  ◯ Lihat Pesanan Saya         │
                     │  ◯ Kirim Struk ke Admin (WA)  │
                     │  ◯ Edit Profil                │
                     │                                │
                     │  ◯ Login Admin                 │
                     │  ◯ Dashboard Statistik         │
                     │  ◯ Kelola Kategori (CRUD)     │
   Admin ───────────▶│  ◯ Kelola Produk (CRUD)       │
                     │  ◯ Cari & Kelola Pesanan      │
                     │  ◯ Update Status Pesanan      │
                     │  ◯ Cetak / Print Struk        │
                     │  ◯ Chat Customer via WA       │
                     │  ◯ Kelola Customer             │
                     │  ◯ Pengaturan Situs            │
                     └────────────────────────────────┘
```

## 3.3 Flowchart Pemesanan (Custom Order)

```
        ┌─────────┐
        │  MULAI  │
        └────┬────┘
             ▼
   ┌──────────────────┐
   │ Customer browse  │
   │   Katalog Produk │
   └────────┬─────────┘
            ▼
   ┌──────────────────┐
   │  Pilih Produk    │
   │  & klik Order    │
   └────────┬─────────┘
            ▼
   ┌──────────────────┐         ┌──────────────┐
   │   Sudah login?   │── NO ──▶│  Login /     │
   └──────┬───────────┘         │  Register    │
          │ YES                  └──────┬───────┘
          ▼                             │
   ┌──────────────────┐ ◀────────────────┘
   │  Isi form custom │
   │  order + upload  │
   └────────┬─────────┘
            ▼
   ┌──────────────────┐
   │  Sistem simpan   │
   │  ke DB pesanan   │
   │  + generate kode │
   └────────┬─────────┘
            ▼
   ┌──────────────────┐
   │  Sistem buatkan  │
   │  struk WhatsApp  │
   │  pre-filled      │
   └────────┬─────────┘
            ▼
   ┌──────────────────┐
   │  Customer kirim  │
   │  struk via WA    │
   │  ke admin        │
   └────────┬─────────┘
            ▼
   ┌──────────────────┐
   │  Admin konfirmasi│
   │  & update status │
   └────────┬─────────┘
            ▼
        ┌─────────┐
        │ SELESAI │
        └─────────┘
```

## 3.4 Entity Relationship Diagram (ERD)

Database `indra_konveksi` terdiri dari **9 tabel** dengan relasi antar entitas sebagai berikut:

```
┌─────────────┐        ┌─────────────┐        ┌─────────────────┐
│   admin     │        │  kategori   │        │     produk      │
├─────────────┤        ├─────────────┤        ├─────────────────┤
│ id (PK)     │        │ id (PK)     │◀──┐    │ id (PK)         │
│ username    │        │ nama        │   │    │ id_kategori (FK)├──┐
│ password    │        │ slug        │   └────│ nama            │  │
│ nama        │        │ deskripsi   │        │ bahan           │  │
│ created_at  │        │ created_at  │        │ harga_min       │  │
└─────────────┘        └─────────────┘        │ harga_max       │  │
                                              │ satuan          │  │
                                              │ deskripsi       │  │
                                              │ gambar          │  │
                                              │ stok            │  │
                                              └────────┬────────┘  │
                                                       │           │
                                                       │ 1..N      │
                                                       ▼           │
                                        ┌─────────────────────────┐│
                                        │     produk_gambar       ││
                                        ├─────────────────────────┤│
                                        │ id (PK)                 ││
                                        │ id_produk (FK) CASCADE  ││
                                        │ gambar                  ││
                                        │ urutan                  ││
                                        └─────────────────────────┘│
                                                                   │
┌─────────────────┐                                                │
│      users      │       ┌─────────────────────────┐              │
├─────────────────┤       │       pesanan           │              │
│ id (PK)         │◀──┐   ├─────────────────────────┤              │
│ nama            │   │   │ id (PK)                 │              │
│ email (UNIQUE)  │   │   │ kode (UNIQUE)           │              │
│ password (NULL) │   └───│ id_user (FK)            │              │
│ no_hp           │       │ id_produk (FK) ─────────┼──────────────┘
│ alamat          │       │ jumlah                  │
│ google_id       │       │ bahan, ukuran, warna    │
│ created_at      │       │ catatan, file_desain    │
└────────┬────────┘       │ total_harga             │
         │                │ status (ENUM)           │
         │ 1..N           │ created_at              │
         ▼                └─────────────────────────┘
┌─────────────────────┐
│   reset_token       │       ┌─────────────────────────┐
├─────────────────────┤       │     login_attempts      │
│ id (PK)             │       ├─────────────────────────┤
│ id_user (FK) CASC   │       │ id (PK)                 │
│ token (UNIQUE)      │       │ ip                      │
│ expires_at          │       │ type (ENUM admin/user)  │
│ used                │       │ success                 │
│ created_at          │       │ attempted_at            │
└─────────────────────┘       └─────────────────────────┘
                              (Standalone — log audit trail,
                               tidak relasi FK ke tabel lain)

┌─────────────────┐
│   pengaturan    │   (Singleton — 1 baris saja, config global situs)
├─────────────────┤
│ id (PK)         │
│ nama_situs      │
│ wa_admin        │
│ email_admin     │
│ alamat          │
│ tagline         │
└─────────────────┘
```

### Penjelasan Relasi

| No | Relasi | Tipe | Keterangan |
|----|--------|------|------------|
| 1 | `kategori` → `produk` | **1 : N** | Satu kategori punya banyak produk. FK `id_kategori` di tabel produk. |
| 2 | `produk` → `produk_gambar` | **1 : N** | Satu produk punya banyak gambar galeri (max 5 gambar tambahan). `ON DELETE CASCADE`. |
| 3 | `users` → `pesanan` | **1 : N** | Satu customer bisa punya banyak pesanan. FK `id_user`. |
| 4 | `produk` → `pesanan` | **1 : N** | Satu produk bisa dipesan di banyak pesanan. FK `id_produk`. |
| 5 | `users` → `reset_token` | **1 : N** | Satu user bisa punya banyak token reset password (history). `ON DELETE CASCADE`. |
| 6 | `login_attempts` | **Standalone** | Tabel audit log brute-force. Tidak relasi FK ke tabel lain — sengaja agar tetap tersimpan walau user dihapus. |
| 7 | `admin` | **Standalone** | Tabel admin terpisah dari `users`. Tidak ada relasi langsung ke entitas lain. |
| 8 | `pengaturan` | **Singleton** | Hanya 1 baris konfigurasi situs (nama, alamat, WA, email admin). |

## 3.5 Struktur Tabel Database

### Tabel `admin`
| Field | Tipe | Keterangan |
|---|---|---|
| id | INT PK AUTO | Primary key |
| username | VARCHAR(50) UNIQUE | Username login |
| password | VARCHAR(255) | Password (bcrypt) |
| nama | VARCHAR(100) | Nama lengkap |
| created_at | TIMESTAMP | Tanggal dibuat |

### Tabel `users` (customer)
| Field | Tipe | Keterangan |
|---|---|---|
| id | INT PK AUTO | Primary key |
| nama | VARCHAR(100) | Nama lengkap |
| email | VARCHAR(100) UNIQUE | Email login |
| password | VARCHAR(255) NULL | Password (bcrypt). Nullable untuk user Google OAuth |
| no_hp | VARCHAR(20) | No HP (wajib untuk order) |
| alamat | TEXT | Alamat (wajib untuk order) |
| google_id | VARCHAR(100) NULL UNIQUE | Google account ID (jika login via OAuth) |
| created_at | TIMESTAMP | Tanggal daftar |

### Tabel `produk_gambar` (galeri multi-image)
| Field | Tipe | Keterangan |
|---|---|---|
| id | INT PK AUTO | Primary key |
| id_produk | INT FK | Foreign key ke produk (CASCADE delete) |
| gambar | VARCHAR(255) | Nama file gambar |
| urutan | INT | Urutan tampil di galeri |

### Tabel `reset_token` (fitur lupa password)
| Field | Tipe | Keterangan |
|---|---|---|
| id | INT PK AUTO | Primary key |
| id_user | INT FK | Foreign key ke users |
| token | VARCHAR(100) UNIQUE | Token random 64-character |
| expires_at | DATETIME | Waktu kedaluwarsa (1 jam dari issue) |
| used | TINYINT | 1 jika token sudah dipakai |
| created_at | TIMESTAMP | Tanggal token dibuat |

### Tabel `login_attempts` (rate limit anti brute-force)
| Field | Tipe | Keterangan |
|---|---|---|
| id | INT PK AUTO | Primary key |
| ip | VARCHAR(45) | IP address pelaku |
| type | ENUM | 'admin' atau 'user' |
| success | TINYINT | 1 = login berhasil, 0 = gagal |
| attempted_at | TIMESTAMP | Waktu percobaan |

### Tabel `kategori`
| Field | Tipe | Keterangan |
|---|---|---|
| id | INT PK AUTO | Primary key |
| nama | VARCHAR(100) | Nama kategori |
| slug | VARCHAR(100) UNIQUE | Slug untuk URL |
| deskripsi | TEXT | Deskripsi kategori |
| created_at | TIMESTAMP | Tanggal dibuat |

### Tabel `produk`
| Field | Tipe | Keterangan |
|---|---|---|
| id | INT PK AUTO | Primary key |
| id_kategori | INT FK | Foreign key ke kategori |
| nama | VARCHAR(200) | Nama produk |
| bahan | VARCHAR(100) | Jenis bahan |
| harga_min | INT | Harga minimum (Rp) |
| harga_max | INT NULL | Harga maximum (range) |
| satuan | VARCHAR(30) | pcs / setel / lembar |
| deskripsi | TEXT | Deskripsi produk |
| keterangan | TEXT | Keterangan tambahan |
| gambar | VARCHAR(255) | Nama file gambar |
| stok_tersedia | TINYINT | 1=aktif, 0=nonaktif |
| created_at | TIMESTAMP | Tanggal input |

### Tabel `pesanan`
| Field | Tipe | Keterangan |
|---|---|---|
| id | INT PK AUTO | Primary key |
| kode | VARCHAR(30) UNIQUE | Kode pesanan IK-YYYYMMDD-XXXXX |
| id_user | INT FK | Foreign key ke users |
| id_produk | INT FK | Foreign key ke produk |
| jumlah | INT | Jumlah pesanan |
| bahan | VARCHAR(100) | Bahan dipilih |
| ukuran | VARCHAR(50) | Ukuran custom |
| warna | VARCHAR(50) | Warna custom |
| catatan | TEXT | Catatan customer |
| file_desain | VARCHAR(255) | File desain (jika ada) |
| total_harga | BIGINT | Total harga (harga × jumlah) |
| status | ENUM | Baru/Diproses/Selesai/Dibatalkan |
| created_at | TIMESTAMP | Tanggal pesan |

### Tabel `pengaturan`
| Field | Tipe | Keterangan |
|---|---|---|
| id | INT PK AUTO | Primary key |
| nama_situs | VARCHAR(100) | Nama toko |
| wa_admin | VARCHAR(20) | No WhatsApp admin |
| email_admin | VARCHAR(100) | Email admin |
| alamat | TEXT | Alamat fisik toko |
| tagline | VARCHAR(255) | Slogan toko |

## 3.6 Struktur Folder Project

```
indra_konveksi/
├── admin/                  → Panel admin
│   ├── includes/           → Header, footer admin
│   ├── index.php           → Dashboard
│   ├── login.php           → Login admin
│   ├── kategori.php        → CRUD kategori
│   ├── produk.php          → CRUD produk
│   ├── pesanan.php         → Kelola pesanan + search
│   ├── struk-print.php     → Cetak struk pesanan
│   ├── users.php           → Kelola customer
│   └── pengaturan.php      → Pengaturan situs
├── assets/
│   ├── css/style.css       → Stylesheet utama
│   ├── js/script.js        → JavaScript
│   └── images/slides/      → Gambar slider beranda
├── config/
│   ├── db.php              → Koneksi PDO MySQL
│   ├── config.php          → Konfigurasi situs
│   └── slides.php          → Konfigurasi slide carousel
├── includes/
│   ├── header.php          → Header customer
│   ├── footer.php          → Footer customer
│   ├── auth.php            → Helper auth
│   └── functions.php       → Helper umum (struk, format, dll)
├── uploads/                → Upload gambar produk & desain
├── database.sql            → Skema + Seed data MySQL
├── index.php               → Beranda + Carousel
├── katalog.php             → Halaman katalog
├── produk.php              → Detail produk
├── custom-order.php        → Form custom order
├── login.php / register.php → Autentikasi customer
├── logout.php
├── profil.php / pesanan-saya.php
├── tentang.php / kontak.php
└── README.md
```

\newpage

# BAB IV
# IMPLEMENTASI DAN PENGUJIAN

## 4.1 Spesifikasi Sistem

### Perangkat Keras (Minimum)
- Processor: Intel Dual Core / AMD setara
- RAM: 2 GB
- Storage: 1 GB free space

### Perangkat Lunak
- Sistem Operasi: Windows 10/11, Linux, atau macOS
- Web Server: Apache (dari XAMPP)
- Database: MySQL/MariaDB 5.7+
- PHP: versi 7.4 atau 8.x
- Browser: Chrome, Firefox, Edge (modern browser)

### Stack Teknologi
| Layer | Teknologi |
|---|---|
| Bahasa Backend | PHP Native (PDO) |
| Database | MySQL / MariaDB |
| Framework UI | Bootstrap 5.3.2 |
| Icon | Bootstrap Icons |
| Web Server | Apache |
| Tools Development | XAMPP, phpMyAdmin |

## 4.2 Instalasi

1. **Extract** folder `indra_konveksi` ke `C:\xampp\htdocs\`
2. Jalankan **Apache + MySQL** di XAMPP Control Panel
3. Buka **phpMyAdmin** → buat database `indra_konveksi` → Import `database.sql`
4. Akses di browser:
   - Customer: `http://localhost/indra_konveksi/`
   - Admin: `http://localhost/indra_konveksi/admin/login.php`

### Akun Default
| Role | Username/Email | Password |
|---|---|---|
| Admin | `admin` | `admin123` |
| Customer Demo | `customer@demo.com` | `customer123` |

## 4.3 Hasil Implementasi (Sisi Customer)

### 4.3.0 Identitas Visual (Logo)
Website menggunakan **logo resmi Indra Konveksi** berbentuk lingkaran berwarna biru putih dengan inisial "IK" sebagai identitas brand. Logo ditampilkan di:

- **Navbar customer** (kiri atas) – ikon brand di samping nama toko.
- **Sidebar admin panel** – logo di header sidebar.
- **Halaman login customer & admin** – logo besar di tengah form login.
- **Halaman register & lupa password** – konsistensi brand di seluruh halaman auth.
- **Halaman Tentang** – logo ditampilkan besar (140px) sebagai identitas resmi toko.
- **Footer website** – logo bersama nama toko di kolom pertama.
- **Struk pesanan printable** – logo di header struk untuk profesionalisme.
- **Favicon browser** – logo tampil di tab browser dan saat di-bookmark.

File logo disimpan di `/assets/img/logo.jpg` dan diakses lewat konstanta `LOGO_URL` di `config/config.php` agar mudah diganti dari satu tempat saja.

### 4.3.1 Halaman Beranda
Beranda menampilkan **carousel hero promo** dengan auto-slide 5 detik, indicators, dan tombol prev/next. Di bawahnya terdapat grid kategori produk dan produk unggulan.
*[Lampirkan screenshot beranda di sini]*

### 4.3.2 Halaman Katalog
Menampilkan seluruh produk dengan filter kategori dan kolom pencarian. Setiap kartu produk menampilkan gambar, nama, bahan, harga range, dan tombol Detail.
*[Lampirkan screenshot katalog di sini]*

### 4.3.3 Halaman Detail Produk
Menampilkan informasi lengkap produk: gambar, nama, kategori, harga, bahan, satuan, deskripsi, dan keterangan tambahan. Tersedia tombol **Pesan / Custom Order** dan tombol WhatsApp untuk konsultasi.
*[Lampirkan screenshot detail produk]*

### 4.3.4 Halaman Register & Login
Form register meminta: nama, email, no HP (wajib), alamat (wajib), dan password. Validasi dilakukan baik di sisi client (HTML5 required) maupun sisi server (PHP).
*[Lampirkan screenshot register]*

### 4.3.5 Form Custom Order
Customer dapat memilih produk, mengisi jumlah, bahan, ukuran, warna, catatan custom, dan upload file desain (max 5MB). Setelah submit, sistem akan menyimpan pesanan dan otomatis membuat **link WhatsApp dengan struk pesanan pre-filled**. Pada bagian bawah form ditampilkan **info estimasi waktu pengerjaan: 1 bulan (±30 hari kerja)** agar customer mengetahui jadwal selesai pesanan sejak awal.
*[Lampirkan screenshot custom order]*

### 4.3.6 Halaman Pesanan Saya
Menampilkan riwayat seluruh pesanan customer dengan kolom **Estimasi Selesai** (otomatis dihitung = tanggal pesan + 30 hari) dan tombol "Kirim ke Admin" yang membuka WhatsApp dengan format struk lengkap (termasuk tanggal estimasi selesai).
*[Lampirkan screenshot pesanan saya]*

## 4.4 Hasil Implementasi (Sisi Admin)

### 4.4.1 Halaman Login Admin
Form login terpisah dari customer dengan URL `/admin/login.php`.
*[Lampirkan screenshot login admin]*

### 4.4.2 Dashboard Admin
Menampilkan statistik: Total Produk, Total Kategori, Total Customer, Total Pesanan, Pesanan Baru, Omzet Selesai. Di bawahnya ditampilkan 5 pesanan terakhir.
*[Lampirkan screenshot dashboard]*

### 4.4.3 CRUD Kategori
Admin dapat menambah, mengedit, dan menghapus kategori. Slug URL otomatis dibuat dari nama.
*[Lampirkan screenshot kategori]*

### 4.4.4 CRUD Produk
Tabel produk dengan thumbnail. Admin dapat menambah/edit produk dengan upload gambar, set harga range, kategori, dan status aktif.
*[Lampirkan screenshot produk]*

### 4.4.5 Kelola Pesanan (dengan Search)
Tabel pesanan menampilkan: Kode, Tanggal, Customer, Produk, Jumlah, Total, Status, dan Aksi. Tersedia **kolom pencarian** yang mencari di kode, nama customer, email, nama produk, dan kategori. Filter status juga tersedia.
*[Lampirkan screenshot pesanan + search]*

### 4.4.6 Detail Pesanan
Menampilkan detail lengkap pesanan, data customer, file desain (jika ada), dan **preview struk WhatsApp**. Admin dapat:
- Update status pesanan
- **Kirim struk lengkap ke customer via WhatsApp**
- **Print struk pesanan** (bukti cetak)
*[Lampirkan screenshot detail pesanan]*

### 4.4.7 Print Struk
Halaman cetak struk dengan format A4-friendly: header toko, no pesanan, tanggal, data customer, rincian pesanan (tabel produk), catatan custom, total pembayaran (kuning besar), dan kolom tanda tangan pemesan & admin.
*[Lampirkan screenshot struk cetak]*

### 4.4.8 Kelola Customer
Daftar seluruh customer terdaftar dengan jumlah pesanan masing-masing.
*[Lampirkan screenshot customer]*

### 4.4.9 Pengaturan Situs
Admin dapat mengubah nama situs, no WhatsApp, email, alamat, dan tagline.
*[Lampirkan screenshot pengaturan]*

## 4.5 Pengujian (Black Box Testing)

| No | Skenario Pengujian | Kasus Uji | Hasil Diharapkan | Hasil Aktual | Status |
|----|--------------------|-----------|------------------|--------------|--------|
| 1  | Akses Beranda | Buka `localhost/indra_konveksi/` | Tampil halaman beranda + carousel + kategori + produk | Sesuai | ✓ Valid |
| 2  | Register dengan data lengkap | Isi semua field termasuk no HP & alamat | Akun berhasil dibuat, redirect ke login | Sesuai | ✓ Valid |
| 3  | Register tanpa no HP | Kosongkan no HP | Muncul pesan error "wajib diisi" | Sesuai | ✓ Valid |
| 4  | Register tanpa alamat | Kosongkan alamat | Muncul pesan error "wajib diisi" | Sesuai | ✓ Valid |
| 5  | Login customer benar | customer@demo.com / customer123 | Berhasil login, redirect ke beranda | Sesuai | ✓ Valid |
| 6  | Login customer salah | Password salah | Muncul pesan error | Sesuai | ✓ Valid |
| 7  | Filter katalog by kategori | Pilih dropdown "Topi" | Tampil produk kategori Topi saja | Sesuai | ✓ Valid |
| 8  | Pencarian katalog | Ketik "drill" | Tampil semua produk bahan Drill | Sesuai | ✓ Valid |
| 9  | Submit custom order | Isi form lengkap | Pesanan tersimpan + redirect dengan link WA | Sesuai | ✓ Valid |
| 10 | Upload file desain | Upload JPG 1MB | File tersimpan di /uploads/ | Sesuai | ✓ Valid |
| 11 | Lihat Pesanan Saya | Klik "Pesanan Saya" | Tampil riwayat pesanan customer | Sesuai | ✓ Valid |
| 12 | Klik tombol WA struk | Klik "Kirim ke Admin" | Buka WA dengan struk pre-filled | Sesuai | ✓ Valid |
| 13 | Login Admin | admin / admin123 | Redirect ke dashboard admin | Sesuai | ✓ Valid |
| 14 | Tambah kategori | Isi nama "Test Kategori" | Kategori bertambah di list | Sesuai | ✓ Valid |
| 15 | Tambah produk + gambar | Isi semua field + upload gambar | Produk muncul di katalog | Sesuai | ✓ Valid |
| 16 | Search pesanan by kode | Ketik "IK-2026" | Tampil pesanan dengan kode tsb | Sesuai | ✓ Valid |
| 17 | Search pesanan by customer | Ketik nama customer | Tampil pesanan customer tsb | Sesuai | ✓ Valid |
| 18 | Update status pesanan | Pilih "Diproses" di dropdown | Status berubah, badge warna update | Sesuai | ✓ Valid |
| 19 | Print struk pesanan | Klik tombol Print | Buka tab baru dengan struk siap cetak | Sesuai | ✓ Valid |
| 20 | Kirim struk ke customer | Klik tombol WA di detail | Buka WA customer dengan struk | Sesuai | ✓ Valid |
| 21 | Logout | Klik logout | Session terhapus, redirect login | Sesuai | ✓ Valid |

**Total skenario diuji: 21** · **Berhasil (Valid): 21** · **Persentase keberhasilan: 100%**

\newpage

# BAB V
# PENUTUP

## 5.1 Kesimpulan

Berdasarkan hasil perancangan, implementasi, dan pengujian yang telah dilakukan, dapat disimpulkan bahwa:

1. **Website Katalog & Custom Order Indra Konveksi** berhasil dibangun menggunakan PHP Native (PDO), MySQL, dan Bootstrap 5 dengan total **61 produk** dalam **11 kategori** sesuai katalog asli.

2. **Fitur Customer** yang berhasil diimplementasikan: registrasi (dengan validasi no HP & alamat wajib), login, browsing katalog, filter & pencarian produk, custom order dengan upload desain, riwayat pesanan, dan struk WhatsApp otomatis pre-filled.

3. **Fitur Admin** yang berhasil diimplementasikan: dashboard statistik, CRUD kategori dan produk, kelola pesanan dengan pencarian multi-field (kode/customer/produk/kategori), update status, **print struk pesanan**, **chat customer dengan struk lengkap via WhatsApp**, kelola customer, dan pengaturan situs.

4. Integrasi WhatsApp berfungsi dengan baik menggunakan link `wa.me/...` dengan teks struk yang otomatis terisi (URL-encoded).

5. **21 skenario pengujian** (black box) berjalan **100% valid**.

## 5.2 Saran

1. **Pengembangan lebih lanjut** dapat menambahkan:
   - Integrasi payment gateway (Midtrans, Xendit, dll) untuk pembayaran digital.
   - Notifikasi WhatsApp otomatis ke admin via WhatsApp Business API (Fonnte/Wablas).
   - Fitur cetak invoice PDF (selain struk).
   - Fitur diskon otomatis berdasarkan quantity (mis: >100 pcs diskon 5–15 ribu).
   - Multi-gambar untuk satu produk (galeri).
   - Reset password customer via email.

2. **Untuk production / deploy online**, perlu:
   - Hosting dengan SSL (HTTPS).
   - Konfigurasi SMTP untuk email yang reliabel (PHPMailer + SendGrid/Mailtrap).
   - Backup database otomatis (cron job).
   - Hardening keamanan tambahan (CSRF token, rate limiting login).

3. **Penyusun menyarankan** kepada pemilik usaha Indra Konveksi untuk segera memanfaatkan website ini secara online supaya jangkauan pemasaran dapat lebih luas dan profesional.

\newpage

## DAFTAR PUSTAKA

1. PHP Group. (2026). *PHP Manual*. Diakses dari https://www.php.net/manual/en/
2. Oracle Corporation. (2026). *MySQL 8.0 Reference Manual*. Diakses dari https://dev.mysql.com/doc/
3. Bootstrap Team. (2026). *Bootstrap 5 Documentation*. Diakses dari https://getbootstrap.com/docs/5.3/
4. Apache Friends. (2026). *XAMPP Documentation*. Diakses dari https://www.apachefriends.org/docs/
5. Mozilla Developer Network. (2026). *Web Docs HTML, CSS, JavaScript*. Diakses dari https://developer.mozilla.org/
6. Indra Konveksi. (2026). *Katalog Produk*. Bengkulu.

\newpage

## LAMPIRAN

### Lampiran 1: Source Code
Seluruh source code tersedia di folder project `indra_konveksi/`.

### Lampiran 2: Database SQL
Skrip SQL untuk pembuatan tabel & data awal terdapat di file `database.sql`.

### Lampiran 3: Screenshot
Lampirkan screenshot tiap halaman website (beranda, katalog, detail produk, register, login, custom order, pesanan saya, dashboard admin, kelola produk, kelola pesanan, print struk, dll).

### Lampiran 4: Tabel Produk Asli (Seed)
Total 61 produk awal yang sudah di-seed di database:

| Kategori | Jumlah Varian |
|---|---|
| Topi | 13 varian (Baseball, Rimba, Trucker, Bludru Dinas) |
| ID Card / Member Card | 3 varian |
| Lanyard | 2 varian |
| Almamater | 1 varian |
| Rompi / Vest | 7 varian (Dril, American Dril, Micro, Taslan, Ripstop, Nagata, Kanvas) |
| Kaos | 9 varian (Cotton Bambo, Combed Terry, 30S, 24S, 20S, Carded, PE Double, PE Single, Papa Jeans) |
| Seragam Sekolah TK | 3 varian |
| Seragam Sekolah SD/MI | 6 varian |
| Seragam Sekolah SMP/MTS | 5 varian |
| Seragam Sekolah SMA/SMK/MA | 5 varian |
| Jaket | 2 varian (Drill, Katun) |
| PDH | 1 varian |
| Batik | 1 varian (Basurek) |
| Seragam Dinas / TNI-POLRI | 4 varian (Dinas, TNI/POLRI, Praktek, Selempang) |
| **TOTAL** | **61 produk** |

---

**---SELESAI---**
