<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: admin.html");
    exit();
}
include("../php/db.php");
$email=$_SESSION['admin'];
$query=mysqli_query($conn,"SELECT * FROM admins LIMIT 1");
$admin=mysqli_fetch_assoc($query);
if(isset($_POST['update'])){
$name=$_POST['fullname'];
$phone=$_POST['phone'];
mysqli_query($conn,"UPDATE admins SET
fullname='$name',
phone='$phone'
WHERE id=".$admin['id']);
echo "<script>
alert('Profile Updated Successfully');
window.location='profile.php';
</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin Profile</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="admin.css">
</head>
<body>
<div class="container mt-5">
<div class="card shadow">
<div class="card-header bg-primary text-white">
<h3>Admin Profile</h3>
</div>
<div class="card-body">
<form method="POST">
<label>Full Name</label>
<input
type="text"
name="fullname"
class="form-control"
value="<?php echo $admin['fullname'];?>">
<br>
<label>Email</label>
<input
type="email"
class="form-control"
value="<?php echo $admin['email'];?>"
readonly>
<br>
<label>Phone</label>
<input
type="text"
name="phone"
class="form-control"
value="<?php echo $admin['phone'];?>">
<br>
<button
name="update"
class="btn btn-success">
Update Profile
</button>
</form>
</div>
</div>
</div>
</body>
</html>