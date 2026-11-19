<?php
require_once __DIR__ . '/bootstrap.php';

$endpoints = [
    'store_info' => [
        'method'      => 'GET',
        'endpoint'    => BASE_URL . 'api/pengaturan.php',
        'description' => 'Mendapatkan profil toko, nomor WhatsApp admin, email, dan alamat',
        'auth'        => 'Public',
    ],
    'categories' => [
        'method'      => 'GET',
        'endpoint'    => BASE_URL . 'api/kategori.php',
        'description' => 'Mendapatkan daftar semua kategori atau detail kategori spesifik (?id=1 atau ?slug=topi)',
        'auth'        => 'Public',
    ],
    'products' => [
        'method'      => 'GET',
        'endpoint'    => BASE_URL . 'api/produk.php',
        'description' => 'Mendapatkan daftar produk dengan filter (?kategori=topi, ?search=kaos, ?limit=10) atau detail produk (?id=1)',
        'auth'        => 'Public',
    ],
    'auth_register' => [
        'method'      => 'POST',
        'endpoint'    => BASE_URL . 'api/auth/register.php',
        'description' => 'Registrasi customer baru dan mendapatkan api_token',
        'auth'        => 'Public',
        'body'        => ['nama', 'email', 'password', 'no_hp', 'alamat'],
    ],
    'auth_login' => [
        'method'      => 'POST',
        'endpoint'    => BASE_URL . 'api/auth/login.php',
        'description' => 'Login customer menggunakan email & password, mengembalikan api_token',
        'auth'        => 'Public',
        'body'        => ['email', 'password'],
    ],
    'auth_profile' => [
        'method'      => 'GET / POST',
        'endpoint'    => BASE_URL . 'api/auth/profile.php',
        'description' => 'Melihat profil (GET) atau memperbarui data profil customer (POST)',
        'auth'        => 'Bearer Token (Header Authorization: Bearer <token>)',
    ],
    'orders_list' => [
        'method'      => 'GET',
        'endpoint'    => BASE_URL . 'api/pesanan/index.php',
        'description' => 'Melihat riwayat semua pesanan milik customer yang login',
        'auth'        => 'Bearer Token',
    ],
    'order_create' => [
        'method'      => 'POST',
        'endpoint'    => BASE_URL . 'api/pesanan/index.php',
        'description' => 'Membuat custom order baru (mendukung upload file desain)',
        'auth'        => 'Bearer Token',
        'body'        => ['id_produk', 'bahan', 'warna', 'catatan', 'size (array: {"S":2, "M":5})', 'file_desain (opsional file)'],
    ],
    'order_detail' => [
        'method'      => 'GET',
        'endpoint'    => BASE_URL . 'api/pesanan/detail.php?kode=IK-20261118-XXXXX',
        'description' => 'Melihat detail status dan struk pesanan berdasarkan kode pesanan atau ID',
        'auth'        => 'Bearer Token',
    ],
];

api_response(true, 'Selamat datang di Web Service Indra Konveksi REST API v1.0', [
    'service'   => 'Indra Konveksi REST API',
    'version'   => '1.0.0',
    'base_url'  => BASE_URL . 'api/',
    'auth_type' => 'Bearer Token via Header "Authorization: Bearer <api_token>"',
    'endpoints' => $endpoints,
]);
