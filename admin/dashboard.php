<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin.html");
    exit();
}
include("../php/db.php");
$userResult = mysqli_query($conn, "SELECT COUNT(DISTINCT fullname) AS total FROM bookings");
if(!$userResult){
    die("User count Error:".mysqli_error($conn));
    }
$userData = mysqli_fetch_assoc($userResult);
$userCount = $userData['total'];
$bookingResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM bookings");
$bookingData = mysqli_fetch_assoc($bookingResult);
$bookingCount = $bookingData['total'];
$customerResult = mysqli_query($conn, "SELECT COUNT(DISTINCT email) AS total FROM bookings");
$customerData = mysqli_fetch_assoc($customerResult);
$customerCount = $customerData['total'];
$serviceCount = 0;
$checkService = mysqli_query($conn, "SHOW TABLES LIKE 'services'");
if (mysqli_num_rows($checkService) > 0) {
    $serviceResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM services");
    $serviceData = mysqli_fetch_assoc($serviceResult);
    $serviceCount = $serviceData['total'];
}
$revenueResult = mysqli_query($conn, "SELECT SUM(amount) AS total FROM payments");
$revenueData = mysqli_fetch_assoc($revenueResult);
$totalRevenue = $revenueData['total'] ? $revenueData['total'] : 0;
$recruitmentResult = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM employee_recruitment"
);
if (!$recruitmentResult) {
    die("Recruitment count Error: " . mysqli_error($conn));
}
$recruitmentData = mysqli_fetch_assoc($recruitmentResult);
$recruitmentCount = $recruitmentData['total'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="admin.css">
</head>
<body>
<div class="d-flex">
<div class="sidebar">
<h2 class="text-center mt-3">🎉BY Events</h2>
<hr>
<a href="dashboard.php">🏠 Dashboard</a>
<a href="bookings.php">📅 Bookings</a>
<a href="customers.php">👥 Customers</a>
<a href="services.php">🛠 Services</a>
<a href="payments.php">💳 Payments</a>
<a href="reports.php">📈 Reports</a>
<a href="employee_recruitment.php"> 👨‍💼 Employee Recruitment</a>
<a href="profile.php">👤 Profile</a>
<a href="change_password.php">🔑 Change Password</a>
<a href="../php/logout.php">🚪 Logout</a>
</div>
<div class="content w-100">
<nav class="navbar navbar-light bg-white shadow-sm">
<div class="container-fluid">
<h3>
Welcome,
<?php echo htmlspecialchars($_SESSION['admin']); ?>
</h3>
</div>
</nav>
<div class="container mt-4">
<div class="row g-4">
<div class="col-md-3">
<div class="card bg-primary text-white shadow">
<div class="card-body text-center">
<h2><?php echo $userCount; ?></h2>
<h5>Total Users</h5>
</div>
</div>
</div>
<div class="col-md-3">
<div class="card bg-success text-white shadow">
<div class="card-body text-center">
<h2><?php echo $bookingCount; ?></h2>
<h5>Total Bookings</h5>
</div>
</div>
</div>
<div class="col-md-3">
<div class="card bg-warning text-dark shadow">
<div class="card-body text-center">
<h2><?php echo $serviceCount; ?></h2>
<h5>Total Services</h5>
</div>
</div>
</div>
<div class="col-md-3">
<div class="card bg-danger text-white shadow">
<div class="card-body text-center">
<h2>₹<?php echo number_format($totalRevenue,2); ?></h2>
<h5>Total Revenue</h5>
</div>
</div>
</div>
<div class="col-md-3">
    <div class="card bg-info text-white shadow">
        <div class="card-body text-center">
            <h2><?php echo $customerCount; ?></h2>
            <h5>Customers</h5>
        </div>
    </div>
</div>
<div class="col-md-3">
    <div class="card bg-secondary text-white shadow">
        <div class="card-body text-center">
            <h2><?php echo $recruitmentCount; ?></h2>
            <h5>Employee Applications</h5>
            <a href="employee_recruitment.php" class="btn btn-light btn-sm mt-2"> View Applications</a>
        </div>
    </div>
</div>
<div class="mt-5">
    <h3>Quick Actions</h3>
    <a href="add_service.php" class="btn btn-success">Add Service</a>
    <a href="bookings.php" class="btn btn-primary">View Bookings</a>
    <a href="customers.php" class="btn btn-dark"> View Customers</a>
    <a href="employee_recruitment.php" class="btn btn-secondary">👨‍💼 Employee Applications</a>
</div>
</div> 
</div> 
</div> 
</body>
</html>