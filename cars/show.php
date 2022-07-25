<?php

session_start();
include '../backend/functions.php';
include '../backend/connect.php';
include '../navbar.php';
authorize();

if (isset($_GET['id'])) {
    $id = readInput($_GET, 'id');
    $sql = "SELECT
    cars.register_number,
    cars.price_per_day,
    cars.production_year,
    car_models.id as model_id,
    car_models.name as model_name,
    brands.id as brand_id,
    brands.name as brand_name,
    car_classes.id as class_id,
    car_classes.name as class_name
    FROM cars
    JOIN car_models on model_id=car_models.id
    JOIN car_classes on class_id=car_classes.id
    JOIN brands on cars.brand_id=brands.id
    WHERE cars.id=$id";
    $res = mysqli_query($db_conn, $sql);
    $car = mysqli_fetch_assoc($res);
} else {
    header("location:../index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <style>

    </style>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Car Rental - Car details</title>
</head>

<body>


    <div class="container">
        <div id="update_message" class="row d-none mt-3">
            <?php
            if (isset($_GET['message']) && $_GET['message'] == "alreadyReserved") {
                echo "
                <div id=\"message_div\" class=\"col-6 offset-3 mt-3\">
                    <div class=\"alert alert-danger\" role=\"alert\">
                    Car is already reserved for that date! 
                    </div>
                </div>
                ";
            }
            ?>
        </div>
        <h3 class="mt-3">Car details</h3>
        <div class="row mt-5">
            <div class="col-8">
                <div class="row">
                    <div class="col-8 offset-1">
                        <div id="carouselPhotosControls" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <?php
                                $sqlPhotos = "SELECT path from photos WHERE car_id=$id";
                                $resPhotos = mysqli_query($db_conn, $sqlPhotos);
                                $i = 0;
                                while ($photo = mysqli_fetch_assoc($resPhotos)) {
                                    $active = "";
                                    if ($i == 0) {
                                        $active = "active";
                                    }
                                    $path = $photo['path'];
                                    echo "
                                        <div class=\"carousel-item $active\">
                                            <img src=\"../$path\" class=\"d-block w-100\" alt=\"...\">
                                        </div>";
                                    $i++;
                                }

                                ?>
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselPhotosControls" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselPhotosControls" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-6 offset-1">
                        <?php
                        $carRegisterNumber = $car['register_number'];
                        $carPricePerDay = $car['price_per_day'];
                        $carProductionYear = $car['production_year'];
                        $brandName = $car['brand_name'];
                        $className = $car['class_name'];
                        $modelName = $car['model_name'];
                        echo "
                            <div class=\"row\">
                                <div class=\"col-6\">
                                    <p><small class=\"text-muted\">Brand:</small></p>
                                    <p>$brandName</p>
                                </div>
                                <div class=\"col-6\">
                                    <p><small class=\"text-muted\">Model:</small></p>
                                    <p>$modelName</p>
                                </div>
                            </div>
                            <div class=\"row\">
                                <div class=\"col-6\">
                                    <p><small class=\"text-muted\">Class:</small></p>
                                    <p>$className</p>
                                </div>
                                <div class=\"col-6\">
                                    <p><small class=\"text-muted\">Register number:</small></p>
                                    <p>$carRegisterNumber</p>
                                </div>
                            </div>
                            <div class=\"row\">
                                <div class=\"col-6\">
                                    <p><small class=\"text-muted\">Production year::</small></p>
                                    <p>$carProductionYear</p>
                                    </div>
                                <div class=\"col-6\">
                                <p><small class=\"text-muted\">Daily price:</small></p>
                                <p>$carPricePerDay €</p>
                                </div>
                            </div>
                        ";
                        ?>
                    </div>
                </div>
            </div>

            <?= $is_admin ? "" : "<div class=\"col-3 text-center \">
                <form action=\"../reservations/save.php\" id=\"saveForm\" method=\"POST\">
                    <input type=\"hidden\" id=\"car_id\" name=\"car\" value=\" $id \">
                    <input type=\"hidden\" id=\"price_per_day\" name=\"price_per_day\" value=\" $carPricePerDay \">
                    <div class=\"form-group\">
                        <label for=\"date_from\">Date from:</label>
                        <input type=\"date\" id=\"date_from\" name=\"date_from\" class=\"form-control mb-3\" placeholder=\"Date from\" onchange=\"totalPrice()\">
                    </div>
                    <div class=\"form-group\">
                        <label for=\"date_to\">Date to:</label>
                        <input type=\"date\" id=\"date_to\" name=\"date_to\" class=\"form-control mb-3\" placeholder=\"Date to\" onchange=\"totalPrice()\">
                    </div>
                    <div class=\"form-group\">
                        <label id=\"total_price_label\"></label>
                        <input type=\"hidden\" id=\"total_price\" name=\"price\">
                    </div>
                    <button class=\"btn btn-outline-warning btn-block mt-3\">Reserve car</button>
                </form>
            </div>
        </div> " ?>

        </div>



        <script src="./app.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            var url = window.location.href;
            var getExists = false;
            if (url.indexOf('?message=') != -1) {
                getExists = true;
            } else if (url.indexOf('&message=') != -1) {
                getExists = true;
            }

            function hideMessage() {
                document.getElementById("message_div").style.display = "none";
            }

            if (getExists) {
                updateMessage.classList.remove("d-none");
                setTimeout(hideMessage, 2000);
            }
        </script>
</body>

</html>