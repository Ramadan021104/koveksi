<?php
/**
 * =====================================================
 * API BOOTSTRAP / CORE HELPER
 * =====================================================
 * Digunakan oleh semua endpoint REST API (Web Service).
 * Mengatur CORS, format respons JSON, dan autentikasi token.
 * =====================================================
 */

define('IS_API', true);

// Set Header CORS agar bisa diakses dari Flutter, React, Vue, Android, iOS, Postman, dll.
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, X-API-TOKEN');
header('Content-Type: application/json; charset=UTF-8');

// Tangani Preflight Request (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Load konfigurasi sistem & helper
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';

/**
 * Mengirimkan respon JSON standar
 */
function api_response(bool $success, string $message, $data = null, int $http_code = 200) {
    http_response_code($http_code);
    $payload = [
        'success'   => $success,
        'message'   => $message,
        'timestamp' => date('Y-m-d H:i:s'),
    ];
    if ($data !== null) {
        $payload['data'] = $data;
    }
    echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Mengirimkan error JSON standar
 */
function api_error(string $message, int $http_code = 400, $errors = null) {
    http_response_code($http_code);
    $payload = [
        'success'   => false,
        'message'   => $message,
        'timestamp' => date('Y-m-d H:i:s'),
    ];
    if ($errors !== null) {
        $payload['errors'] = $errors;
    }
    echo json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Membaca input JSON atau multipart/form-data
 */
function api_input(): array {
    $content_type = $_SERVER['CONTENT_TYPE'] ?? $_SERVER['HTTP_CONTENT_TYPE'] ?? '';
    
    // Jika format JSON
    if (stripos($content_type, 'application/json') !== false) {
        $raw = file_get_contents('php://input');
        $json = json_decode($raw, true);
        return is_array($json) ? $json : [];
    }
    
    // Jika format form-data / x-www-form-urlencoded
    return $_POST;
}

/**
 * Mengambil Authorization Token dari Header atau Query string
 */
function get_auth_token(): ?string {
    // 1. Cek dari Header Authorization (Bearer <token>)
    $header = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
    if (!$header && function_exists('getallheaders')) {
        $all = getallheaders();
        $header = $all['Authorization'] ?? $all['authorization'] ?? '';
    }
    if ($header && preg_match('/Bearer\s+(\S+)/i', $header, $matches)) {
        return $matches[1];
    }

    // 2. Cek dari Custom Header X-API-TOKEN
    if (!empty($_SERVER['HTTP_X_API_TOKEN'])) {
        return $_SERVER['HTTP_X_API_TOKEN'];
    }

    // 3. Fallback: Query parameter ?token=...
    if (!empty($_GET['token'])) {
        return $_GET['token'];
    }

    return null;
}

/**
 * Validasi otentikasi user via Bearer token
 * Mengembalikan data user jika valid, atau langsung menghentikan request dengan 401
 */
function api_auth(PDO $pdo): array {
    $token = get_auth_token();
    if (!$token) {
        api_error('Autentikasi gagal: Token tidak ditemukan. Kirim token via Header "Authorization: Bearer <token>" atau "X-API-TOKEN".', 401);
    }

    $stmt = $pdo->prepare("SELECT id, nama, email, no_hp, alamat, api_token FROM users WHERE api_token = ? AND api_token IS NOT NULL AND api_token != ''");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if (!$user) {
        api_error('Autentikasi gagal: Token tidak valid atau sesi telah berakhir.', 401);
    }

    return $user;
}

/**
 * Generate token acak yang aman
 */
function generate_api_token(): string {
    return bin2hex(random_bytes(32));
}
