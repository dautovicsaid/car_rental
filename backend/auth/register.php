<?php

include "../connect.php";
include "../functions.php";
session_start();

$first_name = readInput($_POST, 'first_name');
$last_name = readInput($_POST, 'last_name');
$username = readInput($_POST, 'username');
$password = readInput($_POST, 'password');
$confirm_password = readInput($_POST, 'confirm_password');
$passport_number = readInput($_POST, 'passport_number');
$country_id = readInput($_POST, 'country_id');
$is_admin = false;
$is_active = true;

if ($password != $confirm_password) {
    header('location:../../register.php?err=1');
    exit;
}

$newUser = [
    "first_name" => $first_name,
    "last_name" => $last_name,
    "username" => $username,
    "password" => md5($password),
    "passport_number" => $passport_number,
    "country_id" => $country_id,
    "is_admin" => $is_admin,
    "is_active" => $is_active
];
$sql = generateInsertQuery('users', $newUser);

if (mysqli_query($db_conn, $sql)) {
    $newUser['id'] = mysqli_insert_id($db_conn);
    $_SESSION['login'] = true;
    $_SESSION['user'] = $newUser;

    header('location:../../index.php?message=successRegister');
    exit;
} else {
    header('location:register.php?message=failedRegister');
}
