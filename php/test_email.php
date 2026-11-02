<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/../vendor/autoload.php';
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'YOUR_GMAIL@gmail.com';
    $mail->Password   = 'YOUR_APP_PASSWORD';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->setFrom(
        'YOUR_GMAIL@gmail.com',
        'EventEase'
    );
    $mail->addAddress(
        'YOUR_TEST_EMAIL@gmail.com',
        'Test Customer'
    );
    $mail->isHTML(true);
    $mail->Subject = 'EventEase Email Test';
    $mail->Body = '
        <h2>EventEase Email Test</h2>
        <p>Hello!</p>
        <p>
            This is a test email from your
            <strong>EventEase</strong> website.
        </p>
        <p>
            If you received this email,
            PHPMailer SMTP is working correctly.
        </p>
        <br>
        <p>
            <strong>EventEase Team</strong>
        </p>
    ';
    $mail->AltBody =
        "Hello!\n\n" .
        "This is a test email from EventEase.\n\n" .
        "PHPMailer SMTP is working correctly.\n\n" .
        "EventEase Team";
    $mail->send();
    echo "
        <h2 style='color:green;'>
            Email sent successfully!
        </h2>
        <p>
            Check the recipient's inbox and spam folder.
        </p>
    ";
} catch (Exception $e) {
    echo "
        <h2 style='color:red;'>
            Email could not be sent.
        </h2>
        <p>
            Error:
            " . htmlspecialchars($mail->ErrorInfo) . "
        </p>
    ";
}
?>