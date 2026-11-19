-- =====================================================
-- DATABASE: indra_konveksi
-- Website Katalog + Custom Order
-- =====================================================
-- PETUNJUK INFINITYFREE:
-- 1. Buka panel InfinityFree → MySQL Databases → phpMyAdmin
-- 2. Pilih database yang sudah Anda buat
-- 3. Klik tab "SQL" / "Import"
-- 4. Paste / upload file SQL ini
-- 5. Klik "Go" untuk menjalankan
-- =====================================================
-- JANGAN buat database dari SQL, karena di InfinityFree
-- database harus dibuat dari panel control.
-- =====================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- =====================================================
-- TABEL: admin
-- =====================================================
CREATE TABLE IF NOT EXISTS `admin` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `nama` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default admin (password: admin123)
INSERT INTO `admin` (`username`, `password`, `nama`) VALUES
('admin', '$2b$10$X4ET8hpheI095bWyraNkYef7jZCmj1kKkZSWWgn5OdGTJJkZKM1ha', 'Administrator');

-- =====================================================
-- TABEL: users (customer)
-- =====================================================
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NULL,
  `no_hp` VARCHAR(20),
  `alamat` TEXT,
  `google_id` VARCHAR(100) NULL UNIQUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Customer demo (password: customer123)
INSERT INTO `users` (`nama`, `email`, `password`, `no_hp`, `alamat`) VALUES
('Customer Demo', 'customer@demo.com', '$2b$10$0hrPT.5VppX7kqA0DIerR.EqJ2e2lTOHhtg88m2suBdQECCs.jau6', '081234567890', 'Jl. Contoh No. 1, Bengkulu');

-- =====================================================
-- TABEL: kategori
-- =====================================================
CREATE TABLE IF NOT EXISTS `kategori` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `deskripsi` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `kategori` (`nama`, `slug`, `deskripsi`) VALUES
('Topi', 'topi', 'Berbagai jenis topi: baseball, rimba, trucker, bludru dinas'),
('ID Card / Member Card', 'id-card', 'Cetak ID Card PVC + casing + tali/yoyo'),
('Lanyard', 'lanyard', 'Lanyard panjang/pendek bahan tissue 2cm'),
('Almamater', 'almamater', 'Almamater kampus/sekolah custom'),
('Rompi / Vest', 'rompi-vest', 'Rompi outdoor/lapangan berbagai bahan'),
('Kaos', 'kaos', 'Kaos standar, raglan, berbagai bahan cotton'),
('Seragam Sekolah', 'seragam-sekolah', 'Seragam TK, SD/MI, SMP/MTS, SMA/SMK/MA'),
('Jaket', 'jaket', 'Jaket drill, katun, custom desain'),
('PDH', 'pdh', 'Baju PDH bahan drill lengan panjang'),
('Batik', 'batik', 'Batik basurek, batik sungai limau'),
('Seragam Dinas / TNI-POLRI', 'seragam-dinas', 'Seragam dinas instansi, TNI, POLRI, praktek');

-- =====================================================
-- TABEL: produk
-- =====================================================
CREATE TABLE IF NOT EXISTS `produk` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `id_kategori` INT NOT NULL,
  `nama` VARCHAR(200) NOT NULL,
  `bahan` VARCHAR(100),
  `harga_min` INT NOT NULL DEFAULT 0,
  `harga_max` INT DEFAULT NULL,
  `satuan` VARCHAR(30) DEFAULT 'pcs',
  `deskripsi` TEXT,
  `keterangan` TEXT,
  `gambar` VARCHAR(255) DEFAULT 'default.png',
  `stok_tersedia` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`id_kategori`) REFERENCES `kategori`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed produk
INSERT INTO `produk` (`id_kategori`, `nama`, `bahan`, `harga_min`, `harga_max`, `satuan`, `deskripsi`, `keterangan`) VALUES
-- TOPI (id_kategori=1)
(1, 'Topi Baseball Sakura', 'Sakura', 47000, NULL, 'pcs', 'Topi baseball bahan sakura, cocok untuk komunitas/event.', 'Min order 12-24 pcs. Sudah termasuk bordir 1 titik.'),
(1, 'Topi Rimba Sakura', 'Sakura', 55000, NULL, 'pcs', 'Topi rimba bahan sakura.', 'Min order 12-24 pcs.'),
(1, 'Topi Baseball Drill', 'Drill', 50000, NULL, 'pcs', 'Topi baseball bahan drill premium.', 'Sudah termasuk furing peles/satin daleman.'),
(1, 'Topi Rimba Drill', 'Drill', 58000, NULL, 'pcs', 'Topi rimba bahan drill.', 'Tahan lama.'),
(1, 'Topi Baseball Ribstop', 'Ribstop', 55000, NULL, 'pcs', 'Topi baseball ribstop premium.', 'Recommended seller.'),
(1, 'Topi Rimba Ribstop', 'Ribstop', 65000, NULL, 'pcs', 'Topi rimba ribstop.', 'Tahan air.'),
(1, 'Topi Baseball Twil', 'Twil', 60000, NULL, 'pcs', 'Topi baseball twil.', 'Bahan kuat & rapi.'),
(1, 'Topi Rimba Twil', 'Twil', 70000, NULL, 'pcs', 'Topi rimba twil.', 'Ideal untuk lapangan.'),
(1, 'Topi Baseball Kanvas Marsoto', 'Kanvas Marsoto', 65000, NULL, 'pcs', 'Topi baseball kanvas marsoto.', 'Bahan tebal premium.'),
(1, 'Topi Rimba Kanvas Marsoto', 'Kanvas Marsoto', 75000, NULL, 'pcs', 'Topi rimba kanvas marsoto.', 'Best seller.'),
(1, 'Topi Trucker Jaring Troya', 'Jaring Troya', 45000, NULL, 'pcs', 'Topi trucker dengan jaring di belakang.', 'Sirkulasi udara baik.'),
(1, 'Topi Dinas Bludru Jaring', 'Jaring + Bludru', 70000, NULL, 'pcs', 'Topi dinas bahan jaring full bordir depan.', 'Untuk instansi/komunitas.'),
(1, 'Topi Dinas Laken Bludru', 'Laken Bludru', 85000, NULL, 'pcs', 'Topi dinas bahan laken bludru full bordir depan.', 'Premium quality.'),

-- ID CARD (id_kategori=2)
(2, 'Cetak ID Card Only (5-20 pcs)', 'PVC', 10000, NULL, 'pcs', 'Cetak ID card bahan PVC 2 muka atau 1 muka.', 'Kuat, tidak mudah luntur & awet. Min 5 pcs.'),
(2, 'Paket ID Card + Casing + Yoyo', 'PVC + Casing + Yoyo', 15000, NULL, 'pcs', 'Paket lengkap ID card.', 'Termasuk casing & yoyo.'),
(2, 'Paket ID Card + Casing + Tali', 'PVC + Casing + Tali', 15000, NULL, 'pcs', 'Paket ID card + tali/lanyard.', 'Termasuk casing & tali.'),

-- LANYARD (id_kategori=3)
(3, 'Lanyard Panjang Tissue 2cm', 'Tissue 2cm', 16000, 24000, 'pcs', 'Lanyard panjang ±40cm, full color custom.', 'Gratis desain, dibungkus persatu. Harga tergantung jumlah.'),
(3, 'Lanyard Pendek / Gantungan Kunci', 'Tissue', 13000, NULL, 'pcs', 'Lanyard pendek 12cm untuk gantungan kunci/HP.', 'Gratis desain.'),

-- ALMAMATER (id_kategori=4)
(4, 'Almamater Drill Lengan Panjang', 'Drill', 200000, NULL, 'lembar', 'Almamater kampus/sekolah custom bordir/sablon.', 'Bahan drill premium, lengan panjang.'),

-- ROMPI / VEST (id_kategori=5)
(5, 'Rompi Dril Jayeng/Kantata', 'Dril Jayeng/Kantata', 116000, 120000, 'pcs', 'Rompi outdoor bahan dril.', 'Min 12-24 pcs Rp120k, 24-62 pcs Rp116k.'),
(5, 'Rompi American Dril', 'American Dril', 123000, 125000, 'pcs', 'Rompi premium American Dril.', 'Best seller.'),
(5, 'Rompi Micro + Furing', 'Micro + Furing', 147000, 150000, 'pcs', 'Rompi micro dengan furing dalam.', 'Nyaman dipakai.'),
(5, 'Rompi Taslan JN/Milky + Furing', 'Taslan + Furing', 157000, 160000, 'pcs', 'Rompi taslan tahan air + furing.', 'Outdoor friendly.'),
(5, 'Rompi Ripstop', 'Ripstop', 132000, 135000, 'pcs', 'Rompi ripstop tahan lama.', 'Recommended.'),
(5, 'Rompi Nagata', 'Nagata', 132000, 135000, 'pcs', 'Rompi bahan Nagata.', 'Premium.'),
(5, 'Rompi Kanvas Marsoto', 'Kanvas Marsoto', 163000, 165000, 'pcs', 'Rompi kanvas marsoto tebal.', 'Heavy duty.'),

-- KAOS (id_kategori=6)
(6, 'Kaos Cotton Bambo Soft', 'Cotton Bambo Soft', 110000, NULL, 'pcs', 'Kaos premium cotton bambo soft.', 'Lembut & adem.'),
(6, 'Kaos Cotton Combed Terry', 'Cotton Combed Terry', 105000, NULL, 'pcs', 'Kaos cotton combed terry.', 'Standar konveksi.'),
(6, 'Kaos Cotton Combed 30S', 'Cotton Combed 30S', 91000, NULL, 'pcs', 'Kaos combed 30S best seller.', 'Tipis nyaman.'),
(6, 'Kaos Cotton Combed 24S', 'Cotton Combed 24S', 96000, NULL, 'pcs', 'Kaos combed 24S.', 'Medium weight.'),
(6, 'Kaos Cotton Combed 20S', 'Cotton Combed 20S', 101000, NULL, 'pcs', 'Kaos combed 20S tebal.', 'Best seller.'),
(6, 'Kaos Cotton Carded', 'Cotton Carded', 85000, NULL, 'pcs', 'Kaos cotton carded ekonomis.', 'Untuk sablon massal.'),
(6, 'Kaos PE Double', 'PE Double', 75000, NULL, 'pcs', 'Kaos PE double.', 'Murah meriah.'),
(6, 'Kaos PE Single', 'PE Single', 70000, NULL, 'pcs', 'Kaos PE single.', 'Untuk event/promosi.'),
(6, 'Kaos Papa Jeans', 'Jeans', 160000, NULL, 'lembar', 'Kaos bahan jeans lengan panjang.', 'Unik & tahan lama.'),

-- SERAGAM SEKOLAH (id_kategori=7)
(7, 'Seragam TK Nasional', 'Pamatex/Expo/Drill', 250000, NULL, 'setel', 'Seragam TK Nasional lengan panjang.', 'Setel lengkap.'),
(7, 'Seragam TK Batik', 'Pamatex/Expo/Drill', 190000, NULL, 'setel', 'Seragam batik TK.', 'Setel lengkap.'),
(7, 'Seragam TK Olahraga', 'Loto / PE', 140000, NULL, 'setel', 'Seragam olahraga TK.', 'Lengan panjang.'),
(7, 'Seragam SD Putih-Merah', 'Pamatex/Expo/Drill', 230000, NULL, 'setel', 'Seragam SD/MI Putih-Merah.', 'Setel lengkap.'),
(7, 'Seragam SD Pramuka', 'Pamatex/Expo/Drill', 230000, NULL, 'setel', 'Seragam Pramuka SD/MI.', 'Setel lengkap.'),
(7, 'Seragam SD Batik Sungai Limau', 'Pamatex/Expo/Drill', 180000, 230000, 'setel', 'Batik Sungai Limau SD.', 'Tersedia 2 model harga.'),
(7, 'Seragam SD Olahraga', 'Loto / PE', 180000, NULL, 'setel', 'Olahraga SD/MI.', 'Lengan panjang.'),
(7, 'Seragam SD Muslim', 'Cantik', 230000, NULL, 'setel', 'Seragam muslim SD/MI.', 'Setel lengkap.'),
(7, 'Seragam SMP Putih-Biru', 'Pamatex/Expo/Drill', 250000, NULL, 'setel', 'Seragam SMP/MTS Putih-Biru.', 'Setel lengkap.'),
(7, 'Seragam SMP Pramuka', 'Pamatex/Expo/Drill', 250000, NULL, 'setel', 'Seragam Pramuka SMP/MTS.', 'Setel lengkap.'),
(7, 'Seragam SMP Batik Sungai Limau', 'Pamatex/Expo/Drill', 200000, 280000, 'setel', 'Batik SMP.', 'Tersedia 2 model harga.'),
(7, 'Seragam SMP Olahraga', 'Loto / PE', 180000, NULL, 'setel', 'Olahraga SMP/MTS.', 'Lengan panjang.'),
(7, 'Seragam SMP Muslim', 'Cantik', 250000, NULL, 'setel', 'Seragam muslim SMP/MTS.', 'Setel lengkap.'),
(7, 'Seragam SMA Putih-Abu', 'Pamatex/Expo/Drill', 270000, NULL, 'setel', 'Seragam SMK/SMU/MA Putih-Abu.', 'Setel lengkap.'),
(7, 'Seragam SMA Pramuka', 'Pamatex/Expo/Drill', 270000, NULL, 'setel', 'Seragam Pramuka SMA.', 'Setel lengkap.'),
(7, 'Seragam SMA Batik Sungai Limau', 'Pamatex/Expo/Drill', 230000, 300000, 'setel', 'Batik SMA.', 'Tersedia 2 model harga.'),
(7, 'Seragam SMA Olahraga', 'Loto / PE', 180000, NULL, 'setel', 'Olahraga SMA.', 'Lengan panjang.'),
(7, 'Seragam SMA Muslim', 'Cantik', 290000, NULL, 'setel', 'Seragam muslim SMA.', 'Setel lengkap.'),

-- JAKET (id_kategori=8)
(8, 'Jaket Drill Lengan Panjang', 'Drill', 205000, NULL, 'lembar', 'Jaket drill custom desain.', 'Lengan panjang, tebal.'),
(8, 'Jaket Katun Lengan Panjang', 'Katun', 185000, NULL, 'lembar', 'Jaket katun nyaman.', 'Bisa custom logo.'),

-- PDH (id_kategori=9)
(9, 'Baju PDH Drill Lengan Panjang', 'Drill', 200000, NULL, 'lembar', 'Baju PDH instansi bahan drill.', 'Lengan panjang, rapi.'),

-- BATIK (id_kategori=10)
(10, 'Batik Basurek Katun', 'Katun', 175000, NULL, 'lembar', 'Batik Basurek khas Bengkulu.', 'Lengan panjang.'),

-- SERAGAM DINAS / TNI-POLRI (id_kategori=11)
(11, 'Seragam Dinas Lengkap', 'Drill', 500000, NULL, 'setel', 'Seragam dinas instansi pemerintah.', 'Setel lengkap, lengan panjang.'),
(11, 'Seragam TNI / POLRI', 'Drill Premium', 550000, NULL, 'setel', 'Seragam TNI/POLRI standar.', 'Setel lengkap.'),
(11, 'Seragam Praktek Drill', 'Drill', 320000, NULL, 'setel', 'Seragam praktek mahasiswa/siswa.', 'Setel drill lengan panjang.'),
(11, 'Selempang Beludru', 'Beludru', 120000, NULL, 'lembar', 'Selempang bahan beludru untuk dinas/acara.', 'Custom bordir nama/logo.');

-- =====================================================
-- TABEL: pesanan (custom order)
-- =====================================================
CREATE TABLE IF NOT EXISTS `pesanan` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `kode` VARCHAR(30) NOT NULL UNIQUE,
  `id_user` INT NOT NULL,
  `id_produk` INT NOT NULL,
  `jumlah` INT NOT NULL DEFAULT 1,
  `bahan` VARCHAR(100),
  `ukuran` VARCHAR(50),
  `warna` VARCHAR(50),
  `catatan` TEXT,
  `file_desain` VARCHAR(255),
  `total_harga` BIGINT NOT NULL DEFAULT 0,
  `status` ENUM('Baru','Diproses','Selesai','Dibatalkan') NOT NULL DEFAULT 'Baru',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`id_user`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`id_produk`) REFERENCES `produk`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABEL: produk_gambar (galeri multi-image)
-- =====================================================
CREATE TABLE IF NOT EXISTS `produk_gambar` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `id_produk` INT NOT NULL,
  `gambar` VARCHAR(255) NOT NULL,
  `urutan` INT DEFAULT 0,
  FOREIGN KEY (`id_produk`) REFERENCES `produk`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABEL: reset_token (lupa password)
-- =====================================================
CREATE TABLE IF NOT EXISTS `reset_token` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `id_user` INT NOT NULL,
  `token` VARCHAR(100) UNIQUE NOT NULL,
  `expires_at` DATETIME NOT NULL,
  `used` TINYINT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`id_user`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABEL: login_attempts (rate limit)
-- =====================================================
CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `ip` VARCHAR(45) NOT NULL,
  `type` ENUM('admin','user') DEFAULT 'user',
  `success` TINYINT DEFAULT 0,
  `attempted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_ip_time (`ip`, `attempted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =====================================================
-- TABEL: pengaturan
-- =====================================================
CREATE TABLE IF NOT EXISTS `pengaturan` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `nama_situs` VARCHAR(100) DEFAULT 'Indra Konveksi',
  `wa_admin` VARCHAR(20) DEFAULT '628123456789',
  `email_admin` VARCHAR(100) DEFAULT 'admin@indrakonveksi.com',
  `alamat` TEXT,
  `tagline` VARCHAR(255) DEFAULT 'Konveksi & Sablon Terpercaya di Bengkulu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `pengaturan` (`nama_situs`, `wa_admin`, `email_admin`, `alamat`, `tagline`) VALUES
('Indra Konveksi', '628123456789', 'admin@indrakonveksi.com', 'Jl. Batang Hari No. 3D, Kel. Nusa, Kec. Ratu Agung, Kota Bengkulu', 'Konveksi & Sablon Terpercaya di Bengkulu - Bebas Kustom Segala Model');


SET FOREIGN_KEY_CHECKS = 1;
