<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$cfg = require __DIR__ . '/config/google_oauth.php';

if (!isset($_GET['code']) || !isset($_GET['state'])) {
    flash('err', 'Login Google dibatalkan.', 'warning');
    redirect(BASE_URL . 'login.php');
}
if (($_SESSION['oauth_state'] ?? '') !== $_GET['state']) {
    flash('err', 'State OAuth tidak valid (kemungkinan CSRF).', 'danger');
    redirect(BASE_URL . 'login.php');
}
unset($_SESSION['oauth_state']);

// Tukar code dengan access_token
$ch = curl_init('https://oauth2.googleapis.com/token');
curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => http_build_query([
        'code'          => $_GET['code'],
        'client_id'     => $cfg['client_id'],
        'client_secret' => $cfg['client_secret'],
        'redirect_uri'  => $cfg['redirect_uri'],
        'grant_type'    => 'authorization_code',
    ]),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 15,
    CURLOPT_SSL_VERIFYPEER => false,
]);
$resp = json_decode(curl_exec($ch), true);
curl_close($ch);

if (empty($resp['access_token'])) {
    flash('err', 'Gagal mendapatkan token Google: ' . ($resp['error_description'] ?? 'unknown'), 'danger');
    redirect(BASE_URL . 'login.php');
}

// Ambil data user
$ch = curl_init('https://www.googleapis.com/oauth2/v2/userinfo');
curl_setopt_array($ch, [
    CURLOPT_HTTPHEADER     => ['Authorization: Bearer ' . $resp['access_token']],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 15,
    CURLOPT_SSL_VERIFYPEER => false,
]);
$info = json_decode(curl_exec($ch), true);
curl_close($ch);

if (empty($info['email'])) {
    flash('err', 'Gagal mengambil profil Google.', 'danger');
    redirect(BASE_URL . 'login.php');
}

// Cari atau buat user
$stmt = $pdo->prepare("SELECT * FROM users WHERE email=? OR google_id=?");
$stmt->execute([$info['email'], $info['id']]);
$user = $stmt->fetch();

if (!$user) {
    // Auto-register
    $ins = $pdo->prepare("INSERT INTO users (nama, email, password, no_hp, alamat, google_id) VALUES (?,?,?,?,?,?)");
    $ins->execute([
        $info['name'] ?? 'Google User',
        $info['email'],
        '', // password kosong (login via Google)
        '',
        '',
        $info['id'],
    ]);
    $id = $pdo->lastInsertId();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();
    flash('ok', 'Selamat datang ' . $info['name'] . '! Akun Anda telah dibuat. Mohon lengkapi No HP & Alamat di halaman Profil sebelum melakukan order.', 'success');
} else {
    // Update google_id kalau belum
    if (empty($user['google_id'])) {
        $pdo->prepare("UPDATE users SET google_id=? WHERE id=?")->execute([$info['id'], $user['id']]);
    }
    flash('ok', 'Login Google berhasil. Selamat datang, ' . $user['nama'] . '!', 'success');
}

$_SESSION['user_id']    = $user['id'];
$_SESSION['user_nama']  = $user['nama'];
$_SESSION['user_email'] = $user['email'];

// Kalau no_hp/alamat masih kosong, arahkan ke profil
if (empty($user['no_hp']) || empty($user['alamat'])) {
    redirect(BASE_URL . 'profil.php');
}
redirect(BASE_URL . 'index.php');
