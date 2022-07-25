<?php
session_start();
include '../backend/connect.php';
include '../backend/functions.php';
include '../navbar.php';
authorizeAdmin();

if (isset($_GET['id'])) {
    $id = readInput($_GET, 'id');
    $sql = "SELECT car_models.*,
    brands.name as brand_name
    FROM car_models
    JOIN brands
    ON car_models.brand_id=brands.id
    WHERE car_models.id=$id ";
    $res = mysqli_query($db_conn, $sql);
    $model = mysqli_fetch_assoc($res);
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
    <title>Car Rental - Models update</title>
</head>

<body>

    <div class="container">
        <div class="row">
            <h3 class="text-center">Edit model</h3>
            <div class="col-6 offset-3 mt-3">
                <form action="update.php" id="modelForm" method="POST" onsubmit="validateModel(event)">
                    <div class="form-group mb-3">
                        <input type="hidden" name="id" value="<?= $model['id'] ?>">
                        <input type="text" class="form-control" name="name" value="<?= $model['name'] ?>" placeholder="Name">
                        <small class="text-danger input-error d-none" id="error-name"></small>
                    </div>
                    <div class="form-group mb-3">
                        <select name="brand_id" class="form-control">
                            <option value="" selected disabled>- choose brand -</option>
                            <?php
                            $sql = "SELECT * FROM brands ORDER BY name";
                            $brands = mysqli_query($db_conn, $sql);
                            while ($brand = mysqli_fetch_assoc($brands)) {
                                $brandId = $brand['id'];
                                $brandName = $brand['name'];
                                $selected = "";
                                if ($brandId == $model['id']) {
                                    $selected = "selected";
                                }
                                echo "<option value=\"$brandId\" $selected >$brandName</option>";
                            }
                            ?>
                        </select>
                        <small class="text-danger input-error d-none" id="error-brand_id"></small>
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