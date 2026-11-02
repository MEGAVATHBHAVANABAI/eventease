<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin.html");
    exit();
}
include("../php/db.php");
if (isset($_POST['add_service'])) {
    $name = mysqli_real_escape_string($conn, $_POST['service_name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $image = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = time() . "_" . basename($_FILES['image']['name']);
        $target = "../uploads/" . $image;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    }
    $sql = "INSERT INTO services
            (service_name, category, price, description, image)
            VALUES
            ('$name','$category','$price','$description','$image')";
    if (mysqli_query($conn, $sql)) {
        echo "<script>
        alert('Service Added Successfully');
        window.location='services.php';
        </script>";
    } else {
        echo "<script>
        alert('Database Error: ".mysqli_error($conn)."');
        </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Add Service</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="admin.css">
</head>
<body class="bg-light">
<div class="container mt-5">
<div class="card shadow">
<div class="card-header bg-success text-white">
<h3>Add New Service</h3>
</div>
<div class="card-body">
<form method="POST" enctype="multipart/form-data">
<div class="mb-3">
<label class="form-label">Service Name</label>
<input type="text" name="service_name" class="form-control" required>
</div>
<div class="mb-3">
<label class="form-label">Category</label>
<select name="category" class="form-select" required>
<option value="">Select Category</option>
<option>Photography</option>
<option>Decoration</option>
<option>Flower Decoration</option>
<option>Makeup Artist</option>
<option>Mehendi Artist</option>
<option>Hairstylist</option>
<option>Catering</option>
<option>Wall Painting</option>
<option>Saree Draping</option>
</select>
</div>
<div class="mb-3">
<label class="form-label">Price (₹)</label>
<input type="number" name="price" class="form-control" required>
</div>
<div class="mb-3">
<label class="form-label">Description</label>
<textarea name="description" class="form-control" rows="4" required></textarea>
</div>
<div class="mb-3">
<label class="form-label">Upload Image</label>
<input type="file" name="image" class="form-control" accept="image/*" required>
</div>
<button type="submit" name="add_service" class="btn btn-success">Add Service</button>
<a href="services.php" class="btn btn-primary">View Services</a>
<a href="dashboard.php" class="btn btn-secondary">Dashboard</a>
</form>
</div>
</div>
</div>
</body>
</html>