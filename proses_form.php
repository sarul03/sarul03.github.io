<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $visitor_name = "";
    $visitor_email = "";
    $email_title = "";
    $visitor_message = "";

    if (isset($_POST['visitor_name'])) {
        $visitor_name = filter_var($_POST['visitor_name'], FILTER_SANITIZE_STRING);
    }

    if (isset($_POST['visitor_email'])) {
        $visitor_email = str_replace(array("\r", "\n", "%0a", "%0d"), '', $_POST['visitor_email']);
        $visitor_email = filter_var($visitor_email, FILTER_VALIDATE_EMAIL);
    }

    if (isset($_POST['email_title'])) {
        $email_title = filter_var($_POST['email_title'], FILTER_SANITIZE_STRING);
    }

    if (isset($_POST['visitor_message'])) {
        $visitor_message = htmlspecialchars($_POST['visitor_message']);
    }

    // Set alamat email penerima
    $recipient = "kukertadesasiabu@gmail.com";

    // Konfigurasi SMTP
    $smtp_host = 'smtp.gmail.com';
    $smtp_port = 587;
    $smtp_username = 'your@gmail.com'; // Ganti dengan alamat email Gmail Anda
    $smtp_password = 'your_password'; // Ganti dengan kata sandi email Gmail Anda

    $headers = 'MIME-Version: 1.0' . "\r\n"
        . 'Content-type: text/html; charset=utf-8' . "\r\n"
        . 'From: ' . $visitor_email . "\r\n";

    // Konfigurasi pengaturan SMTP
    ini_set('SMTP', $smtp_host);
    ini_set('smtp_port', $smtp_port);
    ini_set('sendmail_from', $smtp_username);

    // Pengaturan autentikasi SMTP
    $smtp_config = array(
        'auth' => true,
        'username' => $smtp_username,
        'password' => $smtp_password
    );

    // Load PEAR Mail package
    require_once "Mail.php";

    // Create the mail object using the Mail::factory method
    $mail = Mail::factory('smtp', $smtp_config);

    // Set alamat email pengirim
    $headers .= 'From: ' . $smtp_username . "\r\n";

    // Pengiriman email
    $mail_status = $mail->send($recipient, array('Subject' => $email_title), $visitor_message, $headers);

    if ($mail_status) {
        echo "<p>Thank you for contacting us, $visitor_name. You will get a reply within 24 hours.</p>";
    } else {
        echo '<p>We are sorry but the email did not go through.</p>';
    }
} else {
    echo '<p>Something went wrong</p>';
}
?>
