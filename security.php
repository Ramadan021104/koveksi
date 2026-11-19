<?php
/**
 * =====================================================
 * SECURITY HELPERS
 * - CSRF Token
 * - Rate Limit (anti brute-force)
 * - Security Headers
 * =====================================================
 */

// ---------- SECURITY HEADERS ----------
function apply_security_headers() {
    if (headers_sent()) return;
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
    header("Content-Security-Policy: default-src 'self' https: data: 'unsafe-inline' 'unsafe-eval'; img-src * data: https: blob:; frame-src https://accounts.google.com; object-src 'none'");
    // HSTS hanya kalau HTTPS
    if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    }
}

// ---------- CSRF ----------
function csrf_token() {
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function csrf_field() {
    return '<input type="hidden" name="_csrf" value="' . htmlspecialchars(csrf_token()) . '">';
}

function csrf_check() {
    // Lewati CSRF check jika request berasal dari Web Service / REST API
    if (defined('IS_API') && IS_API) return true;
    if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/api/') !== false) return true;

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return true;
    $tok = $_POST['_csrf'] ?? '';
    $session_tok = $_SESSION['_csrf'] ?? '';
    if (!$tok || !$session_tok || !hash_equals($session_tok, $tok)) {
        http_response_code(403);
        die('<h3>403 Forbidden</h3><p>Token CSRF tidak valid atau session expired. Silakan refresh halaman dan coba lagi.</p>');
    }
    return true;
}

// ---------- RATE LIMIT LOGIN ----------
function client_ip() {
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

/**
 * Cek apakah IP/email diblokir karena terlalu banyak gagal login.
 * Block: 5 attempt gagal dalam 15 menit terakhir.
 */
function is_rate_limited($pdo, $type = 'user') {
    $ip = client_ip();
    try {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM login_attempts WHERE ip=? AND type=? AND success=0 AND attempted_at > (NOW() - INTERVAL 15 MINUTE)");
        $stmt->execute([$ip, $type]);
        return ((int)$stmt->fetchColumn()) >= 5;
    } catch (Exception $e) {
        return false;
    }
}

function record_login_attempt($pdo, $type, $success) {
    try {
        $pdo->prepare("INSERT INTO login_attempts (ip, type, success) VALUES (?,?,?)")
            ->execute([client_ip(), $type, $success ? 1 : 0]);
    } catch (Exception $e) { /* abaikan */ }
}

function clear_login_attempts($pdo, $type) {
    try {
        $pdo->prepare("DELETE FROM login_attempts WHERE ip=? AND type=?")
            ->execute([client_ip(), $type]);
    } catch (Exception $e) { /* abaikan */ }
}
