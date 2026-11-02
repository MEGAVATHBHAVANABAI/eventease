
<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/razorpay_config.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Razorpay\Api\Api;

$transactionStarted = false;

function sendResponse($success, $message, $extra = []) {
    http_response_code($success ? 200 : 400);
    echo json_encode(array_merge([
        'success' => $success,
        'message' => $message
    ], $extra));
    exit;
}

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request.');
    }

    $input = json_decode(file_get_contents('php://input'), true);

    if (!is_array($input)) {
        throw new Exception('Invalid payment response.');
    }

    $order_id = $input['razorpay_order_id'] ?? '';
    $payment_id = $input['razorpay_payment_id'] ?? '';
    $signature = $input['razorpay_signature'] ?? '';

    if (!$order_id || !$payment_id || !$signature) {
        throw new Exception('Missing payment details.');
    }

    $saved = $_SESSION['razorpay_orders'][$order_id] ?? null;

    if (!$saved) {
        throw new Exception('Payment order not found in session.');
    }

    $expected = hash_hmac(
        'sha256',
        $order_id . '|' . $payment_id,
        RAZORPAY_KEY_SECRET
    );

    if (!hash_equals($expected, $signature)) {
        throw new Exception('Invalid payment signature.');
    }

    $api = new Api(
        RAZORPAY_KEY_ID,
        RAZORPAY_KEY_SECRET
    );

    $payment = $api->payment->fetch($payment_id);

    if (
        $payment['order_id'] !== $order_id ||
        (int)$payment['amount'] !== (int)$saved['amount'] ||
        $payment['currency'] !== 'INR'
    ) {
        throw new Exception('Payment details do not match.');
    }

    if ($payment['status'] !== 'captured') {
        throw new Exception('Payment is not captured yet.');
    }

    $booking_id = (int)$saved['booking_id'];
    $customer_name = $saved['customer_name'];
    $amount = (float)$saved['amount'] / 100;
    $payment_method = 'Razorpay';
    $payment_status = 'Success';

    mysqli_begin_transaction($conn);
    $transactionStarted = true;

    // Check whether this Razorpay transaction was already recorded.
    $stmt = mysqli_prepare(
        $conn,
        "SELECT booking_id FROM payments
         WHERE transaction_id = ?
         LIMIT 1"
    );

    mysqli_stmt_bind_param($stmt, 's', $payment_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $existing = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($existing) {
        if ((int)$existing['booking_id'] !== $booking_id) {
            throw new Exception('Transaction belongs to another booking.');
        }

        mysqli_commit($conn);
        $transactionStarted = false;

        unset($_SESSION['razorpay_orders'][$order_id]);

        sendResponse(true, 'Payment already recorded.', [
            'booking_id' => $booking_id
        ]);
    }

    // Insert using your existing payments table columns.
    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO payments
        (booking_id, customer_name, payment_method,
         transaction_id, amount, payment_status)
        VALUES (?, ?, ?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        'isssds',
        $booking_id,
        $customer_name,
        $payment_method,
        $payment_id,
        $amount,
        $payment_status
    );

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception('Unable to save payment.');
    }

    mysqli_stmt_close($stmt);

    mysqli_commit($conn);
    $transactionStarted = false;

    unset($_SESSION['razorpay_orders'][$order_id]);

    sendResponse(true, 'Payment verified successfully.', [
        'booking_id' => $booking_id
    ]);

} catch (Throwable $e) {

    if ($transactionStarted) {
        mysqli_rollback($conn);
    }

    error_log('Razorpay verification error: ' . $e->getMessage());

    sendResponse(false,
        'Payment verification failed. Check your payment status before retrying.'
    );
}
