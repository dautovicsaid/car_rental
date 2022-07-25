<?php
session_start();
include '../backend/connect.php';
include '../backend/functions.php';
include '../navbar.php';
authorizeAdmin();

if (isset($_GET['id'])) {
    $id = readInput($_GET, 'id');
    $sql = generateSelectQuery("brands", ["id" => $id]);
    $res = mysqli_query($db_conn, $sql);
    $brand = mysqli_fetch_assoc($res);
} else {
    header("location:index.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Car Rental - Brands edit</title>
</head>

<body>

    <div class="container">
        <div class="row">
            <h3 class="text-center">Edit brand</h3>
            <div class="col-6 offset-3 mt-3">
                <form action="update.php" id="brandForm" method="POST" onsubmit="validateBrand(event)">
                    <div class="form-group mb-3">
                        <input type="hidden" name="id" value="<?= $brand['id'] ?>">
                        <input type="text" class="form-control " name="name" placeholder="Name" value="<?= $brand['name'] ?>">
                        <small class="text-danger input-error d-none" id="error-name"></small>
                    </div>
                    <button class="btn btn-primary btn-block float-end">Update</button>
                </form>
            </div>
        </div>
    </div>


    <script src="app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>