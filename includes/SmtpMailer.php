<?php
/**
 * Simple SMTP Mailer (kompatibel PHPMailer)
 * Tidak butuh composer. Mendukung SSL & STARTTLS, AUTH LOGIN, dan HTML body.
 */
class SmtpMailer
{
    private $host, $port, $secure, $user, $pass, $from_email, $from_name;
    private $conn;
    public  $debug = false;

    public function __construct(array $cfg)
    {
        $this->host       = $cfg['host'];
        $this->port       = (int)$cfg['port'];
        $this->secure     = $cfg['secure'] ?? 'tls'; // 'tls' atau 'ssl'
        $this->user       = $cfg['username'];
        $this->pass       = $cfg['password'];
        $this->from_email = $cfg['from_email'];
        $this->from_name  = $cfg['from_name'] ?? '';
    }

    public function send(string $to, string $subject, string $html_body, ?string $to_name = null): bool
    {
        try {
            $proto = ($this->secure === 'ssl') ? 'ssl://' : '';
            $ctx = stream_context_create([
                'ssl' => ['verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true],
            ]);
            $this->conn = @stream_socket_client($proto . $this->host . ':' . $this->port, $errno, $errstr, 15, STREAM_CLIENT_CONNECT, $ctx);
            if (!$this->conn) {
                error_log("SMTP connect failed: $errstr");
                return false;
            }
            $this->read(); // greeting 220
            $this->cmd("EHLO " . ($_SERVER['HTTP_HOST'] ?? 'localhost'), 250);

            if ($this->secure === 'tls') {
                $this->cmd("STARTTLS", 220);
                stream_socket_enable_crypto($this->conn, true, STREAM_CRYPTO_METHOD_TLS_CLIENT
                    | STREAM_CRYPTO_METHOD_TLSv1_1_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT);
                $this->cmd("EHLO " . ($_SERVER['HTTP_HOST'] ?? 'localhost'), 250);
            }

            // AUTH LOGIN
            $this->cmd("AUTH LOGIN", 334);
            $this->cmd(base64_encode($this->user), 334);
            $this->cmd(base64_encode($this->pass), 235);

            $this->cmd("MAIL FROM: <" . $this->from_email . ">", 250);
            $this->cmd("RCPT TO: <" . $to . ">", [250, 251]);
            $this->cmd("DATA", 354);

            $headers  = "From: " . $this->encode_header($this->from_name) . " <" . $this->from_email . ">\r\n";
            $headers .= "To: " . ($to_name ? $this->encode_header($to_name) . " <$to>" : $to) . "\r\n";
            $headers .= "Subject: " . $this->encode_header($subject) . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            $headers .= "Content-Transfer-Encoding: 8bit\r\n";
            $headers .= "Date: " . date('r') . "\r\n";

            $body = preg_replace('/^\./m', '..', $html_body); // dot-stuffing
            $this->cmd($headers . "\r\n" . $body . "\r\n.", 250);
            $this->cmd("QUIT", 221);
            fclose($this->conn);
            return true;
        } catch (Exception $e) {
            error_log("SMTP error: " . $e->getMessage());
            if ($this->conn) @fclose($this->conn);
            return false;
        }
    }

    private function cmd(string $line, $expect): void
    {
        if ($this->debug) error_log(">>> $line");
        fwrite($this->conn, $line . "\r\n");
        $resp = $this->read();
        $code = (int)substr($resp, 0, 3);
        $ok = is_array($expect) ? in_array($code, $expect) : ($code === $expect);
        if (!$ok) throw new Exception("Unexpected SMTP response: $resp");
    }

    private function read(): string
    {
        $data = '';
        while (($line = fgets($this->conn, 515)) !== false) {
            $data .= $line;
            if (isset($line[3]) && $line[3] === ' ') break;
        }
        if ($this->debug) error_log("<<< $data");
        return $data;
    }

    private function encode_header(string $s): string
    {
        return preg_match('/[\x80-\xff]/', $s) ? '=?UTF-8?B?' . base64_encode($s) . '?=' : $s;
    }
}
