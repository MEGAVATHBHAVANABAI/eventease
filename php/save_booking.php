<?php
session_start();
include("db.php");
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    die("Invalid Request");
}
$fullname = trim($_POST['customer_name'] ?? '');
$phonenumber = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$service = trim($_POST['service_name'] ?? '');
$category = trim($_POST['category_name'] ?? '');
$price = trim($_POST['price'] ?? '');
$address = trim($_POST['address'] ?? '');
$event_date = trim($_POST['event_date'] ?? '');
$event_time = trim($_POST['event_time'] ?? '');
$special_request = trim($_POST['special_request'] ?? '');
$status = "Pending";
if (
    empty($fullname) ||
    empty($phonenumber) ||
    empty($email) ||
    empty($service) ||
    empty($category) ||
    empty($price) ||
    empty($address) ||
    empty($event_date) ||
    empty($event_time)
) {
    die("Please fill all required fields.");
}
$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO bookings
    (
        service,
        category,
        price,
        fullname,
        phonenumber,
        email,
        address,
        event_date,
        event_time,
        special_request,
        status
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
);
if (!$stmt) {
    die("Database Error: " . mysqli_error($conn));
} 
mysqli_stmt_bind_param(
    $stmt,
    "ssdssssssss",
    $service,
    $category,
    $price,
    $fullname,
    $phonenumber,
    $email,
    $address,
    $event_date,
    $event_time,
    $special_request,
    $status
);
if (mysqli_stmt_execute($stmt)) {
    $booking_id = mysqli_insert_id($conn);
    header(
        "Location: ../booking_success.php?booking_id=" . $booking_id
    );
    exit();
} else {
    echo "Booking Failed<br>";
    echo "Database Error: " . mysqli_stmt_error($stmt);
}
?>