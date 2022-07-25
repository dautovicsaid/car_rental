<?php
function generateSelectQuery($table, $conditions = [], $columns = ['*'])
{

    $columnsToSelect = implode(',', $columns);
    $sql = "SELECT $columnsToSelect FROM $table WHERE 1=1 ";
    foreach ($conditions as $key => $value) {
        $sql .= " AND  $key = '$value' ";
    }
    return $sql;
}

function generateInsertQuery($table, $columns)
{
    $sql = "INSERT INTO $table ";
    $columnsTemp = [];
    $valuesTemp = [];
    foreach ($columns as $key => $value) {
        $columnsTemp[] = $key;
        $valuesTemp[] = is_numeric($value) ? $value : "'$value'";
    }
    $sql .= "(" . implode(",", $columnsTemp) . ") VALUES (" . implode(',', $valuesTemp) . ")";

    return $sql;
}

function authorize()
{
    if (!isset($_SESSION['login']) || $_SESSION['login'] != true) {
        header('location:/car_rental/login.php');
    }
}

function authorizeAdmin()
{
    if (!isset($_SESSION['login']) || $_SESSION['login'] != true) {
        header('location:/car_rental/login.php');
    } else if (!isset($_SESSION['user']['is_admin']) || $_SESSION['user']['is_admin'] != true) {
        header('location:/car_rental/index.php');
    }
}

function uploadFile($allowed_extensions, $upload_dir, $client_filename, $tmp_name, $depth = 0)
{
    $new_filename = uniqid();
    $name_parts = explode('.', $client_filename);
    $client_extension = $name_parts[count($name_parts) - 1];

    if (!in_array($client_extension, $allowed_extensions)) {
        exit("Format not allowed...");
    }
    $new_filename = $new_filename . "." . $client_extension;

    $tmp_path = $tmp_name;
    $new_photo_path = $upload_dir . $new_filename;
    if (!copy($tmp_path, getBackDots($depth) . $new_photo_path)) {
        exit("Error while uploading photo...");
    }

    return $new_photo_path;
}

function getPhotoPaths($car_id)
{
    global $db_conn;
    $sql = "SELECT * FROM photos WHERE car_id = $car_id";
    $res = mysqli_query($db_conn, $sql);
    $paths = [];

    while ($row = mysqli_fetch_assoc($res)) {
        if ($row == null) {
            continue;
        }

        $paths[] = $row['path'];
    }

    return $paths;
}

function deletePhotos($paths)
{
    foreach ($paths as $path) {
        unlink($_SERVER['DOCUMENT_ROOT'] . '/car_rental/' . $path);
    }
}

function totalPrice($price_per_day, $date_from, $date_to)
{
    $date_from = strtotime($date_from);
    $date_to = strtotime($date_to);
    $difference = $date_to - $date_from;
    return round($difference / (60 * 60 * 24)) * $price_per_day;
}

function getBackDots($depth)
{
    $dots = "";
    while ($depth > 0) {
        $dots .= "../";
        $depth--;
    }
    return $dots;
}

function readInput($array, $key)
{
    if (isset($array[$key]) && $array[$key] != "") {
        return $array[$key];
    }
    return false;
}
