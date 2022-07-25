<?php
session_start();
include '../backend/connect.php';
include '../backend/functions.php';
authorize();

$reservationId = readInput($_GET, 'reservation_id');

$sql = "UPDATE reservations SET
    is_cancelled = 1
WHERE id = $reservationId";

$res = mysqli_query($db_conn, $sql);

echo $res;
