<?php

session_start();
include '../connect.php';
include '../functions.php';

$username = readInput($_POST, 'username');
$password = readInput($_POST, 'password');

$sql = generateSelectQuery('users', ['username' => $username, 'password' => md5($password), 'is_active' => true]);
$res = mysqli_query($db_conn, $sql);

if (mysqli_num_rows($res) == 1) {
    $_SESSION['login'] = true;
    $_SESSION['user'] = mysqli_fetch_assoc($res);

    header('location:../../index.php?message=successLogin');
    exit;
} else {
    header('location:../../login.php?message=failedLogin');
    exit;
}
