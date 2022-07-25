<?php
session_start();
include '../backend/connect.php';
include '../backend/functions.php';
authorize();

$reservationId = readInput($_GET, 'id');
$car_id = readInput($_GET, 'car_id');
$date_from = readInput($_GET, 'date_from');
$date_to = readInput($_GET, 'date_to');
$total_price = readInput($_GET, 'total_price');

$sql = "SELECT count(*) as count from reservations where car_id = $car_id
AND reservations.is_cancelled = false
AND NOT reservations.id = $reservationId
AND (((date_from BETWEEN '$date_from' AND '$date_to')
OR (date_to BETWEEN '$date_from' AND '$date_to')))";

$res = mysqli_query($db_conn, $sql);
$row = mysqli_fetch_assoc($res);

if ($row["count"] > 0) {
    echo "alreadyReserved";
} else if ($row["count"] == 0) {

    $sql = "UPDATE reservations SET
    car_id = $car_id,
    date_from = '$date_from',
    date_to = '$date_to',
    total_price = $total_price
    WHERE id = $reservationId";
    $res = mysqli_query($db_conn, $sql);

    echo $res;
}
