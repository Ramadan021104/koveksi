<?php
/**
 * =====================================================
 * KONFIGURASI SMTP (Untuk reset password via email & notif order)
 * =====================================================
 *
 * Cara setup (pilih salah satu):
 *
 * A. Pakai Gmail:
 *    - Login Gmail, buka https://myaccount.google.com/apppasswords
 *    - Generate "App Password" (16 karakter)
 *    - Isi 'username' = email Gmail Anda, 'password' = app password
 *    - 'host' = smtp.gmail.com, 'port' = 587, 'secure' = 'tls'
 *
 * B. Pakai Mailtrap (untuk testing, gratis):
 *    - Daftar di https://mailtrap.io
 *    - Copy SMTP credentials dari inbox sandbox
 *    - 'host' = sandbox.smtp.mailtrap.io, 'port' = 587
 *
 * C. Pakai SMTP hosting / SendGrid / Mailgun: isi sesuai provider
 *
 * Kalau credential dikosongkan, fitur email akan dimatikan otomatis.
 * =====================================================
 */

return [
    'enabled' => true, // ubah ke true setelah credential diisi
    'host'      => 'smtp.gmail.com',
    'port'      => 587,
    'secure'    => 'tls',  // 'tls' atau 'ssl'
    'username'  => 'mramadhanilham19@gmail.com',     // cth: 'admin@indrakonveksi.com'
    'password'  => 'vtnp befp zcfd mcpz',     // app password
    'from_email'=> 'mramadhanilham19@gmail.com',
    'from_name' => 'Indra Konveksi',
];
