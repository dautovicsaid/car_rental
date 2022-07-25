<?php
session_start();
$user_id = $_SESSION['user']['id'];
include '../backend/connect.php';
include '../backend/functions.php';
authorize();

$id = readInput($_POST, 'id');
if ($user_id != $id) {
    header("location:./index.php?id=$user_id&message=notAllowed");
    exit;
}
$first_name = readInput($_POST, 'first_name');
$last_name = readInput($_POST, 'last_name');
$country_id = readInput($_POST, 'country_id');
$passport_number = readInput($_POST, 'passport_number');

$sql = "UPDATE users SET
first_name = '$first_name',
last_name = '$last_name',
country_id = $country_id,
passport_nUMber = '$passport_number'
WHERE id = $id";

$res = mysqli_query($db_conn, $sql);
if ($res) {
    header("location:./index.php?id=$id&message=successEdit");
} else {
    header("location:./index.php?id=$id&message=failedEdit");
}
