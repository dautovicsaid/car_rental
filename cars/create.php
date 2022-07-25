<?php

session_start();
include '../backend/functions.php';
include '../backend/connect.php';
include '../navbar.php';
authorizeAdmin();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Document</title>
</head>

<body>


    <div class="container">
        <div class="row mt-3">
            <h4 class="text-center">Add new car</h4>
            <div class="col-6 offset-3 mt-3">
                <form action="save.php" id="carForm" method="POST" enctype="multipart/form-data" onsubmit="validateCar(event)">
                    <div class="form-group mb-3">
                        <label for="brand">Brands:</label>
                        <select name="brand" id="brand" class="form-control" onchange="loadModels()">
                            <option value="" selected disabled>- Choose brand -</option>
                            <?php
                            $sqlBrands = "SELECT * FROM brands ORDER BY name";
                            $resBrands = mysqli_query($db_conn, $sqlBrands);

                            while ($row = mysqli_fetch_assoc($resBrands)) {
                                $brandId = $row['id'];
                                $brandName = $row['name'];
                                echo "<option value=\"$brandId\">$brandName</option>";
                            }

                            ?>
                        </select>
                        <small class="text-danger input-error d-none" id="error-brand_id"></small>
                    </div>
                    <div class="form-group mb-3">
                        <label for="model">Models:</label>
                        <select name="model" id="model" class="form-control"> </select>
                        <small class="text-danger input-error d-none" id="error-model_id"></small>
                    </div>
                    <div class="form-group mb-3">
                        <label for="class">Classes:</label>
                        <select name="class" class="form-control">
                            <option value="" selected disabled>- Choose class -</option>
                            <?php
                            $sqlClasses = "SELECT * FROM car_classes ORDER BY name";
                            $resClasses = mysqli_query($db_conn, $sqlClasses);

                            while ($row = mysqli_fetch_assoc($resClasses)) {
                                $classId = $row['id'];
                                $className = $row['name'];
                                echo "<option value=\"$classId\">$className</option>";
                            }

                            ?>
                        </select>
                        <small class="text-danger input-error d-none" id="error-class_id"></small>
                    </div>
                    <div class="form-group mb-3">
                        <label for="register_number">Register number:</label>
                        <input type="text" name="register_number" id="register_number" class="form-control" placeholder="Register number">
                        <small class="text-danger input-error d-none" id="error-register_number"></small>
                    </div>

                    <div class="form-group mb-3">
                        <label for="production_year">Production year:</label>
                        <input type="number" name="production_year" id="production_year" class="form-control" placeholder="Register number">
                        <small class="text-danger input-error d-none" id="error-production_year"></small>
                    </div>

                    <div class="form-group mb-3">
                        <label for="price">Price per day:</label>
                        <input type="number" name="price" id="price" class="form-control" placeholder="Price per day">
                        <small class="text-danger input-error d-none" id="error-price_per_day"></small>
                    </div>

                    <div class="form-group mb-3">
                        <label for="photos">Photos:</label>
                        <input type="file" required id="photos" name="files[]" multiple class="form-control">
                    </div>
                    <div class="text-center">
                        <button class="btn btn-primary">Create</button>
                    </div>

                </form>
            </div>
        </div>
    </div>



    <script src="app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>