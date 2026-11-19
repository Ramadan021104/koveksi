<?php
require_once __DIR__ . '/../bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    api_error('Metode HTTP tidak diizinkan. Gunakan POST.', 405);
}

$input  = api_input();
$nama   = trim($input['nama'] ?? '');
$email  = trim($input['email'] ?? '');
$pass   = $input['password'] ?? '';
$no_hp  = trim($input['no_hp'] ?? '');
$alamat = trim($input['alamat'] ?? '');

$errors = [];
if (!$nama)   $errors['nama'] = 'Nama wajib diisi.';
if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Email tidak valid.';
if (strlen($pass) < 6) $errors['password'] = 'Password minimal 6 karakter.';
if (!$no_hp || !preg_match('/^[0-9+\-\s]{8,20}$/', $no_hp)) $errors['no_hp'] = 'No HP tidak valid (minimal 8 digit).';
if (!$alamat) $errors['alamat'] = 'Alamat wajib diisi.';

if (!empty($errors)) {
    api_error('Validasi gagal.', 422, $errors);
}

// Cek apakah email sudah terdaftar
$cek = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$cek->execute([$email]);
if ($cek->fetch()) {
    api_error('Email sudah terdaftar. Silakan gunakan email lain atau login.', 409);
}

$hash_pass = password_hash($pass, PASSWORD_DEFAULT);
$token     = generate_api_token();

$stmt = $pdo->prepare("INSERT INTO users (nama, email, password, no_hp, alamat, api_token) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->execute([$nama, $email, $hash_pass, $no_hp, $alamat, $token]);
$userId = (int)$pdo->lastInsertId();

api_response(true, 'Registrasi berhasil.', [
    'user' => [
        'id'     => $userId,
        'nama'   => $nama,
        'email'  => $email,
        'no_hp'  => $no_hp,
        'alamat' => $alamat,
    ],
    'token' => $token,
    'token_type' => 'Bearer',
], 201);
