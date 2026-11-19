<?php
// =====================================================
// HELPER FUNCTIONS
// =====================================================

function rupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}

function harga_range($min, $max, $satuan = 'pcs') {
    if ($max && $max != $min) {
        return rupiah($min) . ' – ' . rupiah($max) . ' / ' . $satuan;
    }
    return rupiah($min) . ' / ' . $satuan;
}

function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

function flash($key, $msg = null, $type = 'success') {
    if ($msg !== null) {
        $_SESSION['flash'][$key] = ['msg' => $msg, 'type' => $type];
        return;
    }
    if (isset($_SESSION['flash'][$key])) {
        $f = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $f;
    }
    return null;
}

function show_flash() {
    if (!empty($_SESSION['flash'])) {
        foreach ($_SESSION['flash'] as $key => $f) {
            echo '<div class="alert alert-' . e($f['type']) . ' alert-dismissible fade show" role="alert">'
                . e($f['msg'])
                . '<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
        }
        $_SESSION['flash'] = [];
    }
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function kode_pesanan() {
    return 'IK-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
}

function upload_file($field, $prefix = 'file') {
    if (!isset($_FILES[$field]) || $_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'webp'];
    $ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) return null;
    if ($_FILES[$field]['size'] > 5 * 1024 * 1024) return null; // max 5MB
    $name = $prefix . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
    if (move_uploaded_file($_FILES[$field]['tmp_name'], UPLOAD_PATH . $name)) {
        return $name;
    }
    return null;
}

function gambar_produk($file) {
    if (!$file || $file === 'default.png') {
        return 'https://placehold.co/600x600/0d6efd/white?text=Indra+Konveksi';
    }
    $path = UPLOAD_PATH . $file;
    if (file_exists($path)) return UPLOAD_URL . $file;
    return 'https://placehold.co/600x600/0d6efd/white?text=Produk';
}

function kirim_email_order($to, $subject, $body) {
    return send_smtp_mail($to, $subject, $body);
}

/**
 * Kirim email via SMTP (PHPMailer-compatible).
 * Otomatis no-op kalau config SMTP disabled / kosong.
 */
function send_smtp_mail(string $to, string $subject, string $body, ?string $to_name = null): bool {
    $cfg = require __DIR__ . '/../config/smtp.php';
    if (empty($cfg['enabled']) || empty($cfg['username']) || empty($cfg['password'])) {
        return false;
    }
    require_once __DIR__ . '/SmtpMailer.php';
    $mailer = new SmtpMailer($cfg);
    return $mailer->send($to, $subject, $body, $to_name);
}

/**
 * Kirim notifikasi WhatsApp ke admin via Fonnte/Wablas API.
 * Otomatis no-op kalau config disabled.
 */
function kirim_wa_admin(string $pesan): bool {
    $cfg = require __DIR__ . '/../config/wa_api.php';
    if (empty($cfg['enabled']) || empty($cfg['token'])) return false;

    $no = $cfg['admin_wa'];
    if ($cfg['provider'] === 'fonnte') {
        $url = 'https://api.fonnte.com/send';
        $headers = ['Authorization: ' . $cfg['token']];
        $data = ['target' => $no, 'message' => $pesan];
    } else { // wablas
        $url = rtrim($cfg['endpoint'] ?: 'https://console.wablas.com', '/') . '/api/send-message';
        $headers = ['Authorization: ' . $cfg['token']];
        $data = ['phone' => $no, 'message' => $pesan];
    }

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $data,
        CURLOPT_HTTPHEADER     => $headers,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);
    $resp = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $code >= 200 && $code < 300;
}

/**
 * Estimasi tanggal pesanan selesai = tanggal order + 30 hari (1 bulan kerja).
 * Konstanta WAKTU_PENGERJAAN_HARI bisa diubah jika nanti durasi pengerjaan berbeda.
 */
const WAKTU_PENGERJAAN_HARI = 30;

function estimasi_selesai($created_at, $format = 'd M Y') {
    $ts = strtotime($created_at ?: 'now');
    return date($format, $ts + (WAKTU_PENGERJAAN_HARI * 86400));
}

/**
 * Format struk pesanan untuk dikirim via WhatsApp.
 * $detail = row dari JOIN pesanan + users + produk (key: kode, created_at, user_nama, email, no_hp,
 *           produk_nama, kategori_nama, bahan, ukuran, warna, catatan, jumlah, satuan, total_harga, status, file_desain)
 * $untuk  = 'admin' (customer mengirim ke admin) atau 'customer' (admin mengirim ke customer)
 */
function format_struk_wa($detail, $untuk = 'admin') {
    $line = "━━━━━━━━━━━━━━━━━";
    $sapaan = ($untuk === 'admin')
        ? "Halo Admin, saya ingin konfirmasi pesanan saya:"
        : "Halo Kak " . ($detail['user_nama'] ?? 'Customer') . ", berikut detail pesanan Anda:";

    $msg  = "*STRUK PESANAN*\n";
    $msg .= "$line\n";
    $msg .= $sapaan . "\n\n";
    $msg .= "*Kode Pesanan:* " . ($detail['kode'] ?? '-') . "\n";
    $msg .= "*Tanggal:* " . date('d M Y H:i', strtotime($detail['created_at'] ?? 'now')) . "\n";
    $msg .= "*Estimasi Selesai:* " . estimasi_selesai($detail['created_at'] ?? 'now') . "  _(±" . WAKTU_PENGERJAAN_HARI . " hari pengerjaan)_\n";
    $msg .= "*Status:* " . ($detail['status'] ?? 'Baru') . "\n";
    if ($untuk === 'customer') {
        $msg .= "*Nama:* " . ($detail['user_nama'] ?? '-') . "\n";
    }
    $msg .= "$line\n";
    $msg .= "*PRODUK YANG DIPESAN*\n";
    if (!empty($detail['kategori_nama'])) {
        $msg .= "Kategori : " . $detail['kategori_nama'] . "\n";
    }
    $msg .= "Produk   : " . ($detail['produk_nama'] ?? '-') . "\n";
    $msg .= "Jumlah   : " . (int)($detail['jumlah'] ?? 0) . " " . ($detail['satuan'] ?? 'pcs') . "\n";
    if (!empty($detail['bahan']))  $msg .= "Bahan    : " . $detail['bahan'] . "\n";
    if (!empty($detail['ukuran'])) $msg .= "Ukuran   : " . $detail['ukuran'] . "\n";
    if (!empty($detail['warna']))  $msg .= "Warna    : " . $detail['warna'] . "\n";
    if (!empty($detail['catatan'])) {
        $msg .= "\n*Catatan / Custom:*\n" . $detail['catatan'] . "\n";
    }
    if (!empty($detail['file_desain'])) {
        $msg .= "\nFile desain sudah saya upload di website.\n";
    }
    $msg .= "$line\n";
    $msg .= "*TOTAL ESTIMASI: " . rupiah($detail['total_harga'] ?? 0) . "*\n";
    $msg .= "$line\n";
    if ($untuk === 'admin') {
        $msg .= "Mohon konfirmasi & info pembayaran ya. Terima kasih 🙏";
    } else {
        $msg .= "Mohon konfirmasi balik ya kak. Terima kasih 🙏";
    }
    return $msg;
}

/**
 * Generate URL wa.me dengan teks struk pre-filled.
 */
function wa_link_struk($no_hp, $detail, $untuk = 'admin') {
    $no = preg_replace('/[^0-9]/', '', $no_hp);
    if (substr($no, 0, 1) === '0') $no = '62' . substr($no, 1);
    return "https://wa.me/$no?text=" . urlencode(format_struk_wa($detail, $untuk));
}
