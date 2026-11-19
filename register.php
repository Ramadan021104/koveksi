<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

if (is_login()) redirect(BASE_URL . 'index.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama  = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $no_hp = trim($_POST['no_hp'] ?? '');
    $alamat= trim($_POST['alamat'] ?? '');
    $pass  = $_POST['password'] ?? '';
    $pass2 = $_POST['password2'] ?? '';

    if (!$nama || !$email || !$pass || !$no_hp || !$alamat) {
        flash('err', 'Semua kolom (Nama, Email, No HP, Alamat, Password) wajib diisi.', 'danger');
    } elseif (!preg_match('/^[0-9+\-\s]{8,20}$/', $no_hp)) {
        flash('err', 'Format No HP tidak valid (gunakan angka, minimal 8 digit).', 'danger');
    } elseif (strlen($pass) < 6) {
        flash('err', 'Password minimal 6 karakter.', 'danger');
    } elseif ($pass !== $pass2) {
        flash('err', 'Konfirmasi password tidak cocok.', 'danger');
    } else {
        $cek = $pdo->prepare("SELECT id FROM users WHERE email=?");
        $cek->execute([$email]);
        if ($cek->fetch()) {
            flash('err', 'Email sudah terdaftar.', 'danger');
        } else {
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $ins = $pdo->prepare("INSERT INTO users (nama,email,password,no_hp,alamat) VALUES (?,?,?,?,?)");
            $ins->execute([$nama, $email, $hash, $no_hp, $alamat]);
            flash('ok', 'Registrasi berhasil. Silakan login.', 'success');
            redirect(BASE_URL . 'login.php');
        }
    }
}

$google_cfg = require __DIR__ . '/config/google_oauth.php';
$google_active = !empty($google_cfg['client_id']) && !empty($google_cfg['client_secret']);

$page_title = "Daftar Akun";
require_once __DIR__ . '/includes/header.php';
?>

<div class="auth-wrap">
    <div class="text-center mb-4">
        <img src="<?= LOGO_URL ?>" alt="Logo <?= e($pengaturan['nama_situs']) ?>" style="width:90px; height:90px; border-radius:50%; object-fit:cover; box-shadow:0 4px 16px rgba(13,79,138,.18);">
        <h2 class="mt-3">Daftar Akun</h2>
        <p class="text-muted small mb-0">Buat akun untuk mulai pesan custom</p>
    </div>

    <?php if ($google_active): ?>
        <a href="<?= BASE_URL ?>auth-google.php" class="btn btn-outline-secondary w-100 mb-3 d-flex align-items-center justify-content-center gap-2">
            <svg width="20" height="20" viewBox="0 0 48 48"><path fill="#FFC107" d="M43.6 20.5H42V20H24v8h11.3c-1.6 4.7-6 8-11.3 8-6.6 0-12-5.4-12-12s5.4-12 12-12c3.1 0 5.8 1.2 7.9 3l5.7-5.7C34 6.1 29.3 4 24 4 12.9 4 4 12.9 4 24s8.9 20 20 20 20-8.9 20-20c0-1.3-.1-2.4-.4-3.5z"/><path fill="#FF3D00" d="M6.3 14.7l6.6 4.8C14.6 16 19 13 24 13c3.1 0 5.8 1.2 7.9 3l5.7-5.7C34 6.1 29.3 4 24 4 16.3 4 9.7 8.3 6.3 14.7z"/><path fill="#4CAF50" d="M24 44c5.2 0 9.9-2 13.4-5.3l-6.2-5.2C29.3 35 26.8 36 24 36c-5.3 0-9.7-3.3-11.3-8l-6.5 5C9.5 39.6 16.2 44 24 44z"/><path fill="#1976D2" d="M43.6 20.5H42V20H24v8h11.3c-.8 2.3-2.2 4.2-4.1 5.6l6.2 5.2C41 35.9 44 30.4 44 24c0-1.3-.1-2.4-.4-3.5z"/></svg>
            <span class="fw-semibold">Lanjutkan dengan Google</span>
        </a>
        <div class="text-center text-muted small my-2">— atau daftar manual —</div>
    <?php endif; ?>

    <form method="POST">
                    <?= csrf_field() ?>
        <div class="mb-3">
            <label class="form-label">Nama Lengkap *</label>
            <input type="text" name="nama" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email *</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">No HP / WhatsApp *</label>
            <input type="text" name="no_hp" class="form-control" placeholder="08xxx" required pattern="[0-9+\-\s]{8,20}" title="Minimal 8 digit angka">
        </div>
        <div class="mb-3">
            <label class="form-label">Alamat *</label>
            <textarea name="alamat" class="form-control" rows="2" required></textarea>
        </div>
        <div class="row g-2">
            <div class="col-md-6 mb-3">
                <label class="form-label">Password * <span class="text-muted small">(min 6)</span></label>
                <input type="password" name="password" class="form-control" required minlength="6">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Konfirmasi Password *</label>
                <input type="password" name="password2" class="form-control" required minlength="6">
            </div>
        </div>
        <button class="btn btn-primary w-100">Daftar</button>
    </form>
    <p class="text-center mt-3 small">Sudah punya akun? <a href="<?= BASE_URL ?>login.php" class="fw-semibold">Login di sini</a></p>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
