
<?php
session_start();

header('Content-Type: application/json');

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/razorpay_config.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Razorpay\Api\Api;

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request.');
    }

    $booking_id = filter_input(
        INPUT_POST,
        'booking_id',
        FILTER_VALIDATE_INT
    );

    if (!$booking_id || $booking_id < 1) {
        throw new Exception('Invalid booking.');
    }

    $stmt = mysqli_prepare(
        $conn,
        "SELECT id, fullname, email, price
         FROM bookings WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $booking_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $booking = mysqli_fetch_assoc($result);

    if (!$booking) {
        throw new Exception('Booking not found.');
    }

    // Keep the amount calculation on the server.
    $total = round((float)$booking['price'] * 1.18, 2);
    $amount_paise = (int) round($total * 100);

    if ($amount_paise < 100) {
        throw new Exception('Invalid payment amount.');
    }

    $api = new Api(
        RAZORPAY_KEY_ID,
        RAZORPAY_KEY_SECRET
    );

    $order = $api->order->create([
        'receipt' => 'booking_' . $booking_id,
        'amount' => $amount_paise,
        'currency' => 'INR'
    ]);

    // Keep a server-side record for payment verification.
    $_SESSION['razorpay_orders'][$order['id']] = [
        'booking_id' => (int)$booking['id'],
        'amount' => $amount_paise,
        'customer_name' => $booking['fullname'],
        'email' => $booking['email']
    ];

    echo json_encode([
        'success' => true,
        'key' => RAZORPAY_KEY_ID,
        'order_id' => $order['id'],
        'amount' => $amount_paise,
        'name' => $booking['fullname'],
        'email' => $booking['email']
    ]);

} catch (Throwable $e) {
    http_response_code(400);

    // Log technical details privately, not to the browser.
    error_log('Razorpay order error: ' . $e->getMessage());

    echo json_encode([
        'success' => false,
        'message' => 'Unable to create payment order. Check your PHP error log.'
    ]);
}
