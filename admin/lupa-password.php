<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

if (is_admin()) redirect(BASE_URL . 'admin/index.php');

// Session key untuk state OTP admin
$SK_AID  = 'otp_ap_aid';
$SK_CHAN = 'otp_ap_channel';
$SK_DEST = 'otp_ap_dest';
$SK_EXP  = 'otp_ap_exp';

$step = $_GET['step'] ?? 'request';
if (!in_array($step, ['request', 'verify'], true)) $step = 'request';

$dev_otp = null;

// ============ STEP 1: Kirim OTP ============
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $step === 'request') {
    $kontak  = trim($_POST['kontak'] ?? '');
    $channel = $_POST['channel'] ?? 'email';
    if (!in_array($channel, ['email','wa'], true)) $channel = 'email';

    if ($kontak === '') {
        flash('err', 'Mohon isi username, email, atau nomor HP admin.', 'danger');
    } else {
        // Admin bisa lookup by username, email, atau no_hp
        $stmt = $pdo->prepare("SELECT id, username, nama, email, no_hp FROM admin WHERE username=? OR email=? OR no_hp=? LIMIT 1");
        $stmt->execute([$kontak, $kontak, $kontak]);
        $a = $stmt->fetch();

        if ($a) {
            // Fallback: kalau admin belum set email/no_hp sendiri,
            // pakai kontak dari tabel pengaturan (email_admin / wa_admin)
            if ($channel === 'email') {
                $destination = $a['email'] ?: ($pengaturan['email_admin'] ?? '');
            } else {
                $destination = $a['no_hp'] ?: ($pengaturan['wa_admin'] ?? '');
            }

            if (empty($destination)) {
                $label = $channel === 'email' ? 'email' : 'nomor WhatsApp';
                flash('err', "Admin belum memiliki $label terdaftar. Isi melalui menu Pengaturan atau kolom email/no_hp di database.", 'danger');
            } else {
                $res = kirim_otp($pdo, 'admin', (int)$a['id'], $channel, $destination, $a['nama']);

                $_SESSION[$SK_AID]  = (int)$a['id'];
                $_SESSION[$SK_CHAN] = $channel;
                $_SESSION[$SK_DEST] = $destination;
                $_SESSION[$SK_EXP]  = $res['expires_at'];

                if (!$res['ok']) $dev_otp = $res['otp'];
                redirect(BASE_URL . 'admin/lupa-password.php?step=verify' . ($dev_otp ? '&dev=1' : ''));
            }
        } else {
            flash('err', 'Data admin tidak ditemukan.', 'danger');
        }
    }
}

// ============ STEP 2: Verifikasi OTP ============
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $step === 'verify') {
    $otp_input = trim($_POST['otp'] ?? '');
    $aid       = $_SESSION[$SK_AID] ?? 0;

    if (!$aid) {
        flash('err', 'Sesi verifikasi kedaluwarsa. Silakan request OTP baru.', 'danger');
        redirect(BASE_URL . 'admin/lupa-password.php');
    }

    $res = verifikasi_otp($pdo, 'admin', (int)$aid, $otp_input);
    if ($res['valid']) {
        $_SESSION['otp_verified_admin_id']    = (int)$aid;
        $_SESSION['otp_verified_admin_expires_at'] = time() + 600;
        unset($_SESSION[$SK_AID], $_SESSION[$SK_CHAN], $_SESSION[$SK_DEST], $_SESSION[$SK_EXP]);
        flash('ok', 'OTP berhasil diverifikasi. Silakan buat password baru.', 'success');
        redirect(BASE_URL . 'admin/reset-password.php');
    } else {
        flash('err', $res['error'], 'danger');
    }
}

// Kirim ulang OTP
if ($step === 'verify' && ($_GET['resend'] ?? '') === '1') {
    $aid  = $_SESSION[$SK_AID] ?? 0;
    $chan = $_SESSION[$SK_CHAN] ?? '';
    $dest = $_SESSION[$SK_DEST] ?? '';
    if ($aid && $chan && $dest) {
        $stmt = $pdo->prepare("SELECT nama FROM admin WHERE id=?");
        $stmt->execute([$aid]);
        $nama = $stmt->fetchColumn() ?: '';
        $res = kirim_otp($pdo, 'admin', (int)$aid, $chan, $dest, $nama);
        $_SESSION[$SK_EXP] = $res['expires_at'];
        if (!$res['ok']) $dev_otp = $res['otp'];
        flash('ok', 'OTP baru telah dikirim ke ' . e($dest) . '.', 'success');
    }
}

// Ambil dev OTP setelah redirect
if (($_GET['dev'] ?? '') === '1' && !$dev_otp) {
    $aid = $_SESSION[$SK_AID] ?? 0;
    if ($aid) {
        $stmt = $pdo->prepare("SELECT otp_code FROM otp_codes WHERE target_type='admin' AND target_id=? AND used=0 ORDER BY id DESC LIMIT 1");
        $stmt->execute([$aid]);
        $dev_otp = $stmt->fetchColumn() ?: null;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lupa Password Admin - <?= e($pengaturan['nama_situs']) ?></title>
    <link rel="icon" type="image/jpeg" href="<?= LOGO_URL ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <style>body { background: linear-gradient(135deg, #0a2540, #0d4f8a); min-height: 100vh; display: flex; align-items: center; }</style>
</head>
<body>
<div class="container">
    <div class="auth-wrap">
        <div class="text-center mb-4">
            <img src="<?= LOGO_URL ?>" alt="Logo" style="width:100px; height:100px; border-radius:50%; object-fit:cover; box-shadow:0 6px 20px rgba(0,0,0,.25); background:#fff; padding:4px;">
            <h2 class="mt-3"><?= $step === 'verify' ? 'Verifikasi OTP Admin' : 'Lupa Password Admin' ?></h2>
            <p class="text-muted small mb-0">
                <?= $step === 'verify'
                    ? 'Kode OTP sudah dikirim ke ' . e($_SESSION[$SK_DEST] ?? 'kontak admin')
                    : 'Kirim OTP ke email atau WhatsApp admin' ?>
            </p>
        </div>
        <?php show_flash(); ?>

        <?php if ($dev_otp): ?>
            <div class="alert alert-warning small" data-testid="dev-otp-hint-admin">
                <strong><i class="bi bi-tools"></i> Mode Dev:</strong>
                Kode OTP Admin: <strong style="font-size:1.3rem;letter-spacing:4px;"><?= e($dev_otp) ?></strong>
            </div>
        <?php endif; ?>

        <?php if ($step === 'request'): ?>
            <form method="POST" data-testid="form-admin-otp-request">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label">Username / Email / Nomor HP Admin</label>
                    <input type="text" name="kontak" class="form-control" required autofocus
                           placeholder="admin, email@contoh.com, atau 08xxx"
                           data-testid="input-admin-kontak">
                    <div class="form-text">Isi salah satu identitas admin yang terdaftar.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kirim OTP ke:</label>
                    <div class="d-flex gap-2">
                        <label class="flex-fill border rounded p-3 d-flex align-items-center gap-2" style="cursor:pointer;">
                            <input type="radio" name="channel" value="email" checked data-testid="admin-channel-email">
                            <span><i class="bi bi-envelope-fill text-primary"></i> Email</span>
                        </label>
                        <label class="flex-fill border rounded p-3 d-flex align-items-center gap-2" style="cursor:pointer;">
                            <input type="radio" name="channel" value="wa" data-testid="admin-channel-wa">
                            <span><i class="bi bi-whatsapp text-success"></i> WhatsApp</span>
                        </label>
                    </div>
                    <div class="form-text">
                        Bila admin belum set email/HP pribadi, OTP dikirim ke kontak resmi toko
                        (Pengaturan → email_admin / wa_admin).
                    </div>
                </div>
                <button class="btn btn-primary w-100" data-testid="btn-admin-kirim-otp">
                    <i class="bi bi-send"></i> Kirim Kode OTP
                </button>
            </form>
            <div class="text-center mt-3 small">
                <a href="<?= BASE_URL ?>admin/login.php">← Kembali ke Login Admin</a>
            </div>

        <?php else: ?>
            <?php if (!($_SESSION[$SK_AID] ?? 0)): ?>
                <div class="alert alert-warning"><i class="bi bi-exclamation-triangle"></i> Sesi tidak valid.</div>
                <a href="<?= BASE_URL ?>admin/lupa-password.php" class="btn btn-primary w-100">Mulai Ulang</a>
            <?php else: ?>
                <form method="POST" data-testid="form-admin-otp-verify">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Kode OTP (6 digit)</label>
                        <input type="text" name="otp" inputmode="numeric" pattern="[0-9]{6}" maxlength="6"
                               class="form-control form-control-lg text-center fw-bold"
                               style="letter-spacing:12px; font-size:1.8rem;"
                               required autofocus autocomplete="one-time-code"
                               data-testid="input-admin-otp">
                        <div class="form-text">
                            Kode berlaku <strong>10 menit</strong>.
                            <?php if (!empty($_SESSION[$SK_EXP])): ?>
                                Kedaluwarsa: <?= date('H:i:s', strtotime($_SESSION[$SK_EXP])) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <button class="btn btn-primary w-100 mb-2" data-testid="btn-admin-verifikasi-otp">
                        <i class="bi bi-shield-check"></i> Verifikasi OTP
                    </button>
                </form>
                <div class="d-flex justify-content-between small mt-3">
                    <a href="<?= BASE_URL ?>admin/lupa-password.php?step=verify&resend=1" data-testid="link-admin-resend-otp">
                        <i class="bi bi-arrow-clockwise"></i> Kirim Ulang OTP
                    </a>
                    <a href="<?= BASE_URL ?>admin/lupa-password.php" class="text-muted">Ganti kontak</a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
