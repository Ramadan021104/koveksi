<?php
require_once __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    api_error('Metode HTTP tidak diizinkan. Gunakan GET.', 405);
}

// 1. DETAIL PRODUK (Jika ada parameter ID)
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT p.*, k.nama AS kategori_nama, k.slug AS kategori_slug 
                           FROM produk p 
                           JOIN kategori k ON k.id = p.id_kategori 
                           WHERE p.id = ?");
    $stmt->execute([$id]);
    $produk = $stmt->fetch();

    if (!$produk) {
        api_error('Produk tidak ditemukan.', 404);
    }

    $produk['gambar_url'] = gambar_produk($produk['gambar']);
    $produk['harga_format'] = harga_range($produk['harga_min'], $produk['harga_max'], $produk['satuan']);

    // Ambil galeri multi-image tambahan
    $stmtGaleri = $pdo->prepare("SELECT id, gambar, urutan FROM produk_gambar WHERE id_produk = ? ORDER BY urutan ASC, id ASC");
    $stmtGaleri->execute([$id]);
    $galeri = $stmtGaleri->fetchAll();

    $galeri_urls = [];
    foreach ($galeri as $g) {
        $galeri_urls[] = [
            'id'     => (int)$g['id'],
            'gambar' => $g['gambar'],
            'url'    => gambar_produk($g['gambar']),
        ];
    }
    $produk['galeri'] = $galeri_urls;

    api_response(true, 'Detail produk berhasil dimuat.', $produk);
}

// 2. DAFTAR PRODUK (Dengan filter kategori, pencarian, dan pagination)
$kategori = trim($_GET['kategori'] ?? '');
$search   = trim($_GET['search'] ?? '');
$page     = max(1, (int)($_GET['page'] ?? 1));
$limit    = max(1, min(100, (int)($_GET['limit'] ?? 20)));
$offset   = ($page - 1) * $limit;

$where = ["p.stok_tersedia = 1"];
$params = [];

// Filter Kategori (bisa slug atau ID)
if ($kategori !== '') {
    if (is_numeric($kategori)) {
        $where[] = "p.id_kategori = ?";
        $params[] = (int)$kategori;
    } else {
        $where[] = "k.slug = ?";
        $params[] = $kategori;
    }
}

// Filter Pencarian Keyword
if ($search !== '') {
    $where[] = "(p.nama LIKE ? OR p.bahan LIKE ? OR p.deskripsi LIKE ?)";
    $keyword = "%$search%";
    $params[] = $keyword;
    $params[] = $keyword;
    $params[] = $keyword;
}

$where_clause = implode(' AND ', $where);

// Hitung total data
$count_stmt = $pdo->prepare("SELECT COUNT(*) FROM produk p JOIN kategori k ON k.id = p.id_kategori WHERE $where_clause");
$count_stmt->execute($params);
$total_rows = (int)$count_stmt->fetchColumn();

// Ambil data produk
$sql = "SELECT p.id, p.id_kategori, k.nama AS kategori_nama, k.slug AS kategori_slug, 
               p.nama, p.bahan, p.harga_min, p.harga_max, p.satuan, 
               p.deskripsi, p.keterangan, p.gambar, p.stok_tersedia, p.created_at
        FROM produk p 
        JOIN kategori k ON k.id = p.id_kategori 
        WHERE $where_clause 
        ORDER BY p.id DESC 
        LIMIT $limit OFFSET $offset";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$items = $stmt->fetchAll();

foreach ($items as &$item) {
    $item['gambar_url']   = gambar_produk($item['gambar']);
    $item['harga_format'] = harga_range($item['harga_min'], $item['harga_max'], $item['satuan']);
}
unset($item);

api_response(true, 'Daftar produk berhasil dimuat.', [
    'pagination' => [
        'page'        => $page,
        'limit'       => $limit,
        'total_items' => $total_rows,
        'total_pages' => ceil($total_rows / $limit),
    ],
    'items' => $items,
]);
