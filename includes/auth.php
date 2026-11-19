<?php
// =====================================================
// AUTH HELPER (Customer)
// =====================================================

function is_login() {
    return !empty($_SESSION['user_id']);
}

function user_data() {
    if (!is_login()) return null;
    return [
        'id' => $_SESSION['user_id'],
        'nama' => $_SESSION['user_nama'] ?? '',
        'email' => $_SESSION['user_email'] ?? '',
    ];
}

function require_login() {
    if (!is_login()) {
        flash('login', 'Silakan login terlebih dahulu untuk melanjutkan.', 'warning');
        redirect(BASE_URL . 'login.php');
    }
}

function is_admin() {
    return !empty($_SESSION['admin_id']);
}

function require_admin() {
    if (!is_admin()) {
        redirect(BASE_URL . 'admin/login.php');
    }
}
