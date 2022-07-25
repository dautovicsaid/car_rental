<?php
session_start();
include '../backend/connect.php';
include '../backend/functions.php';
authorizeAdmin();

$name = readInput($_POST, 'name');
$newCountry = ["name" => $name];

$sql = generateInsertQuery("countries", $newCountry);
$res = mysqli_query($db_conn, $sql);
if ($res) {
    header("location:index.php?message=successSave");
} else {
    header("location:index.php?message=failedSave");
}
