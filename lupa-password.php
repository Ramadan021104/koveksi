<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

if (is_login()) redirect(BASE_URL . 'index.php');

$sent = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $stmt = $pdo->prepare("SELECT id, nama, email FROM users WHERE email=?");
    $stmt->execute([$email]);
    $u = $stmt->fetch();
    if ($u) {
        // Generate token (valid 1 jam)
        $token = bin2hex(random_bytes(32));
        $exp = (new DateTime('+1 hour'))->format('Y-m-d H:i:s');
        $pdo->prepare("INSERT INTO reset_token (id_user, token, expires_at) VALUES (?,?,?)")
            ->execute([$u['id'], $token, $exp]);

        $link = BASE_URL . "reset-password.php?token=" . $token;
        $body = "<h3>Reset Password</h3>"
              . "<p>Halo <strong>" . e($u['nama']) . "</strong>,</p>"
              . "<p>Klik link berikut untuk reset password (berlaku 1 jam):</p>"
              . "<p><a href=\"$link\" style=\"display:inline-block;background:#0d4f8a;color:#fff;padding:10px 20px;text-decoration:none;border-radius:6px;\">Reset Password Saya</a></p>"
              . "<p>Atau copy link ini:<br><code>$link</code></p>"
              . "<p>Jika Anda tidak meminta reset, abaikan email ini.</p>"
              . "<hr><p style=\"color:#888;font-size:12px\">Indra Konveksi - Email Otomatis</p>";

        $ok = send_smtp_mail($u['email'], 'Reset Password - Indra Konveksi', $body, $u['nama']);
        if (!$ok) {
            // Fallback: kalau SMTP tidak aktif, tampilkan link langsung di halaman (untuk testing)
            flash('reset_link', $link, 'info');
        }
    }
    // Tetap tampilkan pesan generic (jangan bocorkan apakah email ada/tidak)
    $sent = true;
}

$page_title = "Lupa Password";
require_once __DIR__ . '/includes/header.php';
?>

<div class="auth-wrap">
    <div class="text-center mb-4">
        <img src="<?= LOGO_URL ?>" alt="Logo <?= e($pengaturan['nama_situs']) ?>" style="width:90px; height:90px; border-radius:50%; object-fit:cover; box-shadow:0 4px 16px rgba(13,79,138,.18);">
        <h2 class="mt-3">Lupa Password?</h2>
        <p class="text-muted small mb-0">Kami akan kirim link reset ke email Anda</p>
    </div>

    <?php if ($sent): ?>
        <div class="alert alert-success">
            <i class="bi bi-check-circle"></i>
            Jika email tersebut terdaftar, kami sudah mengirim link reset password.
            Cek inbox (atau folder spam) Anda. Link berlaku <strong>1 jam</strong>.
        </div>
        <?php $f = $_SESSION['flash']['reset_link'] ?? null;
              if ($f) {
                  unset($_SESSION['flash']['reset_link']); ?>
            <div class="alert alert-warning small">
                <strong>Mode dev (SMTP belum aktif):</strong> Link reset langsung di sini:
                <div class="mt-2 text-break"><a href="<?= e($f['msg']) ?>"><?= e($f['msg']) ?></a></div>
            </div>
        <?php } ?>
        <a href="<?= BASE_URL ?>login.php" class="btn btn-outline-primary w-100">← Kembali ke Login</a>
    <?php else: ?>
        <form method="POST">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label">Email Akun Anda</label>
                <input type="email" name="email" class="form-control" required autofocus placeholder="email@contoh.com">
            </div>
            <button class="btn btn-primary w-100"><i class="bi bi-envelope"></i> Kirim Link Reset</button>
        </form>
        <p class="text-center mt-3 small"><a href="<?= BASE_URL ?>login.php">← Kembali ke Login</a></p>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
