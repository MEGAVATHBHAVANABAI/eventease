<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin.html");
    exit();
}

include("../php/db.php");

/* Total Users */
$userResult = mysqli_query($conn, "SELECT COUNT( distinct fullname) AS total FROM bookings where fullname is not null and fullname!=''");
if(!$userResult){
    die("User count Error:".mysqli_error($conn));
    }
$userData = mysqli_fetch_assoc($userResult);
$users = $userData['total'];

/* Total Bookings */
$bookingResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM bookings");
$bookingData = mysqli_fetch_assoc($bookingResult);
$bookings = $bookingData['total'];

/* Total Services */
$services = 0;
$table = mysqli_query($conn, "SHOW TABLES LIKE 'services'");
if (mysqli_num_rows($table) > 0) {
    $serviceResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM services");
    $serviceData = mysqli_fetch_assoc($serviceResult);
    $services = $serviceData['total'];
}

/* Total Revenue */
$revenueResult = mysqli_query($conn, "SELECT SUM(amount) AS total FROM payments");
$revenueData = mysqli_fetch_assoc($revenueResult);
$totalRevenue = $revenueData['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Reports | EventEase</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="admin.css">

</head>

<body>

<div class="d-flex">

<!-- Sidebar -->

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

<!-- Content -->

<div class="content">

<nav class="navbar navbar-light bg-white shadow-sm">

<div class="container-fluid">

<h3>Reports & Analytics</h3>

<a href="dashboard.php" class="btn btn-secondary">
Dashboard
</a>

</div>

</nav>

<div class="container mt-4">

<div class="row g-4">

<div class="col-lg-3 col-md-6">

<div class="card bg-primary text-white">

<div class="card-body text-center">

<h2><?php echo $users; ?></h2>

<p>Total Users</p>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="card bg-success text-white">

<div class="card-body text-center">

<h2><?php echo $bookings; ?></h2>

<p>Total Bookings</p>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="card bg-warning">

<div class="card-body text-center">

<h2><?php echo $services; ?></h2>

<p>Total Services</p>

</div>

</div>

</div>

<div class="col-lg-3 col-md-6">

<div class="card bg-danger text-white">

<div class="card-body text-center">

<h2>₹<?php echo number_format($totalRevenue, 2); ?></h2>

<p>Total Revenue</p>

</div>

</div>

</div>

</div>

<div class="card shadow mt-5">

<div class="card-header bg-dark text-white">

<h4 class="mb-0">Monthly Summary</h4>

</div>

<div class="card-body">

<table class="table table-bordered">

<tr>

<th>Total Users</th>

<td><?php echo $users; ?></td>

</tr>

<tr>

<th>Total Bookings</th>

<td><?php echo $bookings; ?></td>

</tr>

<tr>

<th>Total Services</th>

<td><?php echo $services; ?></td>

</tr>

<tr>

<th>Total Revenue</th>

<td>₹<?php echo number_format($totalRevenue, 2); ?></td>

</tr>

</table>

<button onclick="window.print()" class="btn btn-success">
🖨 Print Report
</button>

</div>

</div>

</div>

</div>

</div>

</body>

</html>