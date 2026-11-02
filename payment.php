
<?php
require_once __DIR__ . '/php/db.php';

if (!isset($_GET['booking_id'])) {
    die("Invalid Booking");
}

$booking_id = filter_var($_GET['booking_id'], FILTER_VALIDATE_INT);

if (!$booking_id || $booking_id < 1) {
    die("Invalid Booking");
}

$stmt = mysqli_prepare($conn, "SELECT * FROM bookings WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $booking_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$booking = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$booking) {
    die("Booking not found.");
}

$price = (float)$booking['price'];
$gst = round($price * 0.18, 2);
$total = round($price + $gst, 2);
$advance = round($total * 0.50, 2);
$remaining = round($total - $advance, 2);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Payment | EventEase</title>
<link rel="stylesheet" href="css/payment.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
<div class="container mt-5 mb-5">
<div class="row g-4">
<div class="col-lg-8">
<div class="payment-box">
<h2 class="mb-3">Complete Your Payment</h2>
<p class="text-secondary">Secure checkout powered by Razorpay</p>
<div class="alert alert-success">
<h4>50% Advance Payment</h4>
<h2>₹<?= number_format($advance, 2) ?></h2>
<p class="mb-0">Pay 50% now to confirm your booking.</p>
</div>
<div class="alert alert-info">
Choose your available payment method in the Razorpay checkout window.
<br>
<small>Razorpay Test Mode: No real money will be deducted.</small>
</div>
<button type="button" id="payNow" class="btn btn-success btn-lg w-100">
Pay ₹<?= number_format($advance, 2) ?> Advance with Razorpay
</button>
<p id="paymentMessage" class="mt-3" role="status" aria-live="polite"></p>
</div>
</div>
<div class="col-lg-4">
<div class="card shadow p-4">
<h3>Booking Summary</h3>
<hr>
<p>Service: <strong><?= htmlspecialchars($booking['service']) ?></strong></p>
<p>Category: <strong><?= htmlspecialchars($booking['category'] ?? 'N/A') ?></strong></p>
<p>Booking Fee: <strong>₹<?= number_format($price, 2) ?></strong></p>
<p>GST (18%): <strong>₹<?= number_format($gst, 2) ?></strong></p>
<hr>
<p>Total Order Amount: <strong>₹<?= number_format($total, 2) ?></strong></p>
<p class="text-success">Advance Payment (50%): <strong>₹<?= number_format($advance, 2) ?></strong></p>
<p>Remaining Balance: <strong>₹<?= number_format($remaining, 2) ?></strong></p>
</div>
</div>
</div>
</div>
<script>
const bookingId = <?= json_encode((int)$booking_id) ?>;
const payButton = document.getElementById('payNow');
const message = document.getElementById('paymentMessage');

payButton.addEventListener('click', async function () {
    payButton.disabled = true;
    message.className = 'mt-3 text-secondary';
    message.textContent = 'Preparing secure checkout...';

    try {
        const formData = new FormData();
        formData.append('booking_id', bookingId);

        const orderResponse = await fetch('php/create_razorpay_order.php', {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        });

        const order = await orderResponse.json();

        if (!orderResponse.ok || !order.success) {
            throw new Error(order.message || 'Unable to create payment order.');
        }

        const options = {
            key: order.key,
            amount: order.amount,
            currency: 'INR',
            name: 'BY EVENT BOOKING',
            description: '50% Advance Booking Payment',
            order_id: order.order_id,
            prefill: {
                name: order.name || '',
                email: order.email || ''
            },
            theme: {
                color: '#198754'
            },
            handler: async function (response) {
                message.className = 'mt-3 text-secondary';
                message.textContent = 'Verifying your payment...';

                try {
                    const verifyResponse = await fetch('php/verify_razorpay_payment.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify(response)
                    });

                    const verification = await verifyResponse.json();

                    if (!verifyResponse.ok || !verification.success) {
                        throw new Error(verification.message || 'Payment verification failed.');
                    }

                    message.className = 'mt-3 text-success';
                    message.textContent = 'Advance payment verified successfully!';

                    window.location.href = 'booking_success.php?booking_id=' +
                        encodeURIComponent(verification.booking_id);

                } catch (error) {
                    message.className = 'mt-3 text-danger';
                    message.textContent = error.message;
                    payButton.disabled = false;
                }
            },
            modal: {
                ondismiss: function () {
                    message.className = 'mt-3 text-secondary';
                    message.textContent = 'Checkout closed. Check payment status before trying again.';
                    payButton.disabled = false;
                }
            }
        };

        const checkout = new Razorpay(options);

        checkout.on('payment.failed', function (response) {
            message.className = 'mt-3 text-danger';
            message.textContent = response.error.description || 'Payment failed. Please try again.';
            payButton.disabled = false;
        });

        checkout.open();

    } catch (error) {
        message.className = 'mt-3 text-danger';
        message.textContent = error.message;
        payButton.disabled = false;
    }
});
</script>
</body>
</html>
