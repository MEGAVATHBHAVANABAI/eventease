<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin.html");
    exit();
}
include("../php/db.php");
$result = mysqli_query(
    $conn,
    "SELECT
        fullname,
        email,
        MAX(event_date) AS created_at
     FROM bookings
     GROUP BY fullname, email
     ORDER BY created_at DESC"
);
if (!$result) {
    die(
        "Database Error: "
        . mysqli_error($conn)
    );
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport"
      content="width=device-width, initial-scale=1">
<title>Manage Customers</title>
<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">
<link rel="stylesheet" href="admin.css">
</head>
<body>
<div class="d-flex">
<div class="sidebar">
<h2 class="text-center mt-3">
🎉 EventEase
</h2>
<hr>
<a href="dashboard.php">🏠 Dashboard</a>
<a href="bookings.php">📅 Bookings</a>
<a href="customers.php">👥 Customers</a>
<a href="services.php">🛠 Services</a>
<a href="payments.php">💳 Payments</a>
<a href="reports.php">📈 Reports</a>
<a href="profile.php">👤 Profile</a>
<a href="change_password.php">🔑 Change Password</a>
<a href="logout.php" class="text-danger">
🚪 Logout
</a>
</div>
<div class="content w-100">
<nav class="navbar navbar-light bg-white shadow-sm">
<div class="container-fluid">
<h3>
Registered Customers
</h3>
<a href="dashboard.php"
   class="btn btn-secondary">
Dashboard
</a>
</div>
</nav>
<div class="container mt-4">
<div class="card shadow">
<div class="card-header bg-primary text-white">
<h4>
Customer List
</h4>
</div>
<div class="card-body">
<input
type="text"
id="search"
class="form-control mb-3"
placeholder="Search Customer...">
<div class="table-responsive">
<table
class="table table-bordered table-hover"
id="customerTable">
<thead class="table-dark">
<tr>
<th>
ID
</th>
<th>
Name
</th>
<th>
Email
</th>
<th>
Registered / Booking Date
</th>
<th>
Type
</th>
</tr>
</thead>
<tbody>
<?php
if (mysqli_num_rows($result) == 0) {
?>
<tr>
<td
colspan="5"
class="text-center text-danger">
No Customers Found
</td>
</tr>
<?php
}
else {
$i = 1;
while ($row = mysqli_fetch_assoc($result)) {
?>
<tr>
<td>
<?php
echo $i;
?>
</td>
<td>
<?php
echo htmlspecialchars(
    $row['fullname']
);
?>
</td>
<td>
<?php
echo htmlspecialchars(
    $row['email']
);
?>
</td>
<td>
<?php
echo htmlspecialchars(
    $row['created_at']
);
?>
</td>
<td>
<span class="badge bg-success">
Booking Customer
</span>
</td>
</tr>
<?php
$i++;
}
}
?>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
<script>
document
.getElementById("search")
.addEventListener(
    "keyup",
    function () {
        let filter =
            this.value.toLowerCase();
        let rows =
            document.querySelectorAll(
                "#customerTable tbody tr"
            );
        rows.forEach(
            function (row) {
                let text =
                    row.innerText.toLowerCase();
                row.style.display =
                    text.includes(filter)
                    ? ""
                    : "none";
            }
        );
    }
);
</script>
</body>
</html>