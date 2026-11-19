<?php
/**
 * =====================================================
 * KONFIGURASI SLIDE CAROUSEL BERANDA
 * =====================================================
 * File ini diedit langsung oleh programmer (bukan dari panel admin).
 *
 * Cara mengganti slide:
 * 1. Siapkan gambar (rekomendasi resolusi 1920x800px, format JPG/PNG)
 * 2. Letakkan file di folder: assets/images/slides/
 * 3. Edit array di bawah ini:
 *    - 'gambar'   : nama file di folder slides (cth: 'slide1.jpg')
 *    - 'judul'    : judul besar slide (huruf kapital biar promo banget)
 *    - 'subjudul' : deskripsi singkat / promo / tagline
 *    - 'tombol'   : teks tombol CTA (Call To Action)
 *    - 'link'     : tujuan tombol (cth: 'katalog.php' atau 'custom-order.php')
 *
 * Untuk menambah/mengurangi slide, tinggal tambah/hapus item array.
 * Minimal 1 slide, maksimal disarankan 5 slide.
 * =====================================================
 */

return [
    [
        'gambar'   => 'slide1.png',
        'judul'    => '',
        'subjudul' => '',
        'tombol'   => '',
        'link'     => '',
    ],
    [
        'gambar'   => 'slide2.png',
        'judul'    => '',
        'subjudul' => '',
        'tombol'   => '',
        'link'     => '',
    ],
    [
        'gambar'   => 'slide3.png',
        'judul'    => '',
        'subjudul' => '.',
        'tombol'   => '',
        'link'     => '',
    ],
];
