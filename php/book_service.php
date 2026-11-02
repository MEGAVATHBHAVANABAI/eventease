<?php
session_start();
include("db.php");
if($_SERVER["REQUEST_METHOD"]=="POST"){
$customer_name = mysqli_real_escape_string($conn,$_POST['customer_name']);
$phone         = mysqli_real_escape_string($conn,$_POST['phone']);
$email         = mysqli_real_escape_string($conn,$_POST['email']);
$service       = mysqli_real_escape_string($conn,$_POST['service']);
$category      = mysqli_real_escape_string($conn,$_POST['category']);
$price         = mysqli_real_escape_string($conn,$_POST['price']);
$event_date    = mysqli_real_escape_string($conn,$_POST['event_date']);
$guests        = mysqli_real_escape_string($conn,$_POST['guests']);
$status = "Pending";
$sql = "INSERT INTO bookings
(customer_name, phone, email, service, category, price, event_date, guests, status)
VALUES('$customer_name','$phone','$email','$service','$category','$price','$event_date','$guests','$status')";
if(mysqli_query($conn,$sql)){
    $booking_id = mysqli_insert_id($conn);
    header("Location: ../booking_success.php?booking_id=".$booking_id);
    exit();
}else{
    echo "Error : ".mysqli_error($conn);
}
}
?>