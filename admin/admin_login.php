<?php
session_start();
include("../php/db.php");
if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $sql = "SELECT * FROM admins WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if ($password == $row['password']) { 
            $_SESSION['admin'] = $row['email'];
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>alert('Incorrect Password');window.location='admin.html';</script>";
        }
    } else {
        echo "<script>alert('Admin Not Found');window.location='admin.html';</script>";
    }
}
?>