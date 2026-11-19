<?php
$page_title = "Tentang Kami";
require_once __DIR__ . '/includes/header.php';
?>
<!-- ============== HALAMAN TENTANG KAMI ==============
     Diedit langsung oleh programmer di file ini.
     Bebas tambah/edit text dan section sesuai kebutuhan.
===================================================== -->
<h2 class="section-title">Tentang <?= e($pengaturan['nama_situs']) ?></h2>
<div class="text-center mb-4">
    <img src="<?= LOGO_URL ?>" alt="Logo <?= e($pengaturan['nama_situs']) ?>" style="width:140px; height:140px; border-radius:50%; object-fit:cover; box-shadow:0 6px 24px rgba(13,79,138,.22); background:#fff; padding:5px;">
    <p class="text-muted small mt-2 mb-0">Identitas Resmi Toko</p>
</div>
<div class="row g-4">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <p class="lead"><?= e($pengaturan['tagline']) ?></p>
                <p>Indra Konveksi adalah usaha konveksi &amp; sablon yang berlokasi di Bengkulu, melayani pemesanan custom seragam, kaos, jaket, topi, ID card, lanyard, almamater, batik, hingga seragam dinas TNI/POLRI.</p>
                <p>Kami sudah dipercaya oleh berbagai instansi sekolah (TK, SD, SMP, SMA), perguruan tinggi, perusahaan outsourcing, dan komunitas di seluruh Bengkulu dan Sumatera.</p>
                <h5 class="fw-bold mt-4 text-primary">Keunggulan Kami</h5>
                <ul>
                    <li><strong>Bebas Kustom Segala Model</strong> – desain sesuai keinginan Anda.</li>
                    <li>Harga sudah termasuk <strong>bordir 1 titik</strong> &amp; furing peles/satin.</li>
                    <li>Bahan berkualitas: Drill, Ribstop, Twil, Cotton Combed, Kanvas Marsoto, dll.</li>
                    <li>Pengerjaan cepat, hasil rapi.</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h6 class="fw-bold">Klien Kami</h6>
                <ul class="small">
                    <li>SMA 06 Enggano</li>
                    <li>SMPN 17 Enggano</li>
                    <li>SLB 01 Kaur Utara</li>
                    <li>SMKN 01 Padang</li>
                    <li>MTSN 01 Bengkulu Tengah</li>
                    <li>PT. Narendra Dewa Yoga (NDY)</li>
                    <li>PT. Damar Outsourcing Amanah</li>
                    <li>...dan banyak lainnya</li>
                </ul>
                <a href="https://wa.me/<?= e($pengaturan['wa_admin']) ?>" target="_blank" class="btn btn-success w-100 mt-2">
                    <i class="bi bi-whatsapp"></i> Hubungi Kami
                </a>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
