<?php
session_start();
include '../backend/connect.php';
include '../backend/functions.php';
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
    <title>Car Rental - Models create</title>
</head>

<body>

    <div class="container">
        <div class="row">
            <h3 class="text-center">Create new model</h3>
            <div class="col-6 offset-3 mt-3">
                <form action="save.php" id="modelForm" method="POST" onsubmit="validateModel(event)">
                    <div class="form-group mb-3">
                        <input type="text" class="form-control " name="name" placeholder="Name">
                        <small class="text-danger input-error d-none" id="error-name"></small>
                    </div>
                    <div class="form-group mb-3">
                        <select name="brand_id" class="form-control ">
                            <option value="" selected disabled>- choose brand -</option>
                            <?php
                            $sql = "SELECT * FROM brands ORDER BY name";
                            $brands = mysqli_query($db_conn, $sql);
                            while ($brand = mysqli_fetch_assoc($brands)) {
                                $brandId = $brand['id'];
                                $brandName = $brand['name'];
                                echo "<option value=\"$brandId\" >$brandName</option>";
                            }
                            ?>
                        </select>
                        <small class="text-danger input-error d-none" id="error-brand_id"></small>
                    </div>
                    <button class="btn btn-primary btn-block float-end">Create</button>
                </form>
            </div>
        </div>
    </div>


    <script src="app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>