
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once '../backend/connect.php';
include_once '../backend/functions.php';
$is_admin = $_SESSION["user"]["is_admin"];
$userId = $_SESSION["user"]["id"];

$sql = "SELECT users.*,
        countries.name as country_name
        from users 
        JOIN countries 
        on users.country_id=countries.id
        WHERE is_admin = false
        ORDER BY first_name,last_name";

$res = mysqli_query($db_conn, $sql);
$reservationUserHistory = "";

while ($row = mysqli_fetch_assoc($res)) {
    $user_id = $row['id'];
    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
    $country_name = $row['country_name'];
    $passport_number = $row['passport_number'];
    $is_activate = $row['is_active'];
    if ($is_activate) {
        $dynamicContent = "
            <form action\"index.php\" method=\"GET\" onsubmit=\"deactivateUser(event)\">
                <input type=\"hidden\" name=\"user_id\" value=\"$user_id\">
                <button class=\"btn btn-outline-danger btn-block\">Deactivate</button>
            </form>";
    } else {
        $dynamicContent = "
            <p class=\"text-danger\"> Deactivated </p>
        ";
    }
    echo "
        <tr>
            <td>$first_name</td>
            <td>$last_name</td>
            <td>$country_name</td>
            <td>$passport_number</td>
            <td>" . $dynamicContent . "</td>
        </tr>";
};



?>