<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin.html");
    exit();
}
include("../php/db.php");
if (!isset($_GET['id'])) {
    die("Invalid Service ID");
}
$id = (int)$_GET['id'];
$result = mysqli_query($conn, "SELECT image FROM services WHERE id='$id'");
if (mysqli_num_rows($result) == 0) {
    die("Service not found.");
}
$row = mysqli_fetch_assoc($result);
if (!empty($row['image']) && file_exists("../uploads/" . $row['image'])) {
    unlink("../uploads/" . $row['image']);
}
$sql = "DELETE FROM services WHERE id='$id'";
if (mysqli_query($conn, $sql)) {
    echo "<script>
    alert('Service Deleted Successfully');
    window.location='services.php';
    </script>";
} else {
    echo "<script>
    alert('Failed to Delete Service');
    window.location='services.php';
    </script>";
}
?>