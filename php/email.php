<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require __DIR__ . '/../vendor/autoload.php';
function configureMailer($mail)
{
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'ammakutti4444@gmail.com';
    $mail->Password   = 'tkunayyfpikexocl';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->setFrom(
        'ammakutti4444@gmail.com',
        'EventEase'
    );
}
function sendBookingAcceptedEmail($booking)
{
    $mail = new PHPMailer(true);
    try {
        configureMailer($mail);
        if (empty($booking['email'])) {
            return [
                'success' => false,
                'message' => 'Customer email address is missing.'
            ];
        }
        $mail->addAddress(
            $booking['email'],
            $booking['fullname']
        );

        $mail->isHTML(true);

        $mail->Subject = 'Your EventEase Booking Has Been Accepted';

        $mail->Body = "
        <div style='font-family:Arial,sans-serif;
                    max-width:600px;
                    margin:auto;
                    padding:20px;
                    border:1px solid #ddd;
                    border-radius:10px;
                    background:#fffdf9;'>

            <h2 style='text-align:center;'>
                Booking Accepted
            </h2>

            <p>
                Hello <strong>" .
                htmlspecialchars($booking['fullname']) .
                "</strong>,
            </p>

            <p>
                Your EventEase booking has been
                <strong>successfully accepted</strong>.
            </p>

            <table border='1'
                   cellpadding='10'
                   cellspacing='0'
                   width='100%'
                   style='border-collapse:collapse;'>

                <tr>
                    <td><strong>Booking ID</strong></td>
                    <td>#{$booking['id']}</td>
                </tr>

                <tr>
                    <td><strong>Service</strong></td>
                    <td>" .
                    htmlspecialchars($booking['service']) .
                    "</td>
                </tr>

                <tr>
                    <td><strong>Category</strong></td>
                    <td>" .
                    htmlspecialchars($booking['category']) .
                    "</td>
                </tr>

                <tr>
                    <td><strong>Event Date</strong></td>
                    <td>" .
                    htmlspecialchars($booking['event_date']) .
                    "</td>
                </tr>

                <tr>
                    <td><strong>Event Time</strong></td>
                    <td>" .
                    htmlspecialchars($booking['event_time']) .
                    "</td>
                </tr>

            </table>

            <p>
                For more queries, contact our office:
                <strong>8247267307</strong>
            </p>

            <p>
                Thank you for choosing EventEase.
            </p>

            <p>
                <strong>EventEase Team ❤️</strong>
            </p>

        </div>
        ";

        $mail->AltBody =
            "Hello {$booking['fullname']},\n\n" .
            "Your EventEase booking has been successfully accepted.\n\n" .
            "Booking ID: #{$booking['id']}\n" .
            "Service: {$booking['service']}\n" .
            "Category: {$booking['category']}\n" .
            "Event Date: {$booking['event_date']}\n" .
            "Event Time: {$booking['event_time']}\n\n" .
            "For more queries, contact our office: 8247267307\n\n" .
            "Thank you for choosing EventEase.\n\n" .
            "EventEase Team";

        $mail->send();

        return [
            'success' => true,
            'message' => 'Accepted email sent successfully.'
        ];

    } catch (Exception $e) {

        return [
            'success' => false,
            'message' => $mail->ErrorInfo
        ];
    }
}
function sendBookingRejectedEmail($booking)
{
    $mail = new PHPMailer(true);

    try {

        configureMailer($mail);

        if (empty($booking['email'])) {
            return [
                'success' => false,
                'message' => 'Customer email address is missing.'
            ];
        }

        $mail->addAddress(
            $booking['email'],
            $booking['fullname']
        );

        $mail->isHTML(true);

        $mail->Subject = 'Update Regarding Your EventEase Booking';

        $mail->Body = "
        <div style='font-family:Arial,sans-serif;
                    max-width:600px;
                    margin:auto;
                    padding:25px;
                    border:1px solid #ddd;
                    border-radius:10px;
                    background:#fffdf9;'>

            <h2 style='text-align:center;'>
                Booking Update
            </h2>

            <p>
                Hello <strong>" .
                htmlspecialchars($booking['fullname']) .
                "</strong>,
            </p>

            <p>
                Thank you for choosing EventEase.
            </p>

            <p>
                We regret to inform you that your booking has
                <strong>not been accepted</strong> at this time.
            </p>

            <table border='1'
                   cellpadding='10'
                   cellspacing='0'
                   width='100%'
                   style='border-collapse:collapse;'>

                <tr>
                    <td><strong>Booking ID</strong></td>
                    <td>#{$booking['id']}</td>
                </tr>

                <tr>
                    <td><strong>Service</strong></td>
                    <td>" .
                    htmlspecialchars($booking['service']) .
                    "</td>
                </tr>

                <tr>
                    <td><strong>Category</strong></td>
                    <td>" .
                    htmlspecialchars($booking['category']) .
                    "</td>
                </tr>

                <tr>
                    <td><strong>Event Date</strong></td>
                    <td>" .
                    htmlspecialchars($booking['event_date']) .
                    "</td>
                </tr>

                <tr>
                    <td><strong>Event Time</strong></td>
                    <td>" .
                    htmlspecialchars($booking['event_time']) .
                    "</td>
                </tr>

            </table>

            <p>
                If you have any questions, please contact our office:
                <strong>8247267307</strong>
            </p>

            <p>
                Thank you for your understanding.
            </p>

            <p>
                <strong>EventEase Team ❤️</strong>
            </p>

        </div>
        ";

        $mail->AltBody =
            "Hello {$booking['fullname']},\n\n" .
            "Thank you for choosing EventEase.\n\n" .
            "We regret to inform you that your booking has not been accepted at this time.\n\n" .
            "Booking ID: #{$booking['id']}\n" .
            "Service: {$booking['service']}\n" .
            "Category: {$booking['category']}\n" .
            "Event Date: {$booking['event_date']}\n" .
            "Event Time: {$booking['event_time']}\n\n" .
            "For more queries, contact our office: 8247267307\n\n" .
            "Thank you for your understanding.\n\n" .
            "EventEase Team";

        $mail->send();

        return [
            'success' => true,
            'message' => 'Rejection email sent successfully.'
        ];

    } catch (Exception $e) {

        return [
            'success' => false,
            'message' => $mail->ErrorInfo
        ];
    }
}

?>