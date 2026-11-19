<?php
require_once __DIR__ . '/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    api_error('Metode HTTP tidak diizinkan. Gunakan GET.', 405);
}

$stmt = $pdo->query("SELECT nama_situs, wa_admin, email_admin, alamat, tagline FROM pengaturan LIMIT 1");
$data = $stmt->fetch();

if (!$data) {
    $data = [
        'nama_situs'  => 'Indra Konveksi',
        'wa_admin'    => '628123456789',
        'email_admin' => 'admin@indrakonveksi.com',
        'alamat'      => 'Jl. Batang Hari No. 3D, Kel. Nusa, Kec. Ratu Agung, Kota Bengkulu',
        'tagline'     => 'Konveksi & Sablon Terpercaya di Bengkulu',
    ];
}

$data['logo_url'] = LOGO_URL;
$data['wa_link']  = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $data['wa_admin']);

api_response(true, 'Data pengaturan toko berhasil dimuat.', $data);
