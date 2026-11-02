<?php
include("php/db.php");
if (!isset($_GET['id'])) {
    die("Service ID not found.");
}
$service_id = (int)$_GET['id'];
$serviceQuery = mysqli_query($conn, "SELECT * FROM services WHERE id='$service_id'");
if (!$serviceQuery) {
    die("Database Error: " . mysqli_error($conn));
}
$service = mysqli_fetch_assoc($serviceQuery);
if (!$service) {
    die("No service found. Please insert data into the services table.");
}
$categories = mysqli_query($conn, "SELECT * FROM service_categories WHERE service_id='$service_id'");
if (!$categories) {
    die("Database Error: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($service['service_name']); ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{
    background:#f5f5f5;
}
.card{
    border:none;
    border-radius:15px;
    box-shadow:0 0 10px rgba(0,0,0,.15);
    transition:0.3s;
}

.card:hover{
    transform:translateY(-8px);
}

.card img{
    height:220px;
    object-fit:cover;
}
</style>
</head>
<body>
<div class="container mt-5">
<a href="index.html" class="btn btn-secondary mb-4">
← Back to home
</a>
<h2 class="text-center mb-4">
<?php echo htmlspecialchars($service['service_name']); ?> Categories
</h2>
<div class="row">
<?php
if(mysqli_num_rows($categories)>0){
while($row=mysqli_fetch_assoc($categories)){
$image = !empty($row['image']) ? $row['image'] : "no-image.png";
?>
<div class="col-md-4 mb-4">
    <div class="card h-100 shadow">
        <img src="images/<?php echo htmlspecialchars($image); ?>"
             class="card-img-top"
             alt="<?php echo htmlspecialchars($row['category_name']); ?>">
        <div class="card-body d-flex flex-column">
            <h4>
                <?php echo htmlspecialchars($row['category_name']); ?>
            </h4>
            <p>
                <?php echo htmlspecialchars($row['description']); ?>
            </p>
            <h5 class="text-success">
                ₹<?php echo htmlspecialchars($row['price']); ?>
            </h5>
            <a href="booking.php?category_id=<?php echo $row['id']; ?>"
               class="btn btn-success mt-auto w-100">
               Book Now
            </a>
        </div>
    </div>
</div>
<?php
}
}else{
?>
<div class="col-12">
<div class="alert alert-warning text-center">
No categories available for this service.
</div>
</div>
<?php
}
?>
</div>
</div>
</body>
</html>