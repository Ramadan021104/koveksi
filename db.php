
<?php
// =====================================================
// KONFIGURASI KONEKSI DATABASE (PDO MySQL)
// =====================================================
// PETUNJUK INFINITYFREE:
// 1. Login ke panel InfinityFree → MySQL Databases
// 2. Buat database baru, catat: nama DB, username, password
// 3. Host MySQL InfinityFree biasanya: sql###.infinityfree.com
//    (cek di panel InfinityFree → MySQL Databases → Host)
// 4. Ganti nilai di bawah sesuai data dari panel InfinityFree
// =====================================================

$host = 'sql112.infinityfree.com';   // ← GANTI dengan MySQL Host dari panel InfinityFree
$port = '3306';
$db   = 'if0_43466136_indra_konveksi'; // ← GANTI dengan nama database dari panel
$user = 'if0_43466136';                // ← GANTI dengan username dari panel
$pass = 'VJnWDapbT2vcr';               // ← GANTI dengan password dari panel
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
