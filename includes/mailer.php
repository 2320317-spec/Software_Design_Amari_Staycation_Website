<?php
// Point this to where you saved the PHPMailer folder
require __DIR__ . '/../PHPMailer/Exception.php';
require __DIR__ . '/../PHPMailer/PHPMailer.php';
require __DIR__ . '/../PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendAmariEmail($toEmail, $guestName, $subject, $htmlBody) {
    $mail = new PHPMailer(true);

    try {
        // --- Server Settings ---
        $mail->isSMTP();
        $mail->SMTPDebug  = 0; // debugger - change 0 to 2 if going to debug
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        
        // YOUR AMARI CREDENTIALS (CORRECTED)
        $mail->Username   = 'amaristaycation08@gmail.com'; 
        $mail->Password   = 'nbbohghpmvtcoybr'; 
        
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // --- Sender & Recipient ---
        $mail->setFrom('amaristaycation08@gmail.com', 'Amari Alabang');
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