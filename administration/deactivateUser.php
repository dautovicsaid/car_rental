<?php
session_start();
include '../backend/connect.php';
include '../backend/functions.php';
authorizeAdmin();

$user_id = readInput($_POST, 'user_id');


$sql = "UPDATE users SET
is_active = false
WHERE id = $user_id";

$res = mysqli_query($db_conn, $sql);

echo $res;
