<?php

include '../backend/connect.php';
include '../backend/functions.php';

if (isset($_GET['brand_id'])) {
    $brand_id = readInput($_GET, 'brand_id');
} else {
    echo json_encode([]); // "[]"
}

$resModels = [];
$sql = generateSelectQuery("car_models", ["brand_id" => $brand_id]);
$res = mysqli_query($db_conn, $sql);

while ($row = mysqli_fetch_assoc($res)) {
    $resModels[] = $row;
}

echo json_encode($resModels);
