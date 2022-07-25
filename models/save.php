<?php
session_start();
include '../backend/connect.php';
include '../backend/functions.php';
authorizeAdmin();

$name = readInput($_POST, 'name');
$brand_id = readInput($_POST, 'brand_id');
$newModel = [
    "name" => $name,
    "brand_id" => $brand_id,
];

$sql = generateInsertQuery("car_models", $newModel);
$res = mysqli_query($db_conn, $sql);
if ($res) {
    header("location:index.php?message=successSave");
} else {
    header("location:index.php?message=failedSave");
}
