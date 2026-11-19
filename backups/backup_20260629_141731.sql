-- Indra Konveksi Backup
-- Generated: 2026-06-29 14:17:31

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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

INSERT INTO `pengaturan` (`id`,`nama_situs`,`wa_admin`,`email_admin`,`alamat`,`tagline`) VALUES ('1','Indra Konveksi','6285367388814','admin@indrakonveksi.com','Jl. Batang Hari No. 3D, Kel. Nusa, Kec. Ratu Agung, Kota Bengkulu','Konveksi & Sablon Terpercaya di Bengkulu - Bebas Kustom Segala Model');

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `pesanan` (`id`,`kode`,`id_user`,`id_produk`,`jumlah`,`bahan`,`ukuran`,`warna`,`catatan`,`file_desain`,`total_harga`,`status`,`created_at`) VALUES ('1','IK-20260625-A644F','2','27','2','','S: 2','','',NULL,'220000','Baru','2026-06-26 02:15:01');
INSERT INTO `pesanan` (`id`,`kode`,`id_user`,`id_produk`,`jumlah`,`bahan`,`ukuran`,`warna`,`catatan`,`file_desain`,`total_harga`,`status`,`created_at`) VALUES ('2','IK-20260628-E6302','1','19','6','','S: 1, M: 1, L: 1, XL: 1, XXL: 1, XXXL: 1','','','desain_1782654030_ae08e61c.jpg','1200000','Selesai','2026-06-28 20:40:30');

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

INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('1','1','Topi Baseball Sakura','Sakura','47000',NULL,'pcs','Topi baseball bahan sakura, cocok untuk komunitas/event.','Min order 12-24 pcs. Sudah termasuk bordir 1 titik.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('2','1','Topi Rimba Sakura','Sakura','55000',NULL,'pcs','Topi rimba bahan sakura.','Min order 12-24 pcs.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('3','1','Topi Baseball Drill','Drill','50000',NULL,'pcs','Topi baseball bahan drill premium.','Sudah termasuk furing peles/satin daleman.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('4','1','Topi Rimba Drill','Drill','58000',NULL,'pcs','Topi rimba bahan drill.','Tahan lama.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('5','1','Topi Baseball Ribstop','Ribstop','55000',NULL,'pcs','Topi baseball ribstop premium.','Recommended seller.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('6','1','Topi Rimba Ribstop','Ribstop','65000',NULL,'pcs','Topi rimba ribstop.','Tahan air.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('7','1','Topi Baseball Twil','Twil','60000',NULL,'pcs','Topi baseball twil.','Bahan kuat & rapi.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('8','1','Topi Rimba Twil','Twil','70000',NULL,'pcs','Topi rimba twil.','Ideal untuk lapangan.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('9','1','Topi Baseball Kanvas Marsoto','Kanvas Marsoto','65000',NULL,'pcs','Topi baseball kanvas marsoto.','Bahan tebal premium.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('10','1','Topi Rimba Kanvas Marsoto','Kanvas Marsoto','75000',NULL,'pcs','Topi rimba kanvas marsoto.','Best seller.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('11','1','Topi Trucker Jaring Troya','Jaring Troya','45000',NULL,'pcs','Topi trucker dengan jaring di belakang.','Sirkulasi udara baik.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('12','1','Topi Dinas Bludru Jaring','Jaring + Bludru','70000',NULL,'pcs','Topi dinas bahan jaring full bordir depan.','Untuk instansi/komunitas.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('13','1','Topi Dinas Laken Bludru','Laken Bludru','85000',NULL,'pcs','Topi dinas bahan laken bludru full bordir depan.','Premium quality.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('14','2','Cetak ID Card Only (5-20 pcs)','PVC','10000',NULL,'pcs','Cetak ID card bahan PVC 2 muka atau 1 muka.','Kuat, tidak mudah luntur & awet. Min 5 pcs.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('15','2','Paket ID Card + Casing + Yoyo','PVC + Casing + Yoyo','15000',NULL,'pcs','Paket lengkap ID card.','Termasuk casing & yoyo.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('16','2','Paket ID Card + Casing + Tali','PVC + Casing + Tali','15000',NULL,'pcs','Paket ID card + tali/lanyard.','Termasuk casing & tali.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('17','3','Lanyard Panjang Tissue 2cm','Tissue 2cm','16000','24000','pcs','Lanyard panjang ±40cm, full color custom.','Gratis desain, dibungkus persatu. Harga tergantung jumlah.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('18','3','Lanyard Pendek / Gantungan Kunci','Tissue','13000',NULL,'pcs','Lanyard pendek 12cm untuk gantungan kunci/HP.','Gratis desain.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('19','4','Almamater Drill Lengan Panjang','Drill','200000',NULL,'lembar','Almamater kampus/sekolah custom bordir/sablon.','Bahan drill premium, lengan panjang.','produk_1782732887_5ce6305b.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('20','5','Rompi Dril Jayeng/Kantata','Dril Jayeng/Kantata','116000','120000','pcs','Rompi outdoor bahan dril.','Min 12-24 pcs Rp120k, 24-62 pcs Rp116k.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('21','5','Rompi American Dril','American Dril','123000','125000','pcs','Rompi premium American Dril.','Best seller.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('22','5','Rompi Micro + Furing','Micro + Furing','147000','150000','pcs','Rompi micro dengan furing dalam.','Nyaman dipakai.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('23','5','Rompi Taslan JN/Milky + Furing','Taslan + Furing','157000','160000','pcs','Rompi taslan tahan air + furing.','Outdoor friendly.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('24','5','Rompi Ripstop','Ripstop','132000','135000','pcs','Rompi ripstop tahan lama.','Recommended.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('25','5','Rompi Nagata','Nagata','132000','135000','pcs','Rompi bahan Nagata.','Premium.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('26','5','Rompi Kanvas Marsoto','Kanvas Marsoto','163000','165000','pcs','Rompi kanvas marsoto tebal.','Heavy duty.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('27','6','Kaos Cotton Bambo Soft','Cotton Bambo Soft','110000',NULL,'pcs','Kaos premium cotton bambo soft.','Lembut & adem.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('28','6','Kaos Cotton Combed Terry','Cotton Combed Terry','105000',NULL,'pcs','Kaos cotton combed terry.','Standar konveksi.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('29','6','Kaos Cotton Combed 30S','Cotton Combed 30S','91000',NULL,'pcs','Kaos combed 30S best seller.','Tipis nyaman.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('30','6','Kaos Cotton Combed 24S','Cotton Combed 24S','96000',NULL,'pcs','Kaos combed 24S.','Medium weight.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('31','6','Kaos Cotton Combed 20S','Cotton Combed 20S','101000',NULL,'pcs','Kaos combed 20S tebal.','Best seller.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('32','6','Kaos Cotton Carded','Cotton Carded','85000',NULL,'pcs','Kaos cotton carded ekonomis.','Untuk sablon massal.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('33','6','Kaos PE Double','PE Double','75000',NULL,'pcs','Kaos PE double.','Murah meriah.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('34','6','Kaos PE Single','PE Single','70000',NULL,'pcs','Kaos PE single.','Untuk event/promosi.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('35','6','Kaos Papa Jeans','Jeans','160000',NULL,'lembar','Kaos bahan jeans lengan panjang.','Unik & tahan lama.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('36','7','Seragam TK Nasional','Pamatex/Expo/Drill','250000',NULL,'setel','Seragam TK Nasional lengan panjang.','Setel lengkap.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('37','7','Seragam TK Batik','Pamatex/Expo/Drill','190000',NULL,'setel','Seragam batik TK.','Setel lengkap.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('38','7','Seragam TK Olahraga','Loto / PE','140000',NULL,'setel','Seragam olahraga TK.','Lengan panjang.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('39','7','Seragam SD Putih-Merah','Pamatex/Expo/Drill','230000',NULL,'setel','Seragam SD/MI Putih-Merah.','Setel lengkap.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('40','7','Seragam SD Pramuka','Pamatex/Expo/Drill','230000',NULL,'setel','Seragam Pramuka SD/MI.','Setel lengkap.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('41','7','Seragam SD Batik Sungai Limau','Pamatex/Expo/Drill','180000','230000','setel','Batik Sungai Limau SD.','Tersedia 2 model harga.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('42','7','Seragam SD Olahraga','Loto / PE','180000',NULL,'setel','Olahraga SD/MI.','Lengan panjang.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('43','7','Seragam SD Muslim','Cantik','230000',NULL,'setel','Seragam muslim SD/MI.','Setel lengkap.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('44','7','Seragam SMP Putih-Biru','Pamatex/Expo/Drill','250000',NULL,'setel','Seragam SMP/MTS Putih-Biru.','Setel lengkap.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('45','7','Seragam SMP Pramuka','Pamatex/Expo/Drill','250000',NULL,'setel','Seragam Pramuka SMP/MTS.','Setel lengkap.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('46','7','Seragam SMP Batik Sungai Limau','Pamatex/Expo/Drill','200000','280000','setel','Batik SMP.','Tersedia 2 model harga.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('47','7','Seragam SMP Olahraga','Loto / PE','180000',NULL,'setel','Olahraga SMP/MTS.','Lengan panjang.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('48','7','Seragam SMP Muslim','Cantik','250000',NULL,'setel','Seragam muslim SMP/MTS.','Setel lengkap.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('49','7','Seragam SMA Putih-Abu','Pamatex/Expo/Drill','270000',NULL,'setel','Seragam SMK/SMU/MA Putih-Abu.','Setel lengkap.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('50','7','Seragam SMA Pramuka','Pamatex/Expo/Drill','270000',NULL,'setel','Seragam Pramuka SMA.','Setel lengkap.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('51','7','Seragam SMA Batik Sungai Limau','Pamatex/Expo/Drill','230000','300000','setel','Batik SMA.','Tersedia 2 model harga.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('52','7','Seragam SMA Olahraga','Loto / PE','180000',NULL,'setel','Olahraga SMA.','Lengan panjang.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('53','7','Seragam SMA Muslim','Cantik','290000',NULL,'setel','Seragam muslim SMA.','Setel lengkap.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('54','8','Jaket Drill Lengan Panjang','Drill','205000',NULL,'lembar','Jaket drill custom desain.','Lengan panjang, tebal.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('55','8','Jaket Katun Lengan Panjang','Katun','185000',NULL,'lembar','Jaket katun nyaman.','Bisa custom logo.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('56','9','Baju PDH Drill Lengan Panjang','Drill','200000',NULL,'lembar','Baju PDH instansi bahan drill.','Lengan panjang, rapi.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('57','10','Batik Basurek Katun','Katun','175000',NULL,'lembar','Batik Basurek khas Bengkulu.','Lengan panjang.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('58','11','Seragam Dinas Lengkap','Drill','500000',NULL,'setel','Seragam dinas instansi pemerintah.','Setel lengkap, lengan panjang.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('59','11','Seragam TNI / POLRI','Drill Premium','550000',NULL,'setel','Seragam TNI/POLRI standar.','Setel lengkap.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('60','11','Seragam Praktek Drill','Drill','320000',NULL,'setel','Seragam praktek mahasiswa/siswa.','Setel drill lengan panjang.','default.png','1','2026-06-26 01:58:28');
INSERT INTO `produk` (`id`,`id_kategori`,`nama`,`bahan`,`harga_min`,`harga_max`,`satuan`,`deskripsi`,`keterangan`,`gambar`,`stok_tersedia`,`created_at`) VALUES ('61','11','Selempang Beludru','Beludru','120000',NULL,'lembar','Selempang bahan beludru untuk dinas/acara.','Custom bordir nama/logo.','produk_1782653916_2d68a066.jpg','0','2026-06-26 01:58:28');

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('5','61','galeri_1782654903_e97d6b46.jpg','0');
INSERT INTO `produk_gambar` (`id`,`id_produk`,`gambar`,`urutan`) VALUES ('6','61','galeri_1782654903_9eb06045.jpg','2');

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
