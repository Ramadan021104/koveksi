<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$cfg = require __DIR__ . '/config/google_oauth.php';
if (empty($cfg['client_id']) || empty($cfg['client_secret'])) {
    flash('err', 'Fitur Google login belum dikonfigurasi. Hubungi admin/programmer.', 'danger');
    redirect(BASE_URL . 'login.php');
}

$state = bin2hex(random_bytes(16));
$_SESSION['oauth_state'] = $state;

$params = [
    'client_id'     => $cfg['client_id'],
    'redirect_uri'  => $cfg['redirect_uri'],
    'response_type' => 'code',
    'scope'         => $cfg['scopes'],
    'state'         => $state,
    'access_type'   => 'online',
    'prompt'        => 'select_account',
];

$url = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
redirect($url);
