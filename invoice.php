<?php
require_once __DIR__ . "/php/db.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Invoice");
}

$id = (int)$_GET['id'];

// Fetch booking
$stmt = $conn->prepare("SELECT * FROM bookings WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();

if (!$row) {
    die("Invoice not found");
}

// Calculate order amount
$booking_amount = (float)$row['price'];
$gst = round($booking_amount * 0.18, 2);
$total = round($booking_amount + $gst, 2);

// Fetch actual successful payments
$paymentStmt = $conn->prepare("
    SELECT COALESCE(SUM(amount), 0) AS paid_amount
    FROM payments
    WHERE booking_id = ? AND payment_status = 'Success'
");
$paymentStmt->bind_param("i", $id);
$paymentStmt->execute();
$paymentResult = $paymentStmt->get_result();
$paymentRow = $paymentResult->fetch_assoc();
$paymentStmt->close();

$paid_amount = (float)$paymentRow['paid_amount'];
$remaining_amount = max(0, round($total - $paid_amount, 2));

if ($paid_amount <= 0) {
    $payment_status = "Payment Pending";
} elseif ($remaining_amount > 0) {
    $payment_status = "Advance Paid";
} else {
    $payment_status = "Fully Paid";
}

$booking_status = !empty($row['status']) ? $row['status'] : 'Pending';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Invoice | EventEase</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/invoice.css">
</head>
<body>

<div class="container mt-5 mb-5">
<div class="card shadow p-5">

<div class="invoice-logo">
<img src="images/logoby.jpg" alt="BY Event Booking Logo">
</div>

<h2 class="text-center invoice-title">🎉 BY Event Booking</h2>
<h5 class="text-center mb-4">Booking Invoice</h5>
<hr>

<p><strong>Invoice No:</strong> INV<?php echo htmlspecialchars($row['id']); ?></p>
<p><strong>Customer:</strong> <?php echo htmlspecialchars($row['fullname']); ?></p>
<p><strong>Mobile:</strong> <?php echo htmlspecialchars($row['mobile'] ?? ''); ?></p>
<p><strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
<p><strong>Service:</strong> <?php echo htmlspecialchars($row['service']); ?></p>
<p><strong>Category:</strong> <?php echo htmlspecialchars($row['category'] ?? ''); ?></p>
<p><strong>Event Date:</strong> <?php echo htmlspecialchars($row['event_date']); ?></p>
<p><strong>Event Time:</strong> <?php echo htmlspecialchars($row['event_time']); ?></p>

<hr>

<h5>Order Amount Details</h5>

<p><strong>Booking Amount:</strong> ₹<?php echo number_format($booking_amount, 2); ?></p>
<p><strong>GST (18%):</strong> ₹<?php echo number_format($gst, 2); ?></p>

<h4 class="total-amount">
Total Order Amount: ₹<?php echo number_format($total, 2); ?>
</h4>

<hr>

<h5>Payment Details</h5>

<p>
<strong>Paid Amount:</strong>
<span class="text-success fw-bold">
₹<?php echo number_format($paid_amount, 2); ?>
</span>
</p>

<p>
<strong>Remaining Balance:</strong>
<span class="text-danger fw-bold">
₹<?php echo number_format($remaining_amount, 2); ?>
</span>
</p>

<p>
<strong>Payment Status:</strong>
<?php if ($payment_status === "Fully Paid"): ?>
<span class="badge bg-success">Fully Paid</span>
<?php elseif ($payment_status === "Advance Paid"): ?>
<span class="badge bg-warning text-dark">Advance Paid</span>
<?php else: ?>
<span class="badge bg-danger">Payment Pending</span>
<?php endif; ?>
</p>

<hr>

