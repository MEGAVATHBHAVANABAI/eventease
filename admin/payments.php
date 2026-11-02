<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin.html");
    exit();
}
include("../php/db.php");
$result = mysqli_query($conn, "SELECT * FROM payments ORDER BY id DESC");
if (!$result) {
    die("Database Error: " . mysqli_error($conn));
}
$totalRevenue = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Payment Management</title>
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
<h3>Payment Management</h3>
<a href="dashboard.php" class="btn btn-secondary">
Dashboard
</a>
</div>
</nav>
<div class="container mt-4">
<div class="card shadow">
<div class="card-header bg-success text-white">
<h4>Payment Records</h4>
</div>
<div class="card-body">
<input
type="text"
id="search"
class="form-control mb-3"
placeholder="Search Payment...">
<div class="table-responsive">
<table class="table table-bordered table-hover" id="paymentTable">
<thead class="table-dark">
<tr>
<th>ID</th>
<th>Booking ID</th>
<th>Customer</th>
<th>Method</th>
<th>Amount</th>
<th>Status</th>
<th>Date</th>
</tr>
</thead>
<tbody>
<?php
if(mysqli_num_rows($result)==0){
?>
<tr>
<td colspan="7" class="text-center text-danger">
No Payments Found
</td>
</tr>
<?php
}else{
while($row=mysqli_fetch_assoc($result)){
$totalRevenue += (float)$row['amount'];
?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['booking_id']; ?></td>
<td><?php echo htmlspecialchars($row['customer_name']); ?></td>
<td><?php echo htmlspecialchars($row['payment_method']); ?></td>
<td>
₹<?php echo number_format($row['amount'],2); ?>
</td>
<td>
<?php
$status = $row['payment_status'];
if($status=="Paid"){
echo "<span class='badge bg-success'>Paid</span>";
}elseif($status=="Pending"){
echo "<span class='badge bg-warning text-dark'>Pending</span>";
}elseif($status=="Failed"){
echo "<span class='badge bg-danger'>Failed</span>";
}else{
echo "<span class='badge bg-secondary'>$status</span>";
}
?>
</td>
<td><?php echo htmlspecialchars($row['payment_date']); ?></td>
</tr>
<?php
}
}
?>
</tbody>
</table>
</div>
<h4 class="text-end text-success mt-3">
Total Revenue :
₹<?php echo number_format($totalRevenue,2); ?>
</h4>
</div>
</div>
</div>
</div>
</div>
<script>
document.getElementById("search").addEventListener("keyup",function(){
let value=this.value.toLowerCase();
let rows=document.querySelectorAll("#paymentTable tbody tr");
rows.forEach(function(row){
row.style.display=row.innerText.toLowerCase().includes(value) ? "" : "none";
});
});
</script>
</body>
</html>