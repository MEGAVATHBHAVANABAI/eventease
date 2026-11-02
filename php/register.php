<?php
include "db.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    if ($password !== $confirm_password) {
        header("Location: ../index.html?error=password");
        exit();
    }
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        header("Location: ../index.html?error=email_exists");
        exit();
    }
    $hashed_password =
        password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users
            (fullname,mobile, email, password)
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssss",
        $name,
        $mobile,
        $email,
        $hashed_password
    );
    if ($stmt->execute()) {
        header("Location: ../index.html?registered=success");
        exit();
    } else {
        header("Location: ../index.html?error=registration");
        exit();
    }
}
?>