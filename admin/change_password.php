<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: admin.html");
    exit();
}
include("../php/db.php");
if(isset($_POST['change'])){
$new=password_hash($_POST['password'],PASSWORD_DEFAULT);
mysqli_query($conn,"UPDATE admins SET password='$new'");
echo "<script>
alert('Password Changed Successfully');
window.location='dashboard.php';
</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Change Password</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
<div class="card p-4 shadow">
<h3>Change Password</h3>
<form method="POST">
<input
type="password"
name="password"
class="form-control"
placeholder="New Password"
required>
<br>
<button
name="change"
class="btn btn-danger">
Update Password
</button>
</form>
</div>
</div>
</body>
</html>