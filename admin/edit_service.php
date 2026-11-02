<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin.html");
    exit();
}
include("../php/db.php");
if (!isset($_GET['id'])) {
    die("Invalid Service ID");
}
$id = (int)$_GET['id'];
$result = mysqli_query($conn, "SELECT * FROM services WHERE id='$id'");
if (mysqli_num_rows($result) == 0) {
    die("Service Not Found");
}
$row = mysqli_fetch_assoc($result);
if (isset($_POST['update'])) {
    $name = mysqli_real_escape_string($conn, $_POST['service_name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    if (!empty($_FILES['image']['name'])) {
        $image = time() . "_" . $_FILES['image']['name'];
        $temp = $_FILES['image']['tmp_name'];
        move_uploaded_file($temp, "../uploads/" . $image);
        $sql = "UPDATE services SET
                service_name='$name',
                category='$category',
                price='$price',
                description='$description',
                image='$image'
                WHERE id='$id'";
    } else {
        $sql = "UPDATE services SET
                service_name='$name',
                category='$category',
                price='$price',
                description='$description'
                WHERE id='$id'";
    }
    if (mysqli_query($conn, $sql)) {
        echo "<script>
        alert('Service Updated Successfully');
        window.location='services.php';
        </script>";
    } else {
        echo "<script>
        alert('Update Failed');
        </script>";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Edit Service</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="admin.css">
</head>
<body>
<div class="container mt-5">
<div class="card shadow">
<div class="card-header bg-warning text-dark">
<h3>Edit Service</h3>
</div>
<div class="card-body">
<form method="POST" enctype="multipart/form-data">
<label>Service Name</label>
<input
type="text"
name="service_name"
class="form-control"
value="<?php echo htmlspecialchars($row['service_name']); ?>"
required>
<br>
<label>Category</label>
<input
type="text"
name="category"
class="form-control"
value="<?php echo htmlspecialchars($row['category']); ?>"
required>
<br>
<label>Description</label>
<textarea
name="description"
class="form-control"
rows="4"><?php echo htmlspecialchars($row['description']); ?></textarea>
<br>
<label>Current Image</label>
<br>
<img
src="../uploads/<?php echo htmlspecialchars($row['image']); ?>"
width="150"
class="img-thumbnail">
<br><br>
<label>Change Image</label>
<input
type="file"
name="image"
class="form-control">
<br>
<button
type="submit"
name="update"
class="btn btn-warning">
Update Service
</button>
<a href="services.php" class="btn btn-secondary">Cancel</a>
</form>
</div>
</div>
</div>
</body>
</html>