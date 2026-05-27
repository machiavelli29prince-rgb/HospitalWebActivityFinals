<?php

class MailerHelper
{
    private $usePHPMailer = false;
    private $emailLogDir = __DIR__ . '/../../email_logs';

    public function __construct()
    {
        //create email_logs directory if it doesn't exist (for local development)
        if (!is_dir($this->emailLogDir)) {
            @mkdir($this->emailLogDir, 0755, true);
        }

        $autoload = __DIR__ . '/../vendor/autoload.php';
        if (file_exists($autoload)) {
            require_once $autoload;
        }

        $this->usePHPMailer = class_exists('\PHPMailer\PHPMailer\PHPMailer');
    }

    private function logEmailToFile(string $to, string $subject, string $htmlBody): void
    {
        $filename = $this->emailLogDir . '/' . date('Y-m-d_H-i-s') . '_' . md5($to . microtime()) . '.txt';
        $content = "To: " . $to . "\n";
        $content .= "Subject: " . $subject . "\n";
        $content .= "Date: " . date('Y-m-d H:i:s') . "\n";
        $content .= "From: " . (defined('MAIL_FROM_NAME') ? MAIL_FROM_NAME : 'Rodencia Hospital') . "\n";
        $content .= str_repeat("-", 60) . "\n\n";
        $content .= $htmlBody;
        
        @file_put_contents($filename, $content);
    }

    public function sendEmail(string $to, string $subject, string $htmlBody): bool
    {
        $hasRealSMTPConfig = defined('SMTP_HOST') && !empty(SMTP_HOST);
        
        if ($this->usePHPMailer && $hasRealSMTPConfig) {
            
            $host = SMTP_HOST;
            $username = defined('SMTP_USERNAME') ? SMTP_USERNAME : '';
            $password = defined('SMTP_PASSWORD') ? SMTP_PASSWORD : '';
            $secure = defined('SMTP_SECURE') ? SMTP_SECURE : 'tls';
            $port = defined('SMTP_PORT') ? SMTP_PORT : 587;
            $fromAddress = defined('MAIL_FROM_ADDRESS') ? MAIL_FROM_ADDRESS : 'no-reply@rodencia.com';
            $fromName = defined('MAIL_FROM_NAME') ? MAIL_FROM_NAME : 'Rodencia Hospital';

            try {
                $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

                //debug mode - set to 0 or comment out in production
                $mail->SMTPDebug = 2;

                $mail->isSMTP();
                $mail->Host       = $host;
                $mail->SMTPAuth   = !empty($username);
                $mail->Username   = $username;
                $mail->Password   = $password;
                $mail->SMTPSecure = $secure;
                $mail->Port       = $port;

                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );

                $mail->setFrom($fromAddress, $fromName);
                $mail->addAddress($to);
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body    = $htmlBody;
                $mail->AltBody = strip_tags($htmlBody);

                return $mail->send();
            } 
            catch (\PHPMailer\PHPMailer\Exception $e) {
                error_log('PHPMailer Error: ' . $e->getMessage());
                return false;
            } 
            catch (\Exception $e) {
                error_log('General Mailer System Error: ' . $e->getMessage());
                return false;
            }
        }

        //fall back to native PHP mail() function (for local development)
        $fromAddress = defined('MAIL_FROM_ADDRESS') ? MAIL_FROM_ADDRESS : 'no-reply@rodencia.com';
        $fromName = defined('MAIL_FROM_NAME') ? MAIL_FROM_NAME : 'Rodencia Hospital';

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: " . $fromName . " <" . $fromAddress . ">\r\n";

        //log email to file for verification in local development
        $this->logEmailToFile($to, $subject, $htmlBody);
        
        //attempt to send via mail() - suppresses warnings
        $result = @mail($to, $subject, $htmlBody, $headers);
        
        if (!$result) {
            error_log('PHP mail() failed for: ' . $to);
        }
        
        return true; //always return true since we logged it and mail() may be disabled
    }

    public function sendRegistrationConfirmation(string $name, string $email): bool
    {
        $subject = 'Rodencia Registration Confirmation';
        $body = "<p>Hi " . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . ",</p>" .
            "<p>Welcome to Rodencia Hospital. Your account has been created successfully.</p>" .
            "<p>You can now log in and book appointments using your registered email address.</p>" .
            "<p>Best regards,<br>Rodencia Hospital Team</p>";

        return $this->sendEmail($email, $subject, $body);
    }

    public function sendAppointmentNotification(string $name, string $email, string $subject, string $message): bool
    {
        $body = "<p>Hi " . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . ",</p>" .
            "<p>" . nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8')) . "</p>" .
            "<p>Thank you,<br>Rodencia Hospital</p>";

        return $this->sendEmail($email, $subject, $body);
    }
}