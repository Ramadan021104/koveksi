<?php
require_once __DIR__ . '/../bootstrap.php';

$user = api_auth($pdo);

// 1. GET: DAFTAR PESANAN CUSTOMER
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "SELECT p.*, pr.nama AS produk_nama, pr.gambar AS produk_gambar, pr.satuan, k.nama AS kategori_nama 
            FROM pesanan p 
            JOIN produk pr ON pr.id = p.id_produk 
            JOIN kategori k ON k.id = pr.id_kategori 
            WHERE p.id_user = ? 
            ORDER BY p.id DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user['id']]);
    $pesanan_list = $stmt->fetchAll();

    foreach ($pesanan_list as &$p) {
        $p['total_harga_format'] = rupiah($p['total_harga']);
        $p['estimasi_selesai']   = estimasi_selesai($p['created_at']);
        $p['produk_gambar_url']  = gambar_produk($p['produk_gambar']);
        $p['file_desain_url']    = $p['file_desain'] ? UPLOAD_URL . $p['file_desain'] : null;
    }
    unset($p);

    api_response(true, 'Daftar pesanan berhasil dimuat.', [
        'total' => count($pesanan_list),
        'items' => $pesanan_list,
    ]);
}

// 2. POST: BUAT PESANAN CUSTOM ORDER BARU
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input     = api_input();
    $id_produk = (int)($input['id_produk'] ?? 0);
    $bahan     = trim($input['bahan'] ?? '');
    $warna     = trim($input['warna'] ?? '');
    $catatan   = trim($input['catatan'] ?? '');

    // Cek produk
    $stmt = $pdo->prepare("SELECT p.*, k.nama AS kategori_nama FROM produk p JOIN kategori k ON k.id = p.id_kategori WHERE p.id = ?");
    $stmt->execute([$id_produk]);
    $produk = $stmt->fetch();

    if (!$produk) {
        api_error('Produk yang dipilih tidak valid atau tidak ditemukan.', 404);
    }

    // Hitung ukuran & jumlah
    $ukuran = '';
    $jumlah = 0;

    if (!empty($input['size']) && is_array($input['size'])) {
        $ukuran_arr = [];
        foreach ($input['size'] as $label => $val) {
            $n = max(0, (int)$val);
            if ($n > 0) {
                $ukuran_arr[] = "$label: $n";
                $jumlah += $n;
            }
        }
        $ukuran = implode(', ', $ukuran_arr);
    } elseif (!empty($input['jumlah'])) {
        $jumlah = max(1, (int)$input['jumlah']);
        $ukuran = trim($input['ukuran'] ?? 'All Size');
    }

    if ($jumlah < 1) {
        api_error('Jumlah pesanan minimal 1 pcs. Cantumkan rincian ukuran atau jumlah.', 422);
    }

    // Handle Upload File Desain (jika dikirim via multipart/form-data)
    $file_desain = upload_file('file_desain', 'desain');

    $total_harga = $produk['harga_min'] * $jumlah;
    $kode        = kode_pesanan();

    $ins = $pdo->prepare("INSERT INTO pesanan (kode, id_user, id_produk, jumlah, bahan, ukuran, warna, catatan, file_desain, total_harga, status) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Baru')");
    $ins->execute([$kode, $user['id'], $id_produk, $jumlah, $bahan, $ukuran, $warna, $catatan, $file_desain, $total_harga]);
    $id_pesanan = (int)$pdo->lastInsertId();

    // Data struk untuk WA link
    $detail_pesanan = [
        'id'            => $id_pesanan,
        'kode'          => $kode,
        'created_at'    => date('Y-m-d H:i:s'),
        'status'        => 'Baru',
        'user_nama'     => $user['nama'],
        'email'         => $user['email'],
        'no_hp'         => $user['no_hp'],
        'kategori_nama' => $produk['kategori_nama'],
        'produk_nama'   => $produk['nama'],
        'jumlah'        => $jumlah,
        'satuan'        => $produk['satuan'],
        'bahan'         => $bahan ?: $produk['bahan'],
        'ukuran'        => $ukuran,
        'warna'         => $warna,
        'catatan'       => $catatan,
        'file_desain'   => $file_desain,
        'total_harga'   => $total_harga,
    ];

    // WhatsApp link pre-filled dengan teks struk ke admin
    $wa_admin_link = wa_link_struk($pengaturan['wa_admin'], $detail_pesanan, 'admin');

    // Notifikasi WhatsApp otomatis ke admin via API jika aktif
    if (!empty($pengaturan['wa_admin'])) {
        @kirim_wa_admin(format_struk_wa($detail_pesanan, 'admin'));
    }

    // Email notifikasi jika SMTP aktif
    $email_body = "<h3>Pesanan Baru: $kode</h3>"
                . "<p>Customer: " . htmlspecialchars($user['nama']) . " ({$user['email']})</p>"
                . "<p>Produk: " . htmlspecialchars($produk['nama']) . " x $jumlah {$produk['satuan']}</p>"
                . "<p>Total: " . rupiah($total_harga) . "</p>";
    @kirim_email_order($pengaturan['email_admin'], "Pesanan Baru $kode", $email_body);

    api_response(true, 'Pesanan berhasil dibuat.', [
        'id'                 => $id_pesanan,
        'kode'               => $kode,
        'total_harga'        => $total_harga,
        'total_harga_format' => rupiah($total_harga),
        'status'             => 'Baru',
        'estimasi_selesai'   => estimasi_selesai($detail_pesanan['created_at']),
        'file_desain_url'    => $file_desain ? UPLOAD_URL . $file_desain : null,
        'wa_konfirmasi_url'  => $wa_admin_link,
    ], 201);
}

api_error('Metode HTTP tidak diizinkan. Gunakan GET atau POST.', 405);
