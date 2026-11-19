<?php
require_once __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    api_error('Metode HTTP tidak diizinkan. Gunakan GET.', 405);
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$slug = isset($_GET['slug']) ? trim($_GET['slug']) : null;

if ($id || $slug) {
    if ($id) {
        $stmt = $pdo->prepare("SELECT k.*, COUNT(p.id) AS total_produk FROM kategori k LEFT JOIN produk p ON p.id_kategori = k.id WHERE k.id = ? GROUP BY k.id");
        $stmt->execute([$id]);
    } else {
        $stmt = $pdo->prepare("SELECT k.*, COUNT(p.id) AS total_produk FROM kategori k LEFT JOIN produk p ON p.id_kategori = k.id WHERE k.slug = ? GROUP BY k.id");
        $stmt->execute([$slug]);
    }
    $kategori = $stmt->fetch();
    if (!$kategori) {
        api_error('Kategori tidak ditemukan.', 404);
    }
    api_response(true, 'Detail kategori ditemukan.', $kategori);
}

// Ambil semua kategori beserta jumlah produknya
$sql = "SELECT k.id, k.nama, k.slug, k.deskripsi, COUNT(p.id) AS total_produk 
        FROM kategori k 
        LEFT JOIN produk p ON p.id_kategori = k.id 
        GROUP BY k.id 
        ORDER BY k.nama ASC";
$kategori_list = $pdo->query($sql)->fetchAll();

api_response(true, 'Daftar kategori berhasil dimuat.', [
    'total' => count($kategori_list),
    'items' => $kategori_list,
]);
