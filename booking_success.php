<?php
include("php/db.php");
if (!isset($_GET['booking_id'])) {
    die("Invalid booking.");
}
$booking_id = (int)$_GET['booking_id'];
$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM bookings WHERE id = ?"
);
if (!$stmt) {
    die("Database Error: " . mysqli_error($conn));
}
mysqli_stmt_bind_param(
    $stmt,
    "i",
    $booking_id
);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$booking = mysqli_fetch_assoc($result);
if (!$booking) {
    die("Booking not found.");
}
$total_price = (float)$booking['price'];
$advance_amount = $total_price * 0.50;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport"
      content="width=device-width, initial-scale=1.0">
<title>Booking Status | EventEase</title>
<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-body text-center p-5">
        <?php if ($booking['status'] === 'Pending'): ?>
            <h1 class="text-warning">
                Booking Request Submitted
            </h1>
            <p class="lead mt-3">
                Thank you,
                <?php echo htmlspecialchars($booking['fullname']); ?>!
            </p>
            <p>
                Your booking request has been received successfully.
            </p>
            <div class="alert alert-warning mt-4">
                <strong>
                    Status: Pending
                </strong>
                <br>
                Your booking will be confirmed after admin approval.
            </div>
            <a
                href="my_bookings.php"
                class="btn btn-primary mt-3">
                ← My Bookings
            </a>
        <?php elseif ($booking['status'] === 'Accepted'): ?>
            <h1 class="text-success">
                🎉 Booking Accepted!
            </h1>
            <p class="lead mt-3">
                Congratulations,
                <?php echo htmlspecialchars($booking['fullname']); ?>!
            </p>

            <p>
                Your booking has been accepted by EventEase.
            </p>
            <div class="alert alert-success mt-4">
                <p class="mb-2">
                    <strong>
                        Booking ID:
                    </strong>
                    #<?php echo $booking['id']; ?>
                </p>
                <p class="mb-2">

                    <strong>
                        Service:
                    </strong>
                    <?php echo htmlspecialchars($booking['service']); ?>
                </p>
                <p class="mb-2">

                    <strong>
                        Total Amount:
                    </strong>
                    ₹<?php echo number_format($total_price, 2); ?>
                </p>
                <p class="mb-0">
                    <strong>
                        50% Advance:
                    </strong>
                    ₹<?php echo number_format($advance_amount, 2); ?>
                </p>
            </div>
            <p class="mt-4">
                Pay 50% advance to complete your booking.
            </p>
            <a
                href="payment.php?booking_id=<?php echo (int)$booking['id']; ?>"
                class="btn btn-success btn-lg">
                💳 Move Forward → Pay 50%
            </a>
            <br>
            <a
                href="my_bookings.php"
                class="btn btn-secondary mt-3">
                ← My Bookings
            </a>
        <?php elseif ($booking['status'] === 'Rejected'): ?>
            <h1 class="text-danger">
                Booking Rejected
            </h1>
            <p class="lead mt-3">
                Sorry,
                <?php echo htmlspecialchars($booking['fullname']); ?>.
            </p>
            <div class="alert alert-danger mt-4">
                <strong>
                    Status: Rejected
                </strong>
                <br>
                Your booking request was not accepted by the admin.
            </div>
            <a
                href="my_bookings.php"
                class="btn btn-primary mt-3">
                ← My Bookings
            </a>
        <?php else: ?>
      <div class="alert alert-info mt-4">
                <strong>
                    Booking Status:
                </strong>
                <h2>Your Booking is confirmed</h2>
            </div>
            <a
                href="index.html"
                class="btn btn-primary mt-3">
                Back To Home
            </a>
        <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>