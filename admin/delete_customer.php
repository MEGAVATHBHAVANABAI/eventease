<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: admin.html");
    exit();
}
include("../php/db.php");
$id=$_GET['id'];
mysqli_query($conn,"DELETE FROM users WHERE id='$id'");
echo "<script>
alert('Customer Deleted Successfully');
window.location='customers.php';
</script>";
?>