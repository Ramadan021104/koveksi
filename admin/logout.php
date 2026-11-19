<?php
require_once __DIR__ . '/../config/config.php';
unset($_SESSION['admin_id'], $_SESSION['admin_nama']);
header("Location: " . BASE_URL . "admin/login.php");
exit;
