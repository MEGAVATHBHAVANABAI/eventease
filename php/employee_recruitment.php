<?php

include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullName = $_POST['fullName'] ?? '';
    $skills = $_POST['skills'] ?? '';
    $whatsapp = $_POST['whatsapp'] ?? '';
    $email = $_POST['email'] ?? '';
    $address = $_POST['address'] ?? '';

    $status = "Pending";

    $sql = "INSERT INTO employee_recruitment
            (fullname, skills, whatsapp, email, address)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        die("PREPARE ERROR: " . mysqli_error($conn));
    }

    if (!mysqli_stmt_bind_param(
        $stmt,
        "sssss",
        $fullName,
        $skills,
        $whatsapp,
        $email,
        $address,
    )) {
        die("BIND ERROR: " . mysqli_stmt_error($stmt));
    }

    if (mysqli_stmt_execute($stmt)) {

        header("Location: ../employee_recruitment.html?success=1");
        exit();

    } else {

        die("EXECUTE ERROR: " . mysqli_stmt_error($stmt));
    }

    mysqli_stmt_close($stmt);
}
?>