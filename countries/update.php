<?php
session_start();
include '../backend/connect.php';
include '../backend/functions.php';
authorizeAdmin();

$name = readInput($_POST, 'name');
$id = readInput($_POST, 'id');

$sql = "UPDATE countries SET
name = '$name'
WHERE id = $id";

$res = mysqli_query($db_conn, $sql);
if ($res) {
    header("location:index.php?message=successUpdate");
} else {
    header("location:index.php?message=failedUpdate");
}
