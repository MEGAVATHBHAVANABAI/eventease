<?php
include("php/db.php");

if(!isset($_GET['category_id'])){
    die("Category not found.");
}

$category_id = (int)$_GET['category_id'];

$sql = "SELECT sc.*, s.service_name
        FROM service_categories sc
        JOIN services s ON sc.service_id = s.id
        WHERE sc.id='$category_id'";

$result = mysqli_query($conn,$sql);
$data = mysqli_fetch_assoc($result);

if(!$data){
    die("Invalid category.");
}
?>

<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">

<title>Book Service</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container mt-5">

<div class="row justify-content-center">

<div class="col-md-8">

<div class="card shadow">

<div class="card-header bg-primary text-white">

<h3>Book Your Service</h3>

</div>

<div class="card-body">

<form action="php/save_booking.php" method="POST">

<input type="hidden" name="category_id" value="<?php echo $category_id; ?>">

<input type="hidden" name="service_name" value="<?php echo $data['service_name']; ?>">

<input type="hidden" name="category_name" value="<?php echo $data['category_name']; ?>">

<input type="hidden" name="price" value="<?php echo $data['price']; ?>">

<div class="mb-3">
<label>services</label>
<input type="text" class="form-control"
value="<?php echo $data['service_name']; ?>" readonly>
</div>

<div class="mb-3">
<label>Category</label>
<input type="text" class="form-control"
value="<?php echo $data['category_name'];?>" readonly>
</div>

<div class="mb-3">
<label>Price</label>
<input type="text" class="form-control"
value="₹<?php echo $data['price']; ?>" class="form-control" readonly>
</div>

<div class="mb-3">
<label>Full Name</label>
<input type="text" name="customer_name" class="form-control" required>
</div>

<div class="mb-3">
<label>Phone Number</label>
<input type="text" name="phone" class="form-control" required>
</div>

<div class="mb-3">
<label>Email</label>
<input type="email" name="email" class="form-control" required>
</div>

<div class="mb-3">
<label>Address</label>
<textarea name="address" class="form-control" required></textarea>
</div>

<div class="mb-3">
<label>Event Date</label>
<input type="date" name="event_date" class="form-control" required>
</div>

<div class="mb-3">
<label>Event Time</label>
<input type="time" name="event_time" class="form-control" required>
</div>

<div class="mb-3">
<label>Special Request</label>
<textarea name="special_request" class="form-control"></textarea>
</div>

<button type="submit" class="btn btn-success w-100">
    Send Booking Request
</button>

<div class="alert alert-info mt-3">
    <strong>Note:</strong>
    Your booking will be sent as a request.
    Payment is not required at this stage.
    The booking will be confirmed after admin approval.
</div>

</form>

</div>

</div>

</div>

</div>

</div>

</body>

</html>