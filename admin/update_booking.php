<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin.html");
    exit();
}
include("../php/db.php");
require_once("../php/email.php");
if (!isset($_GET['id']) || !isset($_GET['status'])) {
    die("Invalid Request.");
}
$id = (int)$_GET['id'];
$status = $_GET['status'];
$allowed_statuses = [
    "Accepted",
    "Rejected",
    "Completed"
];
if (!in_array($status, $allowed_statuses)) {
    die("Invalid Status.");
}
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
    $id
);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$booking = mysqli_fetch_assoc($result);
if (!$booking) {
    die("Booking not found.");
}
$update = mysqli_prepare(
    $conn,
    "UPDATE bookings SET status = ? WHERE id = ?"
);
if (!$update) {
    die("Database Error: " . mysqli_error($conn));
}
mysqli_stmt_bind_param(
    $update,
    "si",
    $status,
    $id
);
if (!mysqli_stmt_execute($update)) {
    die("Failed to update booking: " . mysqli_error($conn));
}
$message = "";
if ($status === "Accepted") {
    $email_result = sendBookingAcceptedEmail($booking);
    if ($email_result['success']) {
        $message = "Booking accepted and email sent successfully.";
    } else {
        $message =
            "Booking accepted, but email failed: " .
            $email_result['message'];
    }
}
elseif ($status === "Rejected") {
    $email_result = sendBookingRejectedEmail($booking);
    if ($email_result['success']) {
        $message = "Booking rejected and rejection email sent successfully.";
    } else {
        $message = "REJECTION EMAIL ERROR: " . $email_result['message'];
    }
}
elseif ($status === "Completed") {
    $message = "Booking marked as completed successfully.";
}
echo "<script>";
echo "alert(" . json_encode($message) . ");";
echo "window.location='bookings.php';";
echo "</script>";
exit();
?>