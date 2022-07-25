<?php
session_start();
include "../backend/connect.php";
include "../backend/functions.php";
authorizeAdmin();
if (isset($_GET['car_id'])) {
    $car_id = $_GET['car_id'];
} else {
    header("location:index.php");
}

$resBegin = mysqli_query($db_conn, "BEGIN;");

$photoPaths = getPhotoPaths($car_id);
$sqlPhotos = "DELETE from photos where car_id=$car_id";
$res = mysqli_query($db_conn, $sqlPhotos);
if ($res) {
    $sqlCars = "DELETE from cars WHERE id = $car_id";
    $res = mysqli_query($db_conn, $sqlCars);
    if (!$res) {
        $resBegin = mysqli_query($db_conn, "ROLLBACK;");
        echo 0;
        return;
    }
    deletePhotos($photoPaths);
    $resBegin = mysqli_query($db_conn, "COMMIT;");
    echo 1;
} else {
    $resBegin = mysqli_query($db_conn, "ROLLBACK;");
    echo 0;
}
