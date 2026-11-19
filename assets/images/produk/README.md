# 📸 Stock Photos untuk Produk Indra Konveksi

Folder ini berisi **28 foto stock gratis** yang sudah dikurasi sesuai 11 kategori produk Indra Konveksi.

## ✅ Lisensi & Hak Pakai

Semua foto berasal dari **Unsplash** (https://unsplash.com/license) dengan lisensi:
- ✅ **GRATIS** untuk penggunaan komersial maupun non-komersial
- ✅ **TIDAK perlu** atribusi (tapi sangat disarankan untuk apresiasi fotografer)
- ✅ **Boleh dimodifikasi** (crop, resize, edit, tambah watermark, dll)
- ❌ Tidak boleh dijual ulang sebagai foto stock identik

> Disarankan untuk **tetap mencantumkan attribution** di footer website / halaman tentang sebagai bentuk apresiasi.

## 📁 Daftar File per Kategori

| Kategori | File | Total |
|----------|------|-------|
| **Topi** | `topi-baseball-1.jpg`, `topi-trucker-2.jpg`, `topi-rimba-3.jpg` | 3 |
| **Kaos** | `kaos-polos-1.jpg`, `kaos-folded-2.jpg`, `kaos-warna-3.jpg`, `kaos-raglan-4.jpg` | 4 |
| **Jaket** | `jaket-denim-1.jpg`, `jaket-bomber-2.jpg`, `jaket-windbreaker-3.jpg` | 3 |
| **Batik** | `batik-pattern-1.jpg`, `batik-traditional-2.jpg`, `batik-shirt-3.jpg` | 3 |
| **Seragam Sekolah** | `seragam-sd-1.jpg`, `seragam-smp-2.jpg`, `seragam-sma-3.jpg` | 3 |
| **PDH** | `pdh-drill-1.jpg`, `pdh-formal-2.jpg` | 2 |
| **Seragam Dinas / TNI-POLRI** | `dinas-uniform-1.jpg`, `dinas-military-2.jpg` | 2 |
| **Almamater** | `almamater-graduation-1.jpg`, `almamater-gown-2.jpg` | 2 |
| **Rompi / Vest** | `rompi-outdoor-1.jpg`, `rompi-safety-2.jpg` | 2 |
| **ID Card / Member Card** | `idcard-badge-1.jpg`, `idcard-employee-2.jpg` | 2 |
| **Lanyard** | `lanyard-strap-1.jpg`, `lanyard-badge-2.jpg` | 2 |
| | **TOTAL** | **28** |

## 🎨 Spesifikasi Foto

- **Resolusi**: 800×800 px (square crop, optimal untuk product grid)
- **Format**: JPEG (Q80, kompresi optimal)
- **Ukuran**: 35–200 KB per foto (web-optimized)
- **Total folder**: ±2.6 MB

## 🚀 Cara Menggunakan di Website

### Opsi 1: Manual Upload via Admin Panel
1. Login admin → menu **Produk** → klik edit produk
2. Pada field "Gambar", klik **Browse**, pilih foto sesuai produk (mis: untuk produk "Kaos Polos Cotton" pakai `kaos-polos-1.jpg`)
3. Klik Simpan — foto akan otomatis ter-copy ke folder `/uploads/`

### Opsi 2: Bulk Copy ke /uploads/ (Manual via File Manager)
```bash
# Linux/Mac:
cp /app/indra_konveksi/assets/images/produk/*.jpg /app/indra_konveksi/uploads/

# Windows XAMPP:
copy C:\xampp\htdocs\indra_konveksi\assets\images\produk\*.jpg C:\xampp\htdocs\indra_konveksi\uploads\
```

Lalu update kolom `gambar` di tabel `produk` via phpMyAdmin sesuai nama file.

### Opsi 3: Gunakan Langsung sebagai URL (Tanpa Copy)
Di kolom `gambar` database isi dengan path relatif:
```
assets/images/produk/kaos-polos-1.jpg
```
Catatan: fungsi `gambar_produk()` di `includes/functions.php` perlu disesuaikan untuk support path ini.

## 📝 Atribusi (Opsional tapi Direkomendasikan)

Tambahkan di footer website atau halaman Tentang:

> "Beberapa foto produk yang ditampilkan adalah ilustrasi/mockup dari Unsplash.com untuk keperluan demo."

## 🔄 Bila Ingin Ganti / Tambah Foto

1. Buka https://unsplash.com → search keyword (mis: "polo shirt mockup", "school uniform indonesia")
2. Klik foto → klik tombol **Download free** (resolusi medium/small)
3. Save ke folder `/app/indra_konveksi/assets/images/produk/` dengan nama deskriptif
4. Update database `produk.gambar` sesuai nama file baru

## ⚠️ Untuk Produksi (Bisnis Sebenarnya)

Foto-foto ini cocok untuk **demo presentasi tugas kampus**. Untuk bisnis Indra Konveksi sebenarnya, sangat disarankan:

1. **Foto produk asli** ambil pakai HP/kamera di showroom toko
2. **Pakai mockup** dari template Photoshop/Canva untuk hasil studio-quality
3. **Hire fotografer** untuk katalog produk profesional (~Rp 50–200rb per produk)

---
*File ini bagian dari project "Website Katalog & Custom Order Indra Konveksi"*
*Generated: <?= date pada saat dibuat ?>*
