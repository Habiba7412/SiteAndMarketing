<?php
/**
 * Dynamic Database-Driven Email Engine - DigiRare Technologies
 * Retrieves SMTP and Mailer settings directly from MySQL `email_settings` table.
 * Supports SMTP (TLS / SSL / Direct socket handshake) & Native PHP mail().
 */

if (!function_exists('getMailSettings')) {

    function getMailSettings($pdo) {
        static $cachedSettings = null;
        if ($cachedSettings !== null) return $cachedSettings;

        try {
            $stmt = $pdo->query("SELECT * FROM `email_settings` WHERE `id` = 1");
            $settings = $stmt->fetch();
            if ($settings) {
                $cachedSettings = $settings;
                return $cachedSettings;
            }
        } catch (Exception $e) {
            // Fallback default array if database query fails
        }

        $cachedSettings = [
            'mail_engine' => 'smtp',
            'smtp_host' => 'smtp.gmail.com',
            'smtp_port' => 587,
            'smtp_encryption' => 'tls',
            'smtp_auth' => 1,
            'smtp_username' => '',
            'smtp_password' => '',
            'from_name' => 'DigiRare Technologies',
            'from_email' => 'digiraremarketing@gmail.com',
            'admin_email' => 'digiraremarketing@gmail.com',
            'is_enabled' => 1
        ];
        return $cachedSettings;
    }

    /**
     * Send email dynamically using database credentials
     */
    function sendDynamicEmail($pdo, $to, $subject, $bodyHTML, $replyToEmail = null, $replyToName = null) {
        $settings = getMailSettings($pdo);

        if (empty($settings['is_enabled'])) {
            return ['success' => false, 'message' => 'Email delivery is currently disabled in database settings.'];
        }

        $fromEmail = !empty($settings['from_email']) ? $settings['from_email'] : 'digiraremarketing@gmail.com';
        $fromName  = !empty($settings['from_name'])  ? $settings['from_name']  : 'DigiRare Technologies';
        
        $replyEmail = $replyToEmail ?: $fromEmail;
        $replyName  = $replyToName  ?: $fromName;

        $mailEngine = $settings['mail_engine'] ?? 'smtp';

        if ($mailEngine === 'smtp' && !empty($settings['smtp_host'])) {
            return sendViaSmtpSocket($settings, $to, $subject, $bodyHTML, $replyEmail, $replyName);
        } else {
            return sendViaPhpMail($fromEmail, $fromName, $to, $subject, $bodyHTML, $replyEmail, $replyName);
        }
    }

    /**
     * Native Socket SMTP Delivery with Auth Handshake
     */
    function sendViaSmtpSocket($settings, $to, $subject, $bodyHTML, $replyEmail, $replyName) {
        $host     = $settings['smtp_host'];
        $port     = (int)($settings['smtp_port'] ?: 587);
        $crypto   = strtolower($settings['smtp_encryption'] ?? 'tls');
        $username = $settings['smtp_username'];
        $password = $settings['smtp_password'];
        $from     = $settings['from_email'];
        $fromName = $settings['from_name'];

        $socketPrefix = ($crypto === 'ssl') ? 'ssl://' : 'tcp://';
        $timeout = 12;

        $fp = @fsockopen($socketPrefix . $host, $port, $errno, $errstr, $timeout);
        if (!$fp) {
            // Fall back to native PHP mail if socket connection is unreachable (e.g. local dev without SMTP server)
            return sendViaPhpMail($from, $fromName, $to, $subject, $bodyHTML, $replyEmail, $replyName, "SMTP Connection to {$host}:{$port} failed: {$errstr}");
        }

        stream_set_timeout($fp, $timeout);

        $getResponse = function() use ($fp) {
            $res = '';
            while ($line = fgets($fp, 512)) {
                $res .= $line;
                if (substr($line, 3, 1) === ' ') break;
            }
            return $res;
        };

        $sendCommand = function($cmd) use ($fp, $getResponse) {
            fputs($fp, $cmd . "\r\n");
            return $getResponse();
        };

        $greeting = $getResponse();
        
        // EHLO
        $ehlo = $sendCommand("EHLO " . gethostname());

        if ($crypto === 'tls') {
            $sendCommand("STARTTLS");
            if (!stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT)) {
                fclose($fp);
                return sendViaPhpMail($from, $fromName, $to, $subject, $bodyHTML, $replyEmail, $replyName, "TLS Handshake failed");
            }
            // Re-send EHLO after TLS
            $sendCommand("EHLO " . gethostname());
        }

        // Authenticate if credentials provided
        if (!empty($username) && !empty($password)) {
            $authRes = $sendCommand("AUTH LOGIN");
            if (substr($authRes, 0, 3) === '334') {
                $sendCommand(base64_encode($username));
                $passRes = $sendCommand(base64_encode($password));
                if (substr($passRes, 0, 3) !== '235') {
                    fclose($fp);
                    return ['success' => false, 'message' => "SMTP Authentication failed for user {$username}."];
                }
            }
        }

        // MAIL FROM & RCPT TO
        $sendCommand("MAIL FROM: <{$from}>");
        $rcptRes = $sendCommand("RCPT TO: <{$to}>");
        if (substr($rcptRes, 0, 3) !== '250' && substr($rcptRes, 0, 3) !== '251') {
            fclose($fp);
            return ['success' => false, 'message' => "SMTP Recipient Rejected for {$to}."];
        }

        // DATA
        $sendCommand("DATA");

        // Headers & MIME Body
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: =?UTF-8?B?" . base64_encode($fromName) . "?= <{$from}>\r\n";
        $headers .= "To: <{$to}>\r\n";
        $headers .= "Reply-To: =?UTF-8?B?" . base64_encode($replyName) . "?= <{$replyEmail}>\r\n";
        $headers .= "Subject: =?UTF-8?B?" . base64_encode($subject) . "?=\r\n";
        $headers .= "Date: " . date('r') . "\r\n";
        $headers .= "X-Mailer: DigiRare Dynamic PHP Mailer\r\n";

        $fullPayload = $headers . "\r\n" . $bodyHTML . "\r\n.";
        $dataRes = $sendCommand($fullPayload);

        $sendCommand("QUIT");
        fclose($fp);

        if (substr($dataRes, 0, 3) === '250') {
            return ['success' => true, 'message' => 'Email sent successfully via SMTP!'];
        } else {
            return sendViaPhpMail($from, $fromName, $to, $subject, $bodyHTML, $replyEmail, $replyName, "SMTP Data Error");
        }
    }

    /**
     * PHP Native mail() Fallback Delivery
     */
    function sendViaPhpMail($fromEmail, $fromName, $to, $subject, $bodyHTML, $replyEmail, $replyName, $fallbackNote = null) {
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: =?UTF-8?B?" . base64_encode($fromName) . "?= <{$fromEmail}>\r\n";
        $headers .= "Reply-To: =?UTF-8?B?" . base64_encode($replyName) . "?= <{$replyEmail}>\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

        $encodedSubject = "=?UTF-8?B?" . base64_encode($subject) . "?=";

        $sent = @mail($to, $encodedSubject, $bodyHTML, $headers);

        if ($sent) {
            $msg = 'Email dispatched successfully via PHP mail engine.';
            if ($fallbackNote) $msg .= " ({$fallbackNote})";
            return ['success' => true, 'message' => $msg];
        } else {
            return ['success' => false, 'message' => 'Failed to dispatch email via mail engine. Please check SMTP host server settings.'];
        }
    }

    /**
     * Test Email Execution Tool for Dashboard
     */
    function sendTestEmail($pdo, $testRecipient) {
        $subject = "DigiRare Admin - SMTP Email Integration Test";
        $body = "
        <div style=\"font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; max-width: 600px; margin: 0 auto; background-color: #0b1315; color: #e2e8f0; border-radius: 16px; overflow: hidden; border: 1px solid #1e293b;\">
            <div style=\"background: linear-gradient(135deg, #0284c7, #10b981); padding: 24px; text-align: center;\">
                <h1 style=\"color: #0b1315; margin: 0; font-size: 22px; font-weight: 800; letter-spacing: -0.5px;\">DigiRare Technologies</h1>
                <p style=\"color: #070c0e; margin: 4px 0 0; font-size: 13px; font-weight: 600; text-transform: uppercase;\">Dynamic Email System Integration Test</p>
            </div>
            <div style=\"padding: 32px;\">
                <h2 style=\"color: #38bdf8; font-size: 18px; margin-top: 0;\">SMTP Connection Successful!</h2>
                <p style=\"font-size: 14px; line-height: 1.6; color: #94a3b8;\">
                    Congratulations! Your SMTP database configuration is working properly. Emails sent from your contact forms, admin notifications, and client autoresponders will now be routed dynamically.
                </p>
                <div style=\"background-color: #0e1a1d; padding: 16px; border-radius: 12px; border-left: 4px solid #10b981; margin: 20px 0;\">
                    <p style=\"margin: 0; font-size: 12px; font-family: monospace; color: #cbd5e1;\">
                        <strong>Test Date:</strong> " . date('Y-m-d H:i:s T') . "<br>
                        <strong>Recipient:</strong> " . htmlspecialchars($testRecipient) . "<br>
                        <strong>Status:</strong> Verification Passed (Active)
                    </p>
                </div>
                <p style=\"font-size: 12px; color: #64748b; margin-bottom: 0;\">
                    This is an automated test message dispatched directly from your DigiRare Admin Dashboard.
                </p>
            </div>
            <div style=\"background-color: #070c0e; padding: 16px; text-align: center; border-top: 1px solid #1e293b; font-size: 11px; color: #64748b;\">
                &copy; " . date('Y') . " DigiRare Technologies. All rights reserved.
            </div>
        </div>";

        return sendDynamicEmail($pdo, $testRecipient, $subject, $body);
    }
}
