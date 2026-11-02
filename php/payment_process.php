<?php
include("db.php");
$customer_name=$_POST['customer_name'];
$booking_id = $_POST['booking_id'];
$amount = $_POST['amount'];
$payment_method = $_POST['payment_method'];
$transaction_id = "EVT".rand(100000,999999);
mysqli_query($conn,
"INSERT INTO payments
(booking_id,customer_name,amount,payment_method,payment_status,transaction_id)
VALUES
('$booking_id','$customer_name','$amount','$payment_method','Success','$transaction_id')");
mysqli_query($conn,
"UPDATE bookings
SET status='Confirmed'
WHERE id='$booking_id'");
header("Location: ../booking_success.php?booking_id=".$booking_id);
exit();
?>