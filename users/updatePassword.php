<?php
session_start();
$user_id = $_SESSION['user']['id'];
include '../backend/connect.php';
include '../backend/functions.php';
authorize();
$sql = "SELECT password from users where id=$user_id";
$res = mysqli_query($db_conn, $sql);
$row = mysqli_fetch_assoc($res);
$id = readInput($_POST, 'id');
if ($user_id != $id) {
    header("location:./index.php?id=$user_id&message=notAllowed");
    exit;
}
$old_password =  md5(readInput($_POST, 'old_password'));
$new_password = readInput($_POST, 'new_password');
$new_password_confirm = readInput($_POST, 'new_password_confirm');

if ($old_password == $row["password"] && $new_password == $new_password_confirm) {
    $new_password = md5($new_password);
    $sql = "UPDATE users SET
    password = '$new_password'
    WHERE id = $id";
    $res = mysqli_query($db_conn, $sql);
    if ($res) {
        header("location:./index.php?id=$id&message=successPassUpdate");
    }
} else {
    header("location:./index.php?id=$id&message=failedPassUpdate");
}
