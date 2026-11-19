<?php
$page_title = "Backup Database";
require_once __DIR__ . '/includes/header.php';

$backup_dir = __DIR__ . '/../backups/';
if (!is_dir($backup_dir)) @mkdir($backup_dir, 0775, true);

// =====================================================
// FUNCTION: Generate Backup SQL
// =====================================================
function generate_backup_sql(PDO $pdo, string $dir): ?string {
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    $sql = "-- Indra Konveksi Backup\n-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
    $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";
    foreach ($tables as $t) {
        $sql .= "-- Table: $t\nDROP TABLE IF EXISTS `$t`;\n";
        $create = $pdo->query("SHOW CREATE TABLE `$t`")->fetch();
        $sql .= $create['Create Table'] . ";\n\n";
        $rows = $pdo->query("SELECT * FROM `$t`")->fetchAll(PDO::FETCH_ASSOC);
        if ($rows) {
            $cols = '`' . implode('`,`', array_keys($rows[0])) . '`';
            foreach ($rows as $r) {
                $vals = array_map(fn($v) => $v===null ? 'NULL' : $pdo->quote($v), array_values($r));
                $sql .= "INSERT INTO `$t` ($cols) VALUES (" . implode(',', $vals) . ");\n";
            }
            $sql .= "\n";
        }
    }
    $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
    $file = $dir . 'backup_' . date('Ymd_His') . '.sql';
    return file_put_contents($file, $sql) === false ? null : $file;
}

// =====================================================
// FUNCTION: Generate Backup Excel (SpreadsheetML 2003 XML)
// Format ini bisa dibuka langsung di Microsoft Excel,
// LibreOffice Calc, Google Sheets — tanpa library tambahan.
// Setiap tabel database = 1 sheet terpisah.
// =====================================================
function generate_backup_excel(PDO $pdo, string $dir): ?string {
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    $generated = date('d-m-Y H:i:s');

    $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    $xml .= '<?mso-application progid="Excel.Sheet"?>' . "\n";
    $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"';
    $xml .= ' xmlns:o="urn:schemas-microsoft-com:office:office"';
    $xml .= ' xmlns:x="urn:schemas-microsoft-com:office:excel"';
    $xml .= ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"';
    $xml .= ' xmlns:html="http://www.w3.org/TR/REC-html40">' . "\n";

    // Document properties
    $xml .= '<DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">';
    $xml .= '<Title>Indra Konveksi - Backup Database</Title>';
    $xml .= '<Author>Indra Konveksi Admin</Author>';
    $xml .= '<Created>' . date('c') . '</Created>';
    $xml .= '</DocumentProperties>' . "\n";

    // Styles
    $xml .= '<Styles>';
    $xml .= '<Style ss:ID="Header">';
    $xml .= '<Font ss:Bold="1" ss:Color="#FFFFFF" ss:Size="11"/>';
    $xml .= '<Interior ss:Color="#0D6EFD" ss:Pattern="Solid"/>';
    $xml .= '<Alignment ss:Horizontal="Center" ss:Vertical="Center"/>';
    $xml .= '<Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/></Borders>';
    $xml .= '</Style>';
    $xml .= '<Style ss:ID="Cell">';
    $xml .= '<Borders>';
    $xml .= '<Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#DEE2E6"/>';
    $xml .= '<Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#DEE2E6"/>';
    $xml .= '</Borders>';
    $xml .= '</Style>';
    $xml .= '<Style ss:ID="Info">';
    $xml .= '<Font ss:Bold="1" ss:Size="14" ss:Color="#0D6EFD"/>';
    $xml .= '</Style>';
    $xml .= '</Styles>' . "\n";

    // ---- Sheet INFO (cover sheet) ----
    $xml .= '<Worksheet ss:Name="INFO">';
    $xml .= '<Table>';
    $xml .= '<Column ss:Width="200"/><Column ss:Width="350"/>';
    $xml .= '<Row><Cell ss:StyleID="Info"><Data ss:Type="String">INDRA KONVEKSI</Data></Cell></Row>';
    $xml .= '<Row><Cell><Data ss:Type="String">Backup Database</Data></Cell></Row>';
    $xml .= '<Row></Row>';
    $xml .= '<Row><Cell ss:StyleID="Header"><Data ss:Type="String">Field</Data></Cell>';
    $xml .= '<Cell ss:StyleID="Header"><Data ss:Type="String">Nilai</Data></Cell></Row>';
    $xml .= '<Row><Cell ss:StyleID="Cell"><Data ss:Type="String">Tanggal Backup</Data></Cell>';
    $xml .= '<Cell ss:StyleID="Cell"><Data ss:Type="String">' . htmlspecialchars($generated) . '</Data></Cell></Row>';
    $xml .= '<Row><Cell ss:StyleID="Cell"><Data ss:Type="String">Jumlah Tabel</Data></Cell>';
    $xml .= '<Cell ss:StyleID="Cell"><Data ss:Type="Number">' . count($tables) . '</Data></Cell></Row>';
    $xml .= '<Row><Cell ss:StyleID="Cell"><Data ss:Type="String">Database</Data></Cell>';
    $xml .= '<Cell ss:StyleID="Cell"><Data ss:Type="String">' . htmlspecialchars(defined('DB_NAME') ? DB_NAME : 'indra_konveksi') . '</Data></Cell></Row>';
    $xml .= '<Row></Row>';
    $xml .= '<Row><Cell ss:StyleID="Header"><Data ss:Type="String">No</Data></Cell>';
    $xml .= '<Cell ss:StyleID="Header"><Data ss:Type="String">Nama Tabel</Data></Cell></Row>';
    foreach ($tables as $i => $t) {
        $xml .= '<Row>';
        $xml .= '<Cell ss:StyleID="Cell"><Data ss:Type="Number">' . ($i+1) . '</Data></Cell>';
        $xml .= '<Cell ss:StyleID="Cell"><Data ss:Type="String">' . htmlspecialchars($t) . '</Data></Cell>';
        $xml .= '</Row>';
    }
    $xml .= '</Table></Worksheet>' . "\n";

    // ---- Sheet per tabel ----
    foreach ($tables as $t) {
        $rows = $pdo->query("SELECT * FROM `$t`")->fetchAll(PDO::FETCH_ASSOC);

        // Excel sheet name max 31 chars, no special chars
        $sheetName = substr(preg_replace('/[\[\]:\*\?\/\\\\]/', '_', $t), 0, 31);

        $xml .= '<Worksheet ss:Name="' . htmlspecialchars($sheetName) . '">';
        $xml .= '<Table>';

        if ($rows) {
            $cols = array_keys($rows[0]);
            // Header row
            $xml .= '<Row>';
            foreach ($cols as $c) {
                $xml .= '<Cell ss:StyleID="Header"><Data ss:Type="String">' . htmlspecialchars($c) . '</Data></Cell>';
            }
            $xml .= '</Row>';
            // Data rows
            foreach ($rows as $r) {
                $xml .= '<Row>';
                foreach ($r as $val) {
                    if ($val === null) {
                        $xml .= '<Cell ss:StyleID="Cell"><Data ss:Type="String"></Data></Cell>';
                    } elseif (is_numeric($val) && !preg_match('/^0\d+/', (string)$val)) {
                        // numeric (but skip if leading zero like "0812..." phone)
                        $xml .= '<Cell ss:StyleID="Cell"><Data ss:Type="Number">' . $val . '</Data></Cell>';
                    } else {
                        $safe = htmlspecialchars((string)$val, ENT_XML1 | ENT_QUOTES, 'UTF-8');
                        // remove non-printable chars yang bisa rusak XML
                        $safe = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $safe);
                        $xml .= '<Cell ss:StyleID="Cell"><Data ss:Type="String">' . $safe . '</Data></Cell>';
                    }
                }
                $xml .= '</Row>';
            }
        } else {
            $xml .= '<Row><Cell><Data ss:Type="String">(Tabel kosong)</Data></Cell></Row>';
        }

        $xml .= '</Table></Worksheet>' . "\n";
    }

    $xml .= '</Workbook>';

    $file = $dir . 'backup_' . date('Ymd_His') . '.xls';
    return file_put_contents($file, $xml) === false ? null : $file;
}

// =====================================================
// Auto-backup harian (SQL): kalau backup terakhir > 24 jam
// =====================================================
$auto_done = false;
$files_sql = glob($backup_dir . '*.sql');
$last_time = 0;
foreach ($files_sql as $f) {
    if (filemtime($f) > $last_time) $last_time = filemtime($f);
}
if (time() - $last_time > 86400) {
    $auto_done = generate_backup_sql($pdo, $backup_dir);
}

// =====================================================
// Manual backup actions
// =====================================================
if (($_GET['aksi'] ?? '') === 'buat') {
    $file = generate_backup_sql($pdo, $backup_dir);
    if ($file) flash('ok', 'Backup SQL berhasil dibuat: ' . basename($file), 'success');
    redirect('backup.php');
}
if (($_GET['aksi'] ?? '') === 'buat_excel') {
    $file = generate_backup_excel($pdo, $backup_dir);
    if ($file) flash('ok', 'Backup Excel berhasil dibuat: ' . basename($file), 'success');
    redirect('backup.php');
}

// =====================================================
// Download
// =====================================================
if (!empty($_GET['download'])) {
    $name = basename($_GET['download']);
    $path = $backup_dir . $name;
    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    if (file_exists($path) && in_array($ext, ['sql', 'xls'])) {
        if ($ext === 'sql') {
            header('Content-Type: application/sql');
        } else {
            // Excel — pakai MIME yang dikenali Excel/LibreOffice
            header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        }
        header('Content-Disposition: attachment; filename="' . $name . '"');
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;
    }
}

// =====================================================
// Hapus
// =====================================================
if (($_GET['aksi'] ?? '') === 'hapus' && !empty($_GET['file'])) {
    $name = basename($_GET['file']);
    $path = $backup_dir . $name;
    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    if (file_exists($path) && in_array($ext, ['sql', 'xls'])) @unlink($path);
    flash('ok', 'File backup dihapus.', 'success');
    redirect('backup.php');
}

// =====================================================
// Listing (SQL + Excel)
// =====================================================
$files = array_merge(
    glob($backup_dir . '*.sql') ?: [],
    glob($backup_dir . '*.xls') ?: []
);
usort($files, fn($a,$b)=> filemtime($b) - filemtime($a));
?>

<div class="row g-3 mb-4">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold"><i class="bi bi-cloud-arrow-down text-primary"></i> Backup Database</h6>
                <p class="text-muted small mb-3">
                    Backup otomatis (format SQL) dibuat tiap <strong>24 jam</strong> saat halaman ini diakses.
                    Anda juga bisa membuat backup manual kapan saja, baik format <strong>SQL</strong> (untuk restore via phpMyAdmin) maupun <strong>Excel</strong> (untuk laporan & arsip).
                </p>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="?aksi=buat" class="btn btn-primary">
                        <i class="bi bi-filetype-sql"></i> Buat Backup SQL
                    </a>
                    <a href="?aksi=buat_excel" class="btn btn-success">
                        <i class="bi bi-file-earmark-excel"></i> Buat Backup Excel
                    </a>
                </div>
                <?php if ($auto_done): ?>
                    <div class="mt-2"><span class="badge bg-info">Backup harian otomatis baru saja dibuat</span></div>
                <?php endif; ?>

                <hr class="my-3">
                <div class="small text-muted">
                    <strong>Perbedaan format:</strong>
                    <ul class="mb-0 mt-1">
                        <li><strong>SQL</strong> — bisa di-restore kembali ke database via phpMyAdmin atau MySQL Workbench. Wajib untuk pemulihan data.</li>
                        <li><strong>Excel (.xls)</strong> — setiap tabel jadi sheet terpisah. Cocok untuk laporan, analisa data, atau lampiran dokumen. Bisa dibuka di Excel / LibreOffice / Google Sheets.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card border-0 bg-info bg-opacity-10">
            <div class="card-body small">
                <h6 class="fw-bold"><i class="bi bi-clock-history"></i> Backup Terjadwal (Optional)</h6>
                <p class="mb-1">Untuk backup otomatis bahkan tanpa admin buka halaman ini, tambahkan ke cron/scheduler:</p>
                <p class="mb-0 mt-2 text-muted">
                    <i class="bi bi-info-circle"></i>
                    Disarankan simpan file backup di tempat aman (Google Drive, hard disk eksternal) secara berkala.
                </p>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0">Riwayat Backup</h6>
        <span class="badge bg-secondary"><?= count($files) ?> file</span>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Format</th>
                    <th>Nama File</th>
                    <th>Ukuran</th>
                    <th>Dibuat</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!$files): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">Belum ada backup.</td></tr>
                <?php else: foreach ($files as $f):
                    $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
                    $is_excel = ($ext === 'xls');
                ?>
                    <tr>
                        <td>
                            <?php if ($is_excel): ?>
                                <span class="badge bg-success"><i class="bi bi-file-earmark-excel"></i> Excel</span>
                            <?php else: ?>
                                <span class="badge bg-primary"><i class="bi bi-filetype-sql"></i> SQL</span>
                            <?php endif; ?>
                        </td>
                        <td class="fw-semibold"><?= e(basename($f)) ?></td>
                        <td><?= number_format(filesize($f)/1024, 1) ?> KB</td>
                        <td class="small text-muted"><?= date('d M Y H:i', filemtime($f)) ?></td>
                        <td class="text-end">
                            <a href="?download=<?= e(basename($f)) ?>" class="btn btn-sm btn-success" data-testid="download-backup-<?= e(basename($f)) ?>">
                                <i class="bi bi-download"></i> Download
                            </a>
                            <a href="?aksi=hapus&file=<?= e(basename($f)) ?>" class="btn btn-sm btn-danger" data-confirm="Hapus file backup ini?" data-testid="delete-backup-<?= e(basename($f)) ?>">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
