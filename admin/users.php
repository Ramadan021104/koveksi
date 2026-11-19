<?php
$page_title = "Customer";
require_once __DIR__ . '/includes/header.php';

if (($_GET['aksi'] ?? '') === 'hapus' && ($id = (int)($_GET['id'] ?? 0))) {
    $pdo->prepare("DELETE FROM users WHERE id=?")->execute([$id]);
    flash('ok', 'Customer dihapus.', 'success');
    redirect('users.php');
}

$users = $pdo->query("SELECT u.*, (SELECT COUNT(*) FROM pesanan WHERE id_user=u.id) AS jml_order FROM users u ORDER BY u.id DESC")->fetchAll();
?>

<p class="text-muted">Total: <strong><?= count($users) ?></strong> customer terdaftar</p>
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead><tr><th>#</th><th>Nama</th><th>Email</th><th>No HP</th><th>Pesanan</th><th>Daftar</th><th>Aksi</th></tr></thead>
            <tbody>
                <?php foreach ($users as $i => $u): ?>
                    <tr>
                        <td><?= $i+1 ?></td>
                        <td class="fw-semibold"><?= e($u['nama']) ?></td>
                        <td class="small"><?= e($u['email']) ?></td>
                        <td><?= e($u['no_hp']?:'-') ?></td>
                        <td><span class="badge bg-primary"><?= (int)$u['jml_order'] ?></span></td>
                        <td class="small text-muted"><?= date('d M Y', strtotime($u['created_at'])) ?></td>
                        <td><a href="?aksi=hapus&id=<?= (int)$u['id'] ?>" class="btn btn-sm btn-danger" data-confirm="Hapus customer ini?"><i class="bi bi-trash"></i></a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
