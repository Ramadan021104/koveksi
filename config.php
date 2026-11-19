<?php
// =====================================================
// KONFIGURASI APLIKASI
// =====================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('Asia/Jakarta');

// BASE URL — sesuaikan dengan domain InfinityFree Anda
// Contoh: 'https://indrakonveksi.rf.gd/' atau 'https://namadomainanda.rf.gd/'
// PENTING: harus diakhiri dengan / (slash)
define('BASE_URL', 'https://SUBDOMAIN-ANDA.rf.gd/');  // ← GANTI dengan URL domain InfinityFree

// Upload path (untuk gambar produk & file desain custom order)
define('UPLOAD_PATH', __DIR__ . '/../uploads/');
define('UPLOAD_URL', BASE_URL . 'uploads/');

// Logo identitas toko & website
define('LOGO_URL', BASE_URL . 'assets/img/logo.jpg');

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/security.php';

// Apply security headers di semua halaman
apply_security_headers();

// Global CSRF check di semua POST request
csrf_check();

// Auto-migrate: kolom & tabel baru
try {
    $cek = $pdo->query("SHOW COLUMNS FROM users LIKE 'google_id'")->fetch();
    if (!$cek) {
        $pdo->exec("ALTER TABLE users ADD COLUMN google_id VARCHAR(100) NULL UNIQUE AFTER alamat");
    }
    $cekToken = $pdo->query("SHOW COLUMNS FROM users LIKE 'api_token'")->fetch();
    if (!$cekToken) {
        $pdo->exec("ALTER TABLE users ADD COLUMN api_token VARCHAR(100) NULL UNIQUE AFTER google_id");
    }
    $pdo->exec("ALTER TABLE users MODIFY COLUMN password VARCHAR(255) NULL");
    $pdo->exec("CREATE TABLE IF NOT EXISTS produk_gambar (
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_produk INT NOT NULL,
        gambar VARCHAR(255) NOT NULL,
        urutan INT DEFAULT 0,
        FOREIGN KEY (id_produk) REFERENCES produk(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $pdo->exec("CREATE TABLE IF NOT EXISTS reset_token (
        id INT AUTO_INCREMENT PRIMARY KEY,
        id_user INT NOT NULL,
        token VARCHAR(100) UNIQUE NOT NULL,
        expires_at DATETIME NOT NULL,
        used TINYINT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    $pdo->exec("CREATE TABLE IF NOT EXISTS login_attempts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        ip VARCHAR(45) NOT NULL,
        type ENUM('admin','user') DEFAULT 'user',
        success TINYINT DEFAULT 0,
        attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_ip_time (ip, attempted_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
} catch (Exception $e) { /* abaikan */ }

// Ambil pengaturan dari DB
$pengaturan = $pdo->query("SELECT * FROM pengaturan LIMIT 1")->fetch();
if (!$pengaturan) {
    $pengaturan = [
        'nama_situs'  => 'Indra Konveksi',
        'wa_admin'    => '628123456789',
        'email_admin' => 'admin@indrakonveksi.com',
        'alamat'      => '',
        'tagline'     => '',
    ];
}
