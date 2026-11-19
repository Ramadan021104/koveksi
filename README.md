# Indra Konveksi - Website Katalog & Custom Order

Website katalog produk konveksi (topi, ID card, lanyard, almamater, rompi, kaos, seragam sekolah, jaket, PDH, batik, seragam dinas/TNI-POLRI) dengan fitur **custom order**.

Dibangun menggunakan **PHP Native (PDO)** + **MySQL** + **Bootstrap 5**.

---

## Fitur

### Customer
- Register & login akun customer
- Browsing katalog produk dengan filter kategori & pencarian
- Lihat detail produk + harga
- Buat **Custom Order** (dengan upload desain, pilih bahan, ukuran, jumlah, catatan)
- Notifikasi order ke WhatsApp admin (auto-redirect)
- Email konfirmasi order (opsional, via PHP mail())
- Riwayat pesanan di halaman profil

### Admin
- Login admin terpisah
- Dashboard statistik (total produk, pesanan, customer, pesanan baru)
- CRUD Kategori
- CRUD Produk (upload gambar)
- Kelola Pesanan (lihat detail, update status: Baru / Diproses / Selesai / Dibatalkan)
- Kelola User Customer
- Pengaturan situs (nomor WhatsApp, email, alamat)

---

## Cara Instalasi

### 1. Prasyarat
- XAMPP / Laragon / WAMP (PHP >= 7.4, MySQL >= 5.7)
- Browser modern

### 2. Setup

1. **Extract** folder `indra_konveksi` ke dalam folder `htdocs` XAMPP:
   ```
   C:\xampp\htdocs\indra_konveksi
   ```

2. **Jalankan Apache & MySQL** di XAMPP Control Panel.

3. **Buat Database**:
   - Buka `http://localhost/phpmyadmin`
   - Klik **New**, buat database baru dengan nama: `indra_konveksi`
   - Pilih database tersebut, klik tab **Import**, upload file `database.sql` dari folder project.
   - Klik **Go**. Data sample + tabel akan otomatis dibuat.

4. **Konfigurasi Koneksi** (opsional, default sudah benar untuk XAMPP):
   - Buka file `config/db.php`
   - Sesuaikan `$host`, `$db`, `$user`, `$pass` jika berbeda.

5. **Konfigurasi Situs**:
   - Buka `config/config.php`
   - Ubah `WA_ADMIN` (nomor WhatsApp), `EMAIL_ADMIN`, `ALAMAT`.

6. **Akses Website**:
   - User: `http://localhost/indra_konveksi/`
   - Admin: `http://localhost/indra_konveksi/admin/login.php`

---

## Akun Default

### Admin
- **Username:** `admin`
- **Password:** `admin123`

### Customer Demo
- **Email:** `customer@demo.com`
- **Password:** `customer123`

---

## Struktur Folder

```
indra_konveksi/
├── admin/                # Panel admin
│   ├── includes/         # Header/sidebar/footer admin
│   ├── index.php         # Dashboard
│   ├── login.php
│   ├── produk.php        # CRUD produk
│   ├── kategori.php
│   ├── pesanan.php
│   ├── users.php
│   └── pengaturan.php
├── assets/
│   ├── css/style.css
│   ├── js/script.js
│   └── images/
├── config/
│   ├── db.php            # Koneksi PDO MySQL
│   └── config.php        # Konfigurasi situs
├── includes/
│   ├── header.php
│   ├── footer.php
│   ├── auth.php          # Helper auth customer
│   └── functions.php     # Helper umum
├── uploads/              # File upload (gambar produk, desain custom)
├── database.sql          # SQL schema + seed data
├── index.php             # Landing page
├── katalog.php
├── produk.php
├── custom-order.php
├── login.php
├── register.php
├── logout.php
├── profil.php
├── pesanan-saya.php
├── tentang.php
└── kontak.php
```

---

## Catatan

- Folder `uploads/` harus **writable** (chmod 777 di Linux). Di XAMPP Windows biasanya sudah bisa.
- Fitur email menggunakan `mail()` PHP — di localhost XAMPP perlu config SMTP di `php.ini` (atau gunakan Mailtrap/PHPMailer). Default-nya disabled saat localhost, hanya simpan ke DB + redirect WA.
- Project ini untuk **tugas kampus / pembelajaran**. Production siap setelah hardening keamanan tambahan.

---

**Indra Konveksi — Bengkulu**
