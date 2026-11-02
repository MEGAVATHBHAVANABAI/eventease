<?php
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: admin.html");
    exit();
}

include("../php/db.php");

$result = mysqli_query(
    $conn,
    "SELECT * FROM employee_recruitment ORDER BY id DESC"
);

if (!$result) {
    die("Database Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Employee Recruitment | BY EVENTPLANNER</title>

<link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
>

<link rel="stylesheet" href="admin.css">

</head>

<body>

<div class="d-flex">

    <!-- Sidebar -->
    <div class="sidebar">

        <h2 class="text-center mt-3">
            🎉BY EVENTS
        </h2>

        <hr>

        <a href="dashboard.php">🏠 Dashboard</a>

        <a href="bookings.php">📅 Bookings</a>

        <a href="customers.php">👥 Customers</a>

        <a href="services.php">🛠 Services</a>

        <a href="payments.php">💳 Payments</a>

        <a href="reports.php">📈 Reports</a>

        <a href="employee_recruitment.php">
            👨‍💼 Employee Recruitment
        </a>

        <a href="profile.php">👤 Profile</a>

        <a href="change_password.php">
            🔑 Change Password
        </a>

        <a href="../php/logout.php">
            🚪 Logout
        </a>

    </div>


    <!-- Main Content -->
    <div class="content w-100">

        <nav class="navbar navbar-light bg-white shadow-sm">

            <div class="container-fluid">

                <h3>Employee Recruitment</h3>

                <a
                    href="dashboard.php"
                    class="btn btn-secondary">
                    Dashboard
                </a>

            </div>

        </nav>


        <div class="container mt-4">

            <div class="card shadow">

                <div class="card-header bg-primary text-white">

                    <h4>Employee Applications</h4>

                </div>


                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover">

                            <thead class="table-dark">

                                <tr>

                                    <th>ID</th>

                                    <th>Full Name</th>

                                    <th>Skills</th>

                                    <th>WhatsApp</th>

                                    <th>Email</th>

                                    <th>Address</th>

                                </tr>

                            </thead>


                            <tbody>

                            <?php

                            if (mysqli_num_rows($result) == 0) {

                            ?>

                                <tr>

                                    <td
                                        colspan="7"
                                        class="text-center"
                                    >
                                        No Applications Found
                                    </td>

                                </tr>

                            <?php

                            } else {

                                while ($row = mysqli_fetch_assoc($result)) {

                            ?>

                                <tr>

                                    <td>
                                        <?php
                                        echo htmlspecialchars($row['id']);
                                        ?>
                                    </td>


                                    <!-- IMPORTANT: full_name -->
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
                                            $row['skills']
                                        );
                                        ?>
                                    </td>


                                    <td>
                                        <?php
                                        echo htmlspecialchars(
                                            $row['whatsapp']
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
                                            $row['address']
                                        );
                                        ?>
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

    </div>

</div>

</body>

</html>