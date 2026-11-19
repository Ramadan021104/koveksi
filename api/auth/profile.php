<?php
require_once __DIR__ . '/../bootstrap.php';

$user = api_auth($pdo);

// GET: Ambil detail profil user
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    api_response(true, 'Profil pengguna berhasil dimuat.', [
        'id'        => (int)$user['id'],
        'nama'      => $user['nama'],
        'email'     => $user['email'],
        'no_hp'     => $user['no_hp'],
        'alamat'    => $user['alamat'],
    ]);
}

// POST / PUT: Perbarui profil user
if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'PUT') {
    $input  = api_input();
    $nama   = trim($input['nama'] ?? $user['nama']);
    $no_hp  = trim($input['no_hp'] ?? $user['no_hp']);
    $alamat = trim($input['alamat'] ?? $user['alamat']);
    $pass   = $input['password'] ?? '';

    $errors = [];
    if (!$nama) $errors['nama'] = 'Nama tidak boleh kosong.';
    if ($no_hp && !preg_match('/^[0-9+\-\s]{8,20}$/', $no_hp)) $errors['no_hp'] = 'Format No HP tidak valid.';
    if ($pass && strlen($pass) < 6) $errors['password'] = 'Password minimal 6 karakter.';

    if (!empty($errors)) {
        api_error('Validasi gagal.', 422, $errors);
    }

    if ($pass) {
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET nama = ?, no_hp = ?, alamat = ?, password = ? WHERE id = ?");
        $stmt->execute([$nama, $no_hp, $alamat, $hash, $user['id']]);
    } else {
        $stmt = $pdo->prepare("UPDATE users SET nama = ?, no_hp = ?, alamat = ? WHERE id = ?");
        $stmt->execute([$nama, $no_hp, $alamat, $user['id']]);
    }

    api_response(true, 'Profil berhasil diperbarui.', [
        'id'     => (int)$user['id'],
        'nama'   => $nama,
        'email'  => $user['email'],
        'no_hp'  => $no_hp,
        'alamat' => $alamat,
    ]);
}

api_error('Metode HTTP tidak diizinkan. Gunakan GET atau POST.', 405);
