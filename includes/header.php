<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/auth.php';
$kategori_nav = $pdo->query("SELECT * FROM kategori ORDER BY nama ASC")->fetchAll();
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($page_title ?? $pengaturan['nama_situs']) ?> - <?= e($pengaturan['nama_situs']) ?></title>

    <!-- PWA: manifest & installability -->
    <link rel="manifest" href="<?= BASE_URL ?>manifest.json">
    <meta name="theme-color" content="#0d4f8a">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="<?= e($pengaturan['nama_situs']) ?>">

    <link rel="icon" type="image/jpeg" href="<?= LOGO_URL ?>">
    <link rel="apple-touch-icon" href="<?= BASE_URL ?>assets/img/icon-192.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<script>
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function(){
        navigator.serviceWorker.register('<?= BASE_URL ?>service-worker.js');
    });
}
</script>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="<?= BASE_URL ?>">
            <img src="<?= LOGO_URL ?>" alt="Logo <?= e($pengaturan['nama_situs']) ?>" style="width:38px; height:38px; border-radius:50%; background:#fff; padding:2px; object-fit:cover;">
            <span><?= e($pengaturan['nama_situs']) ?></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link <?= $current_page=='index.php'?'active':'' ?>" href="<?= BASE_URL ?>">Beranda</a></li>
                <li class="nav-item"><a class="nav-link <?= $current_page=='katalog.php'?'active':'' ?>" href="<?= BASE_URL ?>katalog.php">Katalog</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">Kategori</a>
                    <ul class="dropdown-menu">
                        <?php foreach ($kategori_nav as $k): ?>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>katalog.php?kategori=<?= e($k['slug']) ?>"><?= e($k['nama']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link <?= $current_page=='custom-order.php'?'active':'' ?>" href="<?= BASE_URL ?>custom-order.php">Custom Order</a></li>
                <li class="nav-item"><a class="nav-link <?= $current_page=='tentang.php'?'active':'' ?>" href="<?= BASE_URL ?>tentang.php">Tentang</a></li>
                <li class="nav-item"><a class="nav-link <?= $current_page=='kontak.php'?'active':'' ?>" href="<?= BASE_URL ?>kontak.php">Kontak</a></li>
            </ul>
            <ul class="navbar-nav">
                <?php if (is_login()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">
                            <i class="bi bi-person-circle"></i> <?= e(user_data()['nama']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>profil.php"><i class="bi bi-person"></i> Profil</a></li>
                            <li><a class="dropdown-item" href="<?= BASE_URL ?>pesanan-saya.php"><i class="bi bi-bag"></i> Pesanan Saya</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>login.php">Login</a></li>
                    <li class="nav-item"><a class="btn btn-warning text-dark fw-semibold ms-2 px-3" href="<?= BASE_URL ?>register.php">Daftar</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<main class="py-4">
    <div class="container">
        <?php show_flash(); ?>
