<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.html?login=1");
    exit();
}
include("php/db.php");
$email = $_SESSION['user'];
$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM bookings WHERE email = ? ORDER BY id DESC"
);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Bookings | EventEase</title>
<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">
<style>

body {
    background: #f5f5f5;
}

.booking-container {
    margin-top: 50px;
}

.booking-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.page-title {
    text-align: center;
    margin-bottom: 30px;
}

</style>

</head>

<body>

<div class="container booking-container">
    <div class="booking-card">
        <h2 class="page-title">📅 My Bookings</h2>
        <?php if (mysqli_num_rows($result) > 0) { ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Booking ID</th>
                        <th>Service</th>
                        <th>Event Date</th>
                        <th>Action<th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td>
                            <?php echo htmlspecialchars($row['id']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($row['service']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($row['event_date']); ?>
                        </td>
                        <td>
                            <?php
                            if ($row['status'] == "Pending") {
                                echo '<span class="badge bg-warning text-dark">
                                        Pending
                                      </span>';
                            } elseif ($row['status'] == "Accepted") {

                                echo '<span class="badge bg-success">
                                        Accepted
                                      </span>';
                            } elseif ($row['status'] == "Rejected") {
                                echo '<span class="badge bg-danger">
                                        Rejected
                                      </span>';
                            } else {
                                echo '<span class="badge bg-secondary">
                                        ' . htmlspecialchars($row['status']) . '
                                     </span>';
                            }
                            ?>
                        </td>
                        <td>
                            <a
                            href="booking_success.php?booking_id=<?php echo $row['id']; ?>"
                            class="btn btn-info btn-sm">
                                View Details
                            </a>
                            <a
                            href="invoice.php?id=<?php echo $row['id']; ?>"
                            class="btn btn-success btn-sm">
                            🧾 Invoice
                            </a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <?php } else { ?>
            <div class="alert alert-info text-center">
                📭 You don't have any bookings yet.
                <br><br>
                <a href="index.html" class="btn btn-primary">
                    Explore Services
                </a>
            </div>
        <?php } ?>
        <div class="text-center mt-4">
            <a href="index.html" class="btn btn-secondary">
                ← Back to Home
            </a>
        </div>
    </div>
</div>
</body>
</html>