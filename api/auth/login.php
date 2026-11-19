<?php
require_once __DIR__ . '/../bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    api_error('Metode HTTP tidak diizinkan. Gunakan POST.', 405);
}

$input = api_input();
$email = trim($input['email'] ?? '');
$pass  = $input['password'] ?? '';

if (!$email || !$pass) {
    api_error('Email dan password wajib diisi.', 422);
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || empty($user['password']) || !password_verify($pass, $user['password'])) {
    api_error('Email atau password salah.', 401);
}

// Generate token baru atau gunakan yang ada jika masih ada
$token = generate_api_token();
$upd = $pdo->prepare("UPDATE users SET api_token = ? WHERE id = ?");
$upd->execute([$token, $user['id']]);

api_response(true, 'Login berhasil.', [
    'user' => [
        'id'        => (int)$user['id'],
        'nama'      => $user['nama'],
        'email'     => $user['email'],
        'no_hp'     => $user['no_hp'],
        'alamat'    => $user['alamat'],
        'google_id' => $user['google_id'] ? true : false,
    ],
    'token'      => $token,
    'token_type' => 'Bearer',
]);
