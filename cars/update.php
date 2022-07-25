<?php

session_start();
include '../backend/functions.php';
include '../backend/connect.php';
authorizeAdmin();

$car_id = readInput($_POST, 'car_id');
$register_number = readInput($_POST, 'register_number');
$brand_id = readInput($_POST, 'brand');
$model_id = readInput($_POST, 'model');
$production_year = readInput($_POST, 'production_year');
$class_id = readInput($_POST, 'class');
$price = readInput($_POST, 'price');

// BEGIN TRANSACTION
$resBegin = mysqli_query($db_conn, "BEGIN;");

$sql = "UPDATE cars SET
register_number = '$register_number',
brand_id = $brand_id,
model_id = $model_id,
production_year = $production_year,
class_id = $class_id,
price_per_day = $price
WHERE id = $car_id
";
$res = mysqli_query($db_conn, $sql);

if ($res) {

    $upload_dir = "uploads/";
    $allowed_extensions = ['pdf', 'doc', 'docx', 'jpg', 'png'];
    $error_attaching = false;

    if (isset($_FILES) && count($_FILES) > 0 && $_FILES['files']['size'][0] > 0) {
        $photoPaths = getPhotoPaths($car_id);
        deletePhotos($photoPaths);
        $sqlDelete = "DELETE FROM photos WHERE car_id = $car_id";
        $res = mysqli_query($db_conn, $sqlDelete);
        if (!$res) {
            $resBegin = mysqli_query($db_conn, "ROLLBACK;");
            $error_attaching = true;
            return;
        }

        foreach ($_FILES['files']['name'] as $key => $file_name) {
            $path = uploadFile($allowed_extensions, $upload_dir, $file_name, $_FILES['files']['tmp_name'][$key], 1);
            $sql_attach = generateInsertQuery('photos', [
                "path" => $path,
                "car_id" => $car_id,
            ]);
            $res = mysqli_query($db_conn, $sql_attach);

            if (!$res) {
                $resBegin = mysqli_query($db_conn, "ROLLBACK;");
                $error_attaching = true;
                break;
            }
        }
    }
    if (!$error_attaching) {
        $resBegin = mysqli_query($db_conn, "COMMIT;");
    }

    echo 1;
} else {
    $resBegin = mysqli_query($db_conn, "ROLLBACK;");
    echo 0;
}
