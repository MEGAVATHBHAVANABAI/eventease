<?php
session_start();
include("db.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $stmt = mysqli_prepare(
        $conn,
        "SELECT id, fullname, email, password
         FROM users
         WHERE email = ?"
    );
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) == 0) {
        header("Location: ../index.html?error=incorrect");
        exit();
    }
    $row = mysqli_fetch_assoc($result);
    if (password_verify($password, $row['password'])) {
        $_SESSION['user'] = $row['email'];
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['fullname'] = $row['fullname'];
        if (isset($_POST['redirect']) && $_POST['redirect'] == 'my_bookings') {
            header("Location: ../my_bookings.php");
            exit();

        }
        header("Location: ../index.html");
        exit();
    } else {
        header("Location: ../index.html?error=incorrect");
        exit();
    }
}
?>