<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin.html");
    exit();
}
include("../php/db.php");
$result = mysqli_query($conn, "SELECT * FROM bookings ORDER BY id DESC");
if (!$result) {
    die("Database Error: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Bookings | BY EVENTPLANNER</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
<div class="d-flex justify-content-between mb-4">
<h2>📅 Manage Bookings</h2>
<a href="dashboard.php" class="btn btn-secondary">🏠 Dashboard</a>
</div>
<div class="card shadow">
<div class="card-body">
<div class="table-responsive">
<table class="table table-bordered table-hover align-middle">
<thead class="table-dark">
<tr>
<th>ID</th>
<th>Customer</th>
<th>Phone</th>
<th>Service</th>
<th>Category</th>
<th>Price</th>
<th>Event Date</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php
if (mysqli_num_rows($result) == 0) {
?>
<tr>
<td colspan="9" class="text-center text-danger">
No Bookings Found
</td>
</tr>
<?php
} else {
while ($row = mysqli_fetch_assoc($result)) {
?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo htmlspecialchars($row['fullname']); ?></td>
<td><?php echo htmlspecialchars($row['phonenumber']); ?></td>
<td><?php echo htmlspecialchars($row['service']); ?></td>
<td><?php echo htmlspecialchars($row['category']); ?></td>
<td>
₹<?php echo number_format($row['price'],2); ?>
</td>
<td>
<?php echo htmlspecialchars($row['event_date']); ?>
</td>
<td>
<?php
$status = $row['status'];
if ($status == "Pending") {
echo "<span class='badge bg-warning'>Pending</span>";
} elseif ($status == "Accepted") {
echo "<span class='badge bg-success'>Accepted</span>";
} elseif ($status == "Completed") {
echo "<span class='badge bg-primary'>Completed</span>";
} elseif ($status == "Rejected") {
echo "<span class='badge bg-danger'>Rejected</span>";
} else {
echo "<span class='badge bg-secondary'>Unknown</span>";
}
?>
</td>
<td>
<a href="update_booking.php?id=<?php echo $row['id']; ?>&status=Accepted"
class="btn btn-success btn-sm mb-1">
Accept
</a>
<a href="update_booking.php?id=<?php echo $row['id']; ?>&status=Rejected"
class="btn btn-danger btn-sm mb-1">
Reject
</a>
<a href="update_booking.php?id=<?php echo $row['id']; ?>&status=Completed"
class="btn btn-primary btn-sm">
Complete
</a>
</td>
</tr>
<?php
}
}
?>
</tbody>
</table>
</div>
</div>
</div>
</div>
</body>
</html>