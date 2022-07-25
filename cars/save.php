<?php

session_start();
include '../backend/functions.php';
include '../backend/connect.php';
authorizeAdmin();

$register_number = readInput($_POST, 'register_number');
$brand_id = readInput($_POST, 'brand');
$model_id = readInput($_POST, 'model');
$production_year = readInput($_POST, 'production_year');
$class_id = readInput($_POST, 'class');
$price = readInput($_POST, 'price');

// BEGIN TRANSACTION
$resBegin = mysqli_query($db_conn, "BEGIN;");

$sql = generateInsertQuery('cars', [
    "register_number" => $register_number,
    "brand_id" => $brand_id,
    "model_id" => $model_id,
    "production_year" => $production_year,
    "class_id" => $class_id,
    "price_per_day" => $price,
]);
$res = mysqli_query($db_conn, $sql);

if ($res) {

    $new_car_id = mysqli_insert_id($db_conn);

    $upload_dir = "uploads/";
    $allowed_extensions = ['pdf', 'doc', 'docx', 'jpg', 'png'];
    $error_attaching = false;

    if (isset($_FILES) && count($_FILES) > 0) {

        foreach ($_FILES['files']['name'] as $key => $file_name) {
            $path = uploadFile($allowed_extensions, $upload_dir, $file_name, $_FILES['files']['tmp_name'][$key], 1);
            $sql_attach = generateInsertQuery('photos', [
                "path" => $path,
                "car_id" => $new_car_id,
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

    header('location:../index.php?message=successSave');
} else {
    $resBegin = mysqli_query($db_conn, "ROLLBACK;");
    header('location:create.php?msg=error');
}
