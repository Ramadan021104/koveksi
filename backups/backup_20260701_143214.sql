-- Indra Konveksi Backup
-- Generated: 2026-07-01 14:32:14

SET FOREIGN_KEY_CHECKS=0;

-- Table: admin
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `admin` (`id`,`username`,`password`,`nama`,`created_at`) VALUES ('1','admin','$2b$10$X4ET8hpheI095bWyraNkYef7jZCmj1kKkZSWWgn5OdGTJJkZKM1ha','Administrator','2026-06-26 01:58:28');

-- Table: kategori
DROP TABLE IF EXISTS `kategori`;
CREATE TABLE `kategori` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `kategori` (`id`,`nama`,`slug`,`deskripsi`,`created_at`) VALUES ('1','Topi','topi','Berbagai jenis topi: baseball, rimba, trucker, bludru dinas','2026-06-26 01:58:28');
INSERT INTO `kategori` (`id`,`nama`,`slug`,`deskripsi`,`created_at`) VALUES ('2','ID Card / Member Card','id-card','Cetak ID Card PVC + casing + tali/yoyo','2026-06-26 01:58:28');
INSERT INTO `kategori` (`id`,`nama`,`slug`,`deskripsi`,`created_at`) VALUES ('3','Lanyard','lanyard','Lanyard panjang/pendek bahan tissue 2cm','2026-06-26 01:58:28');
INSERT INTO `kategori` (`id`,`nama`,`slug`,`deskripsi`,`created_at`) VALUES ('4','Almamater','almamater','Almamater kampus/sekolah custom','2026-06-26 01:58:28');
INSERT INTO `kategori` (`id`,`nama`,`slug`,`deskripsi`,`created_at`) VALUES ('5','Rompi / Vest','rompi-vest','Rompi outdoor/lapangan berbagai bahan','2026-06-26 01:58:28');
INSERT INTO `kategori` (`id`,`nama`,`slug`,`deskripsi`,`created_at`) VALUES ('6','Kaos','kaos','Kaos standar, raglan, berbagai bahan cotton','2026-06-26 01:58:28');
INSERT INTO `kategori` (`id`,`nama`,`slug`,`deskripsi`,`created_at`) VALUES ('7','Seragam Sekolah','seragam-sekolah','Seragam TK, SD/MI, SMP/MTS, SMA/SMK/MA','2026-06-26 01:58:28');
INSERT INTO `kategori` (`id`,`nama`,`slug`,`deskripsi`,`created_at`) VALUES ('8','Jaket','jaket','Jaket drill, katun, custom desain','2026-06-26 01:58:28');
INSERT INTO `kategori` (`id`,`nama`,`slug`,`deskripsi`,`created_at`) VALUES ('9','PDH','pdh','Baju PDH bahan drill lengan panjang','2026-06-26 01:58:28');
INSERT INTO `kategori` (`id`,`nama`,`slug`,`deskripsi`,`created_at`) VALUES ('10','Batik','batik','Batik basurek, batik sungai limau','2026-06-26 01:58:28');
INSERT INTO `kategori` (`id`,`nama`,`slug`,`deskripsi`,`created_at`) VALUES ('11','Seragam Dinas / TNI-POLRI','seragam-dinas','Seragam dinas instansi, TNI, POLRI, praktek','2026-06-26 01:58:28');

-- Table: login_attempts
DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(45) NOT NULL,
  `type` enum('admin','user') DEFAULT 'user',
  `success` tinyint(4) DEFAULT 0,
  `attempted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_ip_time` (`ip`,`attempted_at`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table: pengaturan
DROP TABLE IF EXISTS `pengaturan`;
CREATE TABLE `pengaturan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_situs` varchar(100) DEFAULT 'Indra Konveksi',
  `wa_admin` varchar(20) DEFAULT '628123456789',
  `email_admin` varchar(100) DEFAULT 'admin@indrakonveksi.com',
  `alamat` text DEFAULT NULL,
  `tagline` varchar(255) DEFAULT 'Konveksi & Sablon Terpercaya di Bengkulu',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `pengaturan` (`id`,`nama_situs`,`wa_admin`,`email_admin`,`alamat`,`tagline`) VALUES ('1','Indra Konveksi','6285367388814','admin@indrakonveksi.com','Jl. Samsul Bahrun NO 34 RT 01 RW 04','Konveksi & Sablon Terpercaya di Bengkulu - Bebas Kustom Segala Model');

-- Table: pesanan
DROP TABLE IF EXISTS `pesanan`;
CREATE TABLE `pesanan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kode` varchar(30) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_produk` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL DEFAULT 1,
  `bahan` varchar(100) DEFAULT NULL,
  `ukuran` varchar(50) DEFAULT NULL,
  `warna` varchar(50) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `file_desain` varchar(255) DEFAULT NULL,
  `total_harga` bigint(20) NOT NULL DEFAULT 0,
  `status` enum('Baru','Diproses','Selesai','Dibatalkan') NOT NULL DEFAULT 'Baru',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode` (`kode`),
  KEY `id_user` (`id_user`),
  KEY `id_produk` (`id_produk`),
  CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pesanan_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `pesanan` (`id`,`kode`,`id_user`,`id_produk`,`jumlah`,`bahan`,`ukuran`,`warna`,`catatan`,`file_desain`,`total_harga`,`status`,`created_at`) VALUES ('2','IK-20260628-E6302','1','19','6','','S: 1, M: 1, L: 1, XL: 1, XXL: 1, XXXL: 1','','','desain_1782654030_ae08e61c.jpg','1200000','Selesai','2026-06-28 20:40:30');
INSERT INTO `pesanan` (`id`,`kode`,`id_user`,`id_produk`,`jumlah`,`bahan`,`ukuran`,`warna`,`catatan`,`file_desain`,`total_harga`,`status`,`created_at`) VALUES ('3','IK-20260629-E1308','2','19','1','','M: 1','','',NULL,'200000','Baru','2026-06-29 19:58:58');

-- Table: produk
DROP TABLE IF EXISTS `produk`;
CREATE TABLE `produk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_kategori` int(11) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `bahan` varchar(100) DEFAULT NULL,
  `harga_min` int(11) NOT NULL DEFAULT 0,
  `harga_max` int(11) DEFAULT NULL,
  `satuan` varchar(30) DEFAULT 'pcs',
  `deskripsi` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `gambar` varchar(255) DEFAULT 'default.png',
  `stok_tersedia` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `id_kategori` (`id_kategori`),
  CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('1','1','Topi Baseball Sakura','Sakura','47000',NULL,'pcs','Topi baseball bahan sakura, cocok untuk komunitas/event.','Min order 12-24 pcs. Sudah termasuk bordir 1 titik.','produk_1782759488_db0ea7df.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('2','1','Topi Rimba','Sakura','55000',NULL,'pcs','Topi rimba bahan sakura.','Min order 12-24 pcs.','produk_1782759603_b828e734.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('3','1','Topi Baseball Drill','Drill','50000',NULL,'pcs','Topi baseball bahan drill premium.','Sudah termasuk furing peles/satin daleman.','produk_1782759742_d05a2bb9.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('4','1','Topi Rimba Drill','Drill','58000',NULL,'pcs','Topi rimba bahan drill.','Tahan lama.','produk_1782759900_505ddf75.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('5','1','Topi Baseball Ribstop','Ribstop','55000',NULL,'pcs','Topi baseball ribstop premium.','Recommended seller.','produk_1782760030_7c526b12.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('6','1','Topi Rimba Ribstop','Ribstop','65000',NULL,'pcs','Topi rimba ribstop.','Tahan air.','produk_1782760155_5b688070.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('7','1','Topi Baseball Twil','Twil','60000',NULL,'pcs','Topi baseball twil.','Bahan kuat & rapi.','produk_1782760246_8f973326.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('8','1','Topi Rimba Twil','Twil','70000',NULL,'pcs','Topi rimba twil.','Ideal untuk lapangan.','produk_1782760331_04aa61fc.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('9','1','Topi Baseball Kanvas Marsoto','Kanvas Marsoto','65000',NULL,'pcs','Topi baseball kanvas marsoto.','Bahan tebal premium.','produk_1782760404_ef9ab77d.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('10','1','Topi Rimba Kanvas Marsoto','Kanvas Marsoto','75000',NULL,'pcs','Topi rimba kanvas marsoto.','Best seller.','produk_1782760498_7979e462.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('11','1','Topi Trucker Jaring Troya','Jaring Troya','45000',NULL,'pcs','Topi trucker dengan jaring di belakang.','Sirkulasi udara baik.','produk_1782760570_1d9371ec.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('12','1','Topi Dinas Bludru Jaring','Jaring + Bludru','70000',NULL,'pcs','Topi dinas bahan jaring full bordir depan.','Untuk instansi/komunitas.','produk_1782760653_02c363cc.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('13','1','Topi Dinas Laken Bludru','Laken Bludru','85000',NULL,'pcs','Topi dinas bahan laken bludru full bordir depan.','Premium quality.','produk_1782760796_cfdab4a0.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('14','2','Cetak ID Card Only (5-20 pcs)','PVC','10000',NULL,'pcs','Cetak ID card bahan PVC 2 muka atau 1 muka.','Kuat, tidak mudah luntur & awet. Min 5 pcs.','produk_1782740737_642302e0.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('15','2','Paket ID Card + Casing + Yoyo','PVC + Casing + Yoyo','15000',NULL,'pcs','Paket lengkap ID card.','Termasuk casing & yoyo.','produk_1782741030_fa4d52b0.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('16','2','Paket ID Card + Casing + Tali','PVC + Casing + Tali','15000',NULL,'pcs','Paket ID card + tali/lanyard.','Termasuk casing & tali.','produk_1782741601_759084ec.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('17','3','Lanyard Panjang Tissue 2cm','Tissue 2cm','16000','24000','pcs','Lanyard panjang ±40cm, full color custom.','Gratis desain, dibungkus persatu. Harga tergantung jumlah.','produk_1782749159_61aa127f.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('18','3','Lanyard Pendek / Gantungan Kunci','Tissue','13000',NULL,'pcs','Lanyard pendek 12cm untuk gantungan kunci/HP.','Gratis desain.','produk_1782749563_18f38e96.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('19','4','Almamater Drill Lengan Panjang','Drill','200000',NULL,'lembar','Almamater kampus/sekolah custom bordir/sablon.','Bahan drill premium, lengan panjang.','produk_1782736557_53429f21.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('20','5','Rompi Dril Jayeng/Kantata','Dril Jayeng/Kantata','116000','120000','pcs','Rompi outdoor bahan dril.','Min 12-24 pcs Rp120k, 24-62 pcs Rp116k.','produk_1782750387_d2a3475e.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('21','5','Rompi American Dril','American Dril','123000','125000','pcs','Rompi premium American Dril.','Best seller.','produk_1782750499_820ac173.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('22','5','Rompi Micro + Furing','Micro + Furing','147000','150000','pcs','Rompi micro dengan furing dalam.','Nyaman dipakai.','produk_1782750604_699ce0e3.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('23','5','Rompi Taslan JN/Milky + Furing','Taslan + Furing','157000','160000','pcs','Rompi taslan tahan air + furing.','Outdoor friendly.','produk_1782750731_12f476c0.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('24','5','Rompi Ripstop','Ripstop','132000','135000','pcs','Rompi ripstop tahan lama.','Recommended.','produk_1782750861_a190b925.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('25','5','Rompi Nagata','Nagata','132000','135000','pcs','Rompi bahan Nagata.','Premium.','produk_1782751031_935c13f5.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('26','5','Rompi Kanvas Marsoto','Kanvas Marsoto','163000','165000','pcs','Rompi kanvas marsoto tebal.','Heavy duty.','produk_1782751201_0ac35a43.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('31','6','Kaos Custom','Cotton Combed 20S','101000',NULL,'pcs','Kaos custom dengan bahan nyaman dan pilihan desain sesuai kebutuhan. Cocok untuk event, komunitas, perusahaan, dan promosi.','Best seller.','produk_1782747653_4351d1a8.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('35','6','Kaos Papa Jeans','Jeans','160000',NULL,'lembar','Kaos bahan jeans lengan panjang.','Unik & tahan lama.','produk_1782747307_a9d7f7cd.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('36','7','Seragam TK Nasional','Pamatex/Expo/Drill','250000',NULL,'setel','Seragam TK Nasional lengan panjang.','Setel lengkap.','produk_1782754269_f10bd769.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('37','7','Seragam TK Batik','Pamatex/Expo/Drill','190000',NULL,'setel','Seragam batik TK.','Setel lengkap.','produk_1782754713_8fc5eac1.jpeg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('38','7','Seragam TK Olahraga','Loto / PE','140000',NULL,'setel','Seragam olahraga TK.','Lengan panjang.','produk_1782755003_322fecb8.jpeg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('39','7','Seragam SD Putih-Merah','Pamatex/Expo/Drill','230000',NULL,'setel','Seragam SD/MI Putih-Merah.','Setel lengkap.','produk_1782755151_3261d58b.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('40','7','Seragam SD Pramuka','Pamatex/Expo/Drill','230000',NULL,'setel','Seragam Pramuka SD/MI.','Setel lengkap.','produk_1782755232_4f86ef6f.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('41','7','Seragam SD Batik','Pamatex/Expo/Drill','180000','230000','setel','Batik Sungai Limau SD.','Tersedia 2 model harga.','produk_1782755454_62fdc8d7.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('42','7','Seragam SD Olahraga','Loto / PE','180000',NULL,'setel','Olahraga SD/MI.','Lengan panjang.','produk_1782755534_90f1db9c.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('43','7','Seragam SD Muslim','Cantik','230000',NULL,'setel','Seragam muslim SD/MI.','Setel lengkap.','produk_1782755915_2efa51af.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('44','7','Seragam SMP Putih-Biru','Pamatex/Expo/Drill','250000',NULL,'setel','Seragam SMP/MTS Putih-Biru.','Setel lengkap.','produk_1782756241_f7e1f59c.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('45','7','Seragam SMP Pramuka','Pamatex/Expo/Drill','250000',NULL,'setel','Seragam Pramuka SMP/MTS.','Setel lengkap.','produk_1782758055_6147329c.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('46','7','Seragam SMP Batik Sungai Limau','Pamatex/Expo/Drill','200000','280000','setel','Batik SMP.','Tersedia 2 model harga.','produk_1782758206_49a400a8.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('47','7','Seragam SMP Olahraga','Loto / PE','180000',NULL,'setel','Olahraga SMP/MTS.','Lengan panjang.','produk_1782758404_86c276fc.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('48','7','Seragam SMP Muslim','Cantik','250000',NULL,'setel','Seragam muslim SMP/MTS.','Setel lengkap.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('49','7','Seragam SMA Putih-Abu','Pamatex/Expo/Drill','270000',NULL,'setel','Seragam SMK/SMU/MA Putih-Abu.','Setel lengkap.','produk_1782758939_b4d9388b.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('50','7','Seragam SMA Pramuka','Pamatex/Expo/Drill','270000',NULL,'setel','Seragam Pramuka SMA.','Setel lengkap.','produk_1782759026_bc1d6fc4.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('51','7','Seragam SMA Batik Sungai Limau','Pamatex/Expo/Drill','230000','300000','setel','Batik SMA.','Tersedia 2 model harga.','produk_1782759265_cef13570.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('52','7','Seragam SMA Olahraga','Loto / PE','180000',NULL,'setel','Olahraga SMA.','Lengan panjang.','produk_1782759398_ea2082a8.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('53','7','Seragam SMA Muslim','Cantik','290000',NULL,'setel','Seragam muslim SMA.','Setel lengkap.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('54','8','Jaket Drill Lengan Panjang','Drill','205000',NULL,'lembar','Jaket drill custom desain.','Lengan panjang, tebal.','produk_1782746113_ebe8ec79.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('55','8','Jaket Katun Lengan Panjang','Katun','185000',NULL,'lembar','Jaket katun nyaman.','Bisa custom logo.','produk_1782746869_a21d73b9.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('56','9','Baju PDH Drill Lengan Panjang','Drill','200000',NULL,'lembar','Baju PDH instansi bahan drill.','Lengan panjang, rapi.','produk_1782750262_e9e04cab.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('57','10','Batik Basurek Katun','Katun','175000',NULL,'lembar','Batik Basurek khas Bengkulu.','Lengan panjang.','produk_1782740008_74b9ac4f.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('58','11','Seragam Dinas Lengkap','Drill','500000',NULL,'setel','Seragam dinas instansi pemerintah.','Setel lengkap, lengan panjang.','produk_1782752684_11c20995.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('59','11','Seragam TNI / POLRI','Drill Premium','550000',NULL,'setel','Seragam TNI/POLRI standar.','Setel lengkap.','produk_1782752173_65d01ec9.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('60','11','Seragam Praktek Drill','Drill','320000',NULL,'setel','Seragam praktek mahasiswa/siswa.','Setel drill lengan panjang.','produk_1782753052_933101ad.jpg','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('61','3','Selempang Beludru','Beludru','120000',NULL,'lembar','Selempang bahan beludru untuk dinas/acara.','Custom bordir nama/logo.','produk_1782753548_20b116cb.jpg','1','2026-06-26 01:58:28');

-- Table: produk_gambar
DROP TABLE IF EXISTS `produk_gambar`;
CREATE TABLE `produk_gambar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_produk` int(11) NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `urutan` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `id_produk` (`id_produk`),
  CONSTRAINT `produk_gambar_ibfk_1` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=133 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('8','19','galeri_1782738523_7960fad9.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('9','19','galeri_1782738537_e73e2d71.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('10','57','galeri_1782740008_f7b42efa.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('11','57','galeri_1782740008_aed38a41.jpg','1');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('12','14','galeri_1782740737_0678ac65.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('14','14','galeri_1782740737_4b634bfc.webp','2');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('15','15','galeri_1782741030_c5182b59.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('17','15','galeri_1782741030_b74b3410.jpg','2');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('18','16','galeri_1782741601_4b76bf5c.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('19','16','galeri_1782741601_80af416b.jpg','1');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('20','19','galeri_1782744969_8f1d55d9.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('21','19','galeri_1782744970_4b7d872d.jpg','1');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('22','54','galeri_1782746023_329fc9d4.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('23','55','galeri_1782746869_9ee36652.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('24','55','galeri_1782746890_244cec42.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('25','55','galeri_1782746890_8c31f66a.jpg','1');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('27','35','galeri_1782747187_9d3d5a5a.jpg','1');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('30','35','galeri_1782747284_ac56de73.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('31','31','galeri_1782747653_406c2783.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('32','31','galeri_1782747653_1dbd71b8.jpg','1');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('33','31','galeri_1782747653_9ea247a1.jpg','2');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('37','17','galeri_1782749054_b34a6250.png','1');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('38','18','galeri_1782749563_9aac24ec.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('40','18','galeri_1782749563_8b699544.jpg','2');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('41','56','galeri_1782750262_a560867d.png','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('42','56','galeri_1782750262_6aa6903f.png','1');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('43','56','galeri_1782750262_b7e2aabd.png','2');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('44','20','galeri_1782750387_96832cff.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('45','20','galeri_1782750387_1ac9eb21.jpg','1');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('46','21','galeri_1782750499_f912fad9.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('48','21','galeri_1782750499_e82005fb.jpg','2');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('49','21','galeri_1782750499_d42391e3.jpg','3');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('50','22','galeri_1782750604_72800a67.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('51','22','galeri_1782750604_7012bed1.jpg','1');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('52','23','galeri_1782750731_c668aea5.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('53','23','galeri_1782750731_e9506cdc.jpg','1');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('54','23','galeri_1782750731_ebbbf90f.jpg','2');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('55','24','galeri_1782750861_5eebba00.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('57','24','galeri_1782750861_37394c05.jpg','2');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('58','25','galeri_1782751031_c9a3fb4c.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('59','26','galeri_1782751201_48e912f6.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('60','26','galeri_1782751201_0653d720.jpg','1');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('64','59','galeri_1782752202_12a0e857.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('65','59','galeri_1782752202_c0ca0793.jpg','1');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('66','59','galeri_1782752202_2f6ba939.jpg','2');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('71','58','galeri_1782752596_9cbc3c7c.png','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('72','58','galeri_1782752596_c4ff2f86.png','1');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('74','60','galeri_1782753052_4e8c477e.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('76','60','galeri_1782753052_87bcd9d7.jpg','2');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('82','61','galeri_1782753730_7f74d51b.png','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('83','61','galeri_1782753780_84d1f990.png','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('84','36','galeri_1782754269_bd56b36c.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('85','37','galeri_1782754713_9250b681.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('86','38','galeri_1782755003_9764cafd.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('87','39','galeri_1782755151_ff9702fa.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('88','40','galeri_1782755232_580550d7.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('89','41','galeri_1782755454_4defd5ac.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('90','42','galeri_1782755534_f0aee108.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('92','43','galeri_1782755915_72eac996.png','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('93','44','galeri_1782756241_5be50d67.webp','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('95','44','galeri_1782756241_f52499c9.jpg','2');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('96','45','galeri_1782758055_72ab7fef.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('97','46','galeri_1782758206_1d6faaab.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('98','46','galeri_1782758206_300c538e.jpg','1');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('99','47','galeri_1782758404_0a004b32.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('101','47','galeri_1782758404_7a8d1d43.jpg','2');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('102','49','galeri_1782758939_e3847cf2.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('104','49','galeri_1782758939_0fe1d1be.jpg','2');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('105','50','galeri_1782759026_dccaf936.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('106','52','galeri_1782759398_09d4b6e5.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('107','52','galeri_1782759398_94b4aaa5.jpg','1');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('108','1','galeri_1782759488_6d52361e.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('109','2','galeri_1782759603_74423608.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('110','2','galeri_1782759603_5e5605ee.jpg','1');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('111','3','galeri_1782759742_54d4d2d8.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('113','3','galeri_1782759742_7baf4eaf.jpg','2');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('114','4','galeri_1782759900_7ddf3ae9.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('115','5','galeri_1782760030_ed061c57.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('116','6','galeri_1782760155_3465babe.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('118','6','galeri_1782760155_79b86886.jpg','2');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('119','7','galeri_1782760246_c9831825.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('120','7','galeri_1782760246_038187bb.jpg','1');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('121','8','galeri_1782760331_dacb0cf9.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('122','8','galeri_1782760331_825711e5.jpg','1');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('123','9','galeri_1782760404_0bd8fcf4.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('125','9','galeri_1782760404_fc5ffeef.jpg','2');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('126','10','galeri_1782760498_116c52a7.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('127','10','galeri_1782760498_9bd0640f.jpg','1');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('128','11','galeri_1782760570_0b697208.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('129','11','galeri_1782760570_03a97846.jpg','1');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('130','12','galeri_1782760653_eb11c9f9.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('131','12','galeri_1782760653_ce814ffc.jpg','1');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('132','13','galeri_1782760796_fb08f548.jpg','0');

-- Table: reset_token
DROP TABLE IF EXISTS `reset_token`;
CREATE TABLE `reset_token` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `token` varchar(100) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint(4) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `id_user` (`id_user`),
  CONSTRAINT `reset_token_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table: users
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `google_id` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `google_id` (`google_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`id`,`nama`,`email`,`password`,`no_hp`,`alamat`,`google_id`,`created_at`) VALUES ('1','Customer Demo','customer@demo.com','$2y$10$MHUTFVpRP4sFI5DMBrVvc.zBikssMj8PNbYeGNHUbburD6oaVod8S','0895605813166','Jl. Contoh No. 1, Bengkulu',NULL,'2026-06-26 01:58:28');
INSERT INTO `users` (`id`,`nama`,`email`,`password`,`no_hp`,`alamat`,`google_id`,`created_at`) VALUES ('2','Muhammad ramadhan ilham','mramadhanilham19@gmail.com','$2y$10$ACuEgZgabBOox8/aLBWMzOyBlrOKGmULAF4zsW24fdfNRT0oZQiLW','0895605813166','Desa Air periukan Kec Air Periukan Kab Seluma','104437008337696690069','2026-06-26 02:14:33');

SET FOREIGN_KEY_CHECKS=1;
