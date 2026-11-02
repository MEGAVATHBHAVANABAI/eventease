<?php
session_start();
include("db.php");
if (isset($_POST['pay'])) {
    $booking_id = $_POST['booking_id'];
    $customer_name = $_POST['customer_name'];
    $payment_method = $_POST['payment_method'];
    $amount = $_POST['amount'];
    $sql = "INSERT INTO payments
            (booking_id, customer_name, payment_method, amount, payment_status)
            VALUES
            ('$booking_id', '$customer_name', '$payment_method', '$amount', 'Paid')";
    if (mysqli_query($conn, $sql)) {
        $update = "UPDATE bookings 
                   SET payment_status = 'Paid',
                       advance_amount = '$amount'
                   WHERE id = '$booking_id'";
        mysqli_query($conn, $update);
        header("Location: ../booking_success.php?booking_id=" . $booking_id);
        exit();
    } else {
        echo "Payment Failed<br>";
        echo mysqli_error($conn);
    }
} else {
    echo "Invalid Request";
}
?>