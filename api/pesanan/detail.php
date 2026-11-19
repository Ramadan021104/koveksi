<?php
require_once __DIR__ . '/../bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    api_error('Metode HTTP tidak diizinkan. Gunakan GET.', 405);
}

$user = api_auth($pdo);

$id   = isset($_GET['id']) ? (int)$_GET['id'] : null;
$kode = isset($_GET['kode']) ? trim($_GET['kode']) : null;

if (!$id && !$kode) {
    api_error('Parameter ID atau Kode pesanan wajib disertakan.', 422);
}

$sql = "SELECT p.*, pr.nama AS produk_nama, pr.gambar AS produk_gambar, pr.satuan, k.nama AS kategori_nama 
        FROM pesanan p 
        JOIN produk pr ON pr.id = p.id_produk 
        JOIN kategori k ON k.id = pr.id_kategori 
        WHERE p.id_user = ? AND (p.id = ? OR p.kode = ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user['id'], $id, $kode]);
$detail = $stmt->fetch();

if (!$detail) {
    api_error('Pesanan tidak ditemukan atau Anda tidak memiliki akses.', 404);
}

$detail['total_harga_format'] = rupiah($detail['total_harga']);
$detail['estimasi_selesai']   = estimasi_selesai($detail['created_at']);
$detail['produk_gambar_url']  = gambar_produk($detail['produk_gambar']);
$detail['file_desain_url']    = $detail['file_desain'] ? UPLOAD_URL . $detail['file_desain'] : null;
$detail['wa_konfirmasi_url']  = wa_link_struk($pengaturan['wa_admin'], [
    'kode'          => $detail['kode'],
    'created_at'    => $detail['created_at'],
    'status'        => $detail['status'],
    'user_nama'     => $user['nama'],
    'kategori_nama' => $detail['kategori_nama'],
    'produk_nama'   => $detail['produk_nama'],
    'jumlah'        => $detail['jumlah'],
    'satuan'        => $detail['satuan'],
    'bahan'         => $detail['bahan'],
    'ukuran'        => $detail['ukuran'],
    'warna'         => $detail['warna'],
    'catatan'       => $detail['catatan'],
    'file_desain'   => $detail['file_desain'],
    'total_harga'   => $detail['total_harga'],
], 'admin');

api_response(true, 'Detail pesanan ditemukan.', $detail);
