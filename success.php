<?php
include("php/db.php");
if (!isset($_GET['booking_id'])) {
    die("Invalid booking ID.");
}
$booking_id = (int) $_GET['booking_id'];
$sql = "SELECT * FROM bookings WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    die("Database error: " . mysqli_error($conn));
}
mysqli_stmt_bind_param($stmt, "i", $booking_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$booking = mysqli_fetch_assoc($result);
if (!$booking) {
    die("Booking not found.");
}
$payment_status = $booking['payment_status'] ?? 'Pending';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Booking Successful | EventEase</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/success.css">
</head>
<body>
<div class="success-box">
    <div class="checkmark">
        ✔
    </div>
    <h1>Booking Confirmed!</h1>
    <p>
        Thank you for choosing EventEase.
    </p>
    <div class="booking-card">
        <h4>Your Booking Details</h4>
        <hr>
        <p>
            <strong>Booking ID :</strong>
            <span>#<?php echo $booking['id']; ?></span>
        </p>
        <p>
            <strong>Customer :</strong>
            <?php echo htmlspecialchars($booking['fullname']); ?>
        </p>
        <p>
            <strong>Service :</strong>
            <?php echo htmlspecialchars($booking['service']); ?>
        </p>

        <p>
            <strong>Category :</strong>
            <?php echo htmlspecialchars($booking['category']); ?>
        </p>
        <p>
            <strong>Event Date :</strong>
            <?php echo htmlspecialchars($booking['event_date']); ?>
        </p>
        <p>
            <strong>Status :</strong>
            <?php if ($booking['status'] === 'Accepted'): ?>
                <span class="text-success">
                    Accepted
                </span>
            <?php elseif ($booking['status'] === 'Rejected'): ?>
                <span class="text-danger">
                    Rejected
                </span>
            <?php elseif ($booking['status'] === 'Completed'): ?>
                <span class="text-success">
                    Completed
                </span>
            <?php else: ?>
                <span class="text-warning">
                    Pending
                </span>
            <?php endif; ?>
        </p>
        <p>
            <strong>Payment :</strong>
            <?php if ($payment_status === 'Paid'): ?>
                <span class="text-success">
                    Paid Successfully
                </span>
            <?php else: ?>
                <span class="text-warning">
                    Payment Pending
                </span>
            <?php endif; ?>
        </p>
        <p>
            <strong>Support :</strong>
            +91 9876543210
        </p>
    </div>
    <div class="buttons">
        <?php if ($booking['status'] === 'Accepted' && $payment_status !== 'Paid'): ?>
            <a href="payment.php?booking_id=<?php echo $booking['id']; ?>"
               class="btn btn-success">
                Move Forward →
            </a>
        <?php endif; ?>
        <?php if ($payment_status === 'Paid'): ?>
            <a href="invoice.html?booking_id=<?php echo $booking['id']; ?>"
               class="btn btn-primary">
                Download Invoice
            </a>
            <a href="index.html"
               class="btn btn-success">
                🏠 Home
            </a>
        <?php endif; ?>
        <?php if ($booking['status'] === 'Pending'): ?>
            <a href="index.html"
               class="btn btn-success">
                🏠 Home
            </a>
        <?php endif; ?>
        <?php if ($booking['status'] === 'Rejected'): ?>
            <a href="index.html"
               class="btn btn-success">
                🏠 Home
            </a>
        <?php endif; ?>
    </div>
</div>
<script src="js/success.js"></script>
</body>
</html>