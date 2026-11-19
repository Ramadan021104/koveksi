<?php
/**
 * Tes Koneksi MySQL InfinityFree
 * Buka file ini di browser untuk memastikan koneksi DB berhasil.
 * HAPUS file ini setelah selesai testing! (demi keamanan)
 */

echo "<h3>Tes Koneksi MySQL InfinityFree</h3>";

// Load konfigurasi DB
require_once __DIR__ . '/config/db.php';

try {
    echo "<p style='color:green'><strong>✅ KONEKSI MYSQL BERHASIL!</strong></p>";
    echo "<p>Versi MySQL: " . $pdo->query("SELECT VERSION()")->fetchColumn() . "</p>";
    
    // Cek tabel
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    if (count($tables) > 0) {
        echo "<p>Tabel ditemukan: " . count($tables) . "</p>";
        echo "<ul>";
        foreach ($tables as $t) {
            echo "<li>$t</li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color:orange'>⚠️ Database kosong. Silakan import <code>database.sql</code> via phpMyAdmin.</p>";
    }
} catch (PDOException $e) {
    echo "<p style='color:red'><strong>❌ KONEKSI GAGAL!</strong></p>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<h4>Checklist:</h4>";
    echo "<ul>";
    echo "<li>Pastikan nama database, username, dan password di <code>config/db.php</code> sudah benar</li>";
    echo "<li>Pastikan MySQL Host sudah benar (cek di panel InfinityFree → MySQL Databases)</li>";
    echo "<li>Pastikan database sudah dibuat di panel InfinityFree</li>";
    echo "</ul>";
}
