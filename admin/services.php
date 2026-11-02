<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("Location: admin.html");
    exit();
}
include("../php/db.php");
$result = mysqli_query($conn,"SELECT * FROM services ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Manage Services</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="admin.css">
</head>
<body>
<div class="d-flex">
<div class="sidebar">
<h2 class="text-center mt-3">🎉 EventEase</h2>
<hr>
<a href="dashboard.php">🏠 Dashboard</a>
<a href="bookings.php">📅 Bookings</a>
<a href="customers.php">👥 Customers</a>
<a href="services.php">🛠 Services</a>
<a href="payments.php">💳 Payments</a>
<a href="reports.php">📈 Reports</a>
<a href="profile.php">👤 Profile</a>
<a href="change_password.php">🔑 Change Password</a>
<a href="../php/logout.php">🚪 Logout</a>
</div>
<div class="content">
<nav class="navbar navbar-light bg-white shadow-sm">
<div class="container-fluid">
<h3>Manage Services</h3>
<a href="add_service.php" class="btn btn-success">
+ Add New Service
</a>
</div>
</nav>
<div class="container mt-4">
<div class="card shadow">
<div class="card-header bg-primary text-white">
<h4>All Services</h4>
</div>
<div class="card-body">
<table class="table table-bordered table-hover">
<thead class="table-dark">
<tr>
<th>ID</th>
<th>Service</th>
<th>Category</th>
<th>Description</th>
<th>Actions</th>
</tr>
</thead>
<tbody>
<?php
while($row=mysqli_fetch_assoc($result))
{
?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['service_name']; ?></td>
<td><?php echo $row['category']; ?></td>
<td><?php echo $row['description']; ?></td>
<td>
<a
href="edit_service.php?id=<?php echo $row['id'];?>"
class="btn btn-warning btn-sm">
Edit
</a>
<a
href="delete_service.php?id=<?php echo $row['id'];?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this service?')">
Delete
</a>
</td>
</tr>
<?php
}
?>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</body>
</html>