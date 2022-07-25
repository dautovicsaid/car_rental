<?php
session_start();
include '../backend/connect.php';
include '../backend/functions.php';
authorizeAdmin();

$name = readInput($_POST, 'name');
$brandId = readInput($_POST, 'brand_id');
$id = readInput($_POST, 'id');

$sql = "UPDATE car_models SET
name = '$name',
brand_id = $brandId
WHERE id = $id";

$res = mysqli_query($db_conn, $sql);
if ($res) {
    header("location:index.php?message=successUpdate");
} else {
    header("location:index.php?message=failedUpdate");
}
