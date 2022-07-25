<?php
session_start();
include "../backend/connect.php";
include "../backend/functions.php";
authorizeAdmin();

if (isset($_GET['id'])) {
    $id = readInput($_GET, 'id');
} else {
    header("location:index.php");
}

$sql = "DELETE FROM car_models WHERE id = $id";

try {
    $res = mysqli_query($db_conn, $sql);
    header("location:index.php?message=successDelete");
} catch (\Throwable $th) {
    header("location:index.php?message=failedDelete");
}
