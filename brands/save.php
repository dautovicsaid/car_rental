<?php
session_start();

include '../backend/connect.php';
include '../backend/functions.php';
authorizeAdmin();

$name = readInput($_POST, 'name');
$newBrand = ["name" => $name];

$sql = generateInsertQuery("brands", $newBrand);
$res = mysqli_query($db_conn, $sql);
if ($res) {
    header("location:index.php?message=successSave");
} else {
    header("location:create.php?message=failedSave");
}
