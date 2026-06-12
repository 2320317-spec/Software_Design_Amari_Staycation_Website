<?php
// Point this to where you saved the PHPMailer folder
require __DIR__ . '/../PHPMailer/Exception.php';
require __DIR__ . '/../PHPMailer/PHPMailer.php';
require __DIR__ . '/../PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendAmariEmail($toEmail, $guestName, $subject, $htmlBody) {
    // Load mail credentials from includes/secrets.php (gitignored, not in repo)
    $secrets_file = __DIR__ . '/secrets.php';
    $secrets = file_exists($secrets_file) ? require $secrets_file : [];
    $mailCfg = isset($secrets['mail']) ? $secrets['mail'] : ['user' => '', 'pass' => '', 'from_name' => 'Amari Alabang'];

    // No credentials configured → fail gracefully instead of erroring
    if (empty($mailCfg['user']) || empty($mailCfg['pass'])) {
        return false;
    }

    $mail = new PHPMailer(true);

    try {
        // --- Server Settings ---
        $mail->isSMTP();
        $mail->SMTPDebug  = 0; // debugger - change 0 to 2 if going to debug
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;

        // Credentials pulled from secrets.php
        $mail->Username   = $mailCfg['user'];
        $mail->Password   = $mailCfg['pass'];

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // --- Sender & Recipient ---
        $mail->setFrom($mailCfg['user'], isset($mailCfg['from_name']) ? $mailCfg['from_name'] : 'Amari Alabang');
        $mail->addAddress($toEmail, $guestName);

        // --- Email Content ---
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $htmlBody;

        $mail->send();
        return true;
    } catch (Exception $e) {
        // --- CHANGE THIS BACK TO SILENT FAIL ---
        return false; 
    }
}
?>