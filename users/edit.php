<?php
session_start();
include '../backend/connect.php';
include '../backend/functions.php';
include '../navbar.php';
authorize();
$id = $_SESSION['user']['id'];
if (isset($_GET['id']) && $id == $_GET['id']) {
    $sql = "SELECT users.*,
            countries.name as country_name
            from users
            JOIN countries 
            ON users.country_id = countries.id
            WHERE users.id = $id";
    $res = mysqli_query($db_conn, $sql);
    $user = mysqli_fetch_assoc($res);
} else {
    header("location:index.php?id=$id");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Car Rental - Profile</title>
</head>

<body>

    <div class="container">
        <div class="row mt-5">
            <div class="col-6 offset-3">
                <div class="row">
                    <h3 class="text-center">Edit user</h3>
                    <form action="update.php" id="userForm" method="POST" onsubmit="validateUser(event)">
                        <div class="form-group mb-3">
                            <input type="hidden" name="id" value="<?= $id ?>">
                            <input type="text" class="form-control " name="first_name" placeholder="first_name" value="<?= $user['first_name'] ?>">
                            <small class="text-danger input-error d-none" id="error-first_name"></small>
                        </div>
                        <div class="form-group mb-3">
                            <input type="text" class="form-control " name="last_name" placeholder="last_name" value="<?= $user['last_name'] ?>">
                            <small class="text-danger input-error d-none" id="error-last_name"></small>
                        </div>
                        <div class="form-group mb-3">
                            <select name="country_id" class="form-control">
                                <option value="" selected disabled>- choose brand -</option>
                                <?php
                                $sql = "SELECT * FROM countries ORDER BY name";
                                $countries = mysqli_query($db_conn, $sql);
                                while ($country = mysqli_fetch_assoc($countries)) {
                                    $country_id = $country['id'];
                                    $country_name = $country['name'];
                                    $selected = "";
                                    if ($country_id == $user['country_id']) {
                                        $selected = "selected";
                                    }
                                    echo "<option value=\"$country_id\" $selected >$country_name</option>";
                                }
                                ?>
                            </select>
                            <small class="text-danger input-error d-none" id="error-country_id"></small>
                        </div>
                        <div class="form-group mb-3">
                            <input type="text" class="form-control " name="passport_number" placeholder="last_name" value="<?= $user['passport_number'] ?>">
                            <small class="text-danger input-error d-none" id="error-passport_number"></small>
                        </div>
                        <button class="btn btn-primary float-end">Update</button>
                    </form>
                </div>
            </div>
        </div>


        <script src="app.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>