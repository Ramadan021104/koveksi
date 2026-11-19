<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$filter_status = $_GET['status'] ?? '';
$q             = trim($_GET['q'] ?? '');

$sql = "SELECT pe.*, u.nama AS user_nama, u.email, u.no_hp, u.alamat,
               p.nama AS produk_nama, p.satuan, k.nama AS kategori_nama
        FROM pesanan pe
        JOIN users u ON u.id=pe.id_user
        JOIN produk p ON p.id=pe.id_produk
        JOIN kategori k ON k.id=p.id_kategori
        WHERE 1=1";
$params = [];
if ($filter_status) { $sql .= " AND pe.status=?"; $params[] = $filter_status; }
if ($q) {
    $sql .= " AND (pe.kode LIKE ? OR u.nama LIKE ? OR u.email LIKE ? OR p.nama LIKE ? OR k.nama LIKE ?)";
    $like = "%$q%";
    array_push($params, $like, $like, $like, $like, $like);
}
$sql .= " ORDER BY pe.id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();

// Output CSV (Excel-compatible) dengan BOM UTF-8 supaya bahasa Indonesia tetap rapi
$filename = 'Pesanan_IndraKonveksi_' . date('Ymd_His') . '.csv';
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: no-store, no-cache');

$out = fopen('php://output', 'w');
// BOM agar Excel deteksi UTF-8
fwrite($out, "\xEF\xBB\xBF");

// Header kolom
fputcsv($out, [
    'No', 'Kode Pesanan', 'Tanggal', 'Status', 'Nama Customer', 'Email', 'No HP', 'Alamat',
    'Kategori', 'Produk', 'Bahan', 'Detail Ukuran', 'Warna',
    'Jumlah', 'Satuan', 'Catatan', 'Total Harga (Rp)',
]);

foreach ($rows as $i => $r) {
    fputcsv($out, [
        $i + 1,
        $r['kode'],
        date('d/m/Y H:i', strtotime($r['created_at'])),
        $r['status'],
        $r['user_nama'],
        $r['email'],
        $r['no_hp'],
        $r['alamat'],
        $r['kategori_nama'],
        $r['produk_nama'],
        $r['bahan'],
        $r['ukuran'],
        $r['warna'],
        $r['jumlah'],
        $r['satuan'],
        $r['catatan'],
        $r['total_harga'],
    ]);
}

// Summary row
fputcsv($out, []);
fputcsv($out, ['', '', '', '', '', '', '', '', '', '', '', '', 'TOTAL OMZET:', '', '', '', array_sum(array_column($rows, 'total_harga'))]);
fputcsv($out, ['', '', '', '', '', '', '', '', '', '', '', '', 'TOTAL PESANAN:', count($rows), '', '', '']);

fclose($out);
exit;
