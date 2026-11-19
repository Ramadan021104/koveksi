<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/auth.php';
require_admin();
$admin = $_SESSION['admin_nama'] ?? 'Admin';
$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin - <?= e($page_title ?? 'Dashboard') ?></title>
    <link rel="icon" type="image/jpeg" href="<?= LOGO_URL ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body>
<div class="admin-wrap">
    <aside class="admin-sidebar">
        <div class="brand text-center">
            <img src="<?= LOGO_URL ?>" alt="Logo" style="width:64px; height:64px; border-radius:50%; background:#fff; padding:3px; object-fit:cover; margin-bottom:8px;">
            <h6 class="fw-bold text-warning mb-0"><i class="bi bi-shield-lock"></i> Admin Panel</h6>
            <small class="text-secondary"><?= e($pengaturan['nama_situs']) ?></small>
        </div>
        <a href="<?= BASE_URL ?>admin/index.php" class="<?= $current=='index.php'?'active':'' ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="<?= BASE_URL ?>admin/kategori.php" class="<?= $current=='kategori.php'?'active':'' ?>"><i class="bi bi-tags"></i> Kategori</a>
        <a href="<?= BASE_URL ?>admin/produk.php" class="<?= $current=='produk.php'?'active':'' ?>"><i class="bi bi-box-seam"></i> Produk</a>
        <a href="<?= BASE_URL ?>admin/pesanan.php" class="<?= $current=='pesanan.php'?'active':'' ?>"><i class="bi bi-cart-check"></i> Pesanan</a>
        <a href="<?= BASE_URL ?>admin/users.php" class="<?= $current=='users.php'?'active':'' ?>"><i class="bi bi-people"></i> Customer</a>
        <a href="<?= BASE_URL ?>admin/backup.php" class="<?= $current=='backup.php'?'active':'' ?>"><i class="bi bi-cloud-arrow-down"></i> Backup DB</a>
        <a href="<?= BASE_URL ?>admin/pengaturan.php" class="<?= $current=='pengaturan.php'?'active':'' ?>"><i class="bi bi-gear"></i> Pengaturan</a>
        <a href="<?= BASE_URL ?>admin/ganti-password.php" class="<?= $current=='ganti-password.php'?'active':'' ?>"><i class="bi bi-key"></i> Ganti Password</a>
        <hr class="border-secondary">
        <a href="<?= BASE_URL ?>" target="_blank"><i class="bi bi-globe"></i> Lihat Website</a>
        <a href="<?= BASE_URL ?>admin/logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </aside>
    <main class="admin-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0"><?= e($page_title ?? 'Dashboard') ?></h3>
            <span class="text-muted small"><i class="bi bi-person-circle"></i> <?= e($admin) ?></span>
        </div>
        <?php show_flash(); ?>
