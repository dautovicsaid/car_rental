<?php
session_start();
include '../backend/connect.php';
include '../backend/functions.php';
authorize();


$car_id = readInput($_POST, 'car');
$user_id = $_SESSION['user']['id'];
$date_from = readInput($_POST, 'date_from');
$date_to = readInput($_POST, 'date_to');
$total_price = readInput($_POST, 'price');

$sql = "SELECT count(*) as count from reservations where car_id = $car_id
AND reservations.is_cancelled = false
AND (((date_from BETWEEN '$date_from' AND '$date_to')
OR (date_to BETWEEN '$date_from' AND '$date_to')))";

$res = mysqli_query($db_conn, $sql);
$row = mysqli_fetch_assoc($res);

if ($row["count"] > 0) {
    header("location:../cars/show.php?id=" . $car_id . "&message=alreadyReserved");
} else if ($row["count"] == 0) {
    $newReservation = [
        "car_id" => $car_id,
        "user_id" => $user_id,
        "date_from" => $date_from,
        "date_to" => $date_to,
        "total_price" => $total_price
    ];
    $sql = generateInsertQuery("reservations", $newReservation);
    $res = mysqli_query($db_conn, $sql);
    if ($res) {
        header("location:index.php?message=successSave");
    } else {
        header("location:index.php?message=failedSave");
    }
}
