<?php
/**
 * =====================================================
 * KONFIGURASI WHATSAPP API (Fonnte / Wablas)
 * =====================================================
 *
 * Pilih salah satu provider, isi credential, lalu set 'enabled' = true.
 *
 * A. FONNTE (https://fonnte.com)
 *    - Daftar, scan QR untuk hubungkan WhatsApp
 *    - Copy "Device Token" dari dashboard
 *    - Set 'provider' = 'fonnte'
 *    - Set 'token' = isi token-mu
 *
 * B. WABLAS (https://wablas.com)
 *    - Daftar, scan QR
 *    - Copy API Key dari dashboard
 *    - Set 'provider' = 'wablas'
 *    - Set 'token' = API Key
 *    - Set 'endpoint' = URL endpoint Wablas server kamu (cth: https://jkt.wablas.com)
 *
 * Saat aktif: setiap pesanan baru → admin otomatis dapat notifikasi WA berisi struk.
 * Kalau 'enabled' = false → fitur dimatikan, customer tetap kirim manual via tombol WA.
 * =====================================================
 */

return [
    'enabled'   => false,
    'provider'  => 'fonnte',           // 'fonnte' atau 'wablas'
    'token'     => '',                 // device token / API key
    'endpoint'  => '',                 // wajib untuk wablas (kosong = pakai default fonnte)
    'admin_wa'  => '628123456789',     // nomor admin penerima (format 62xxx, tanpa + atau spasi)
];
