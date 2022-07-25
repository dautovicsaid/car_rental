<?php
session_start();
include '../backend/connect.php';
include '../backend/functions.php';
include '../navbar.php';
authorize();

if (isset($_GET['id'])) {
    $id = readInput($_GET, 'id');
    $sql = "SELECT users.*,
            countries.name as country_name
            from users
            JOIN countries 
            ON users.country_id = countries.id
            WHERE users.id = $id";
    $res = mysqli_query($db_conn, $sql);
    $user = mysqli_fetch_assoc($res);
} else {
    header("location:../index.php");
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
        <?php
        if (isset($_GET['message']) && $_GET['message'] == "successEdit") {
            echo "
                <div id=\"message_div\" class=\"col-6 offset-3 mt-3\">
                    <div class=\"alert alert-success\" role=\"alert\">
                    Profile edited successfully!
                    </div>
                </div>";
        } else if (isset($_GET['message']) && $_GET['message'] == "failedEdit") {
            echo "
                <div id=\"message_div\" class=\"col-6 offset-3 mt-3\">
                    <div class=\"alert alert-danger\" role=\"alert\">
                    Profile edit failed!
                    </div>
                </div>";
        } else if (isset($_GET['message']) && $_GET['message'] == "successPassUpdate") {
            echo "
                <div id=\"message_div\" class=\"col-6 offset-3 mt-3\">
                    <div class=\"alert alert-success\" role=\"alert\">
                    Password updated successfully!
                    </div>
                </div>";
        } else if (isset($_GET['message']) && $_GET['message'] == "failedPassUpdate") {
            echo "
                <div id=\"message_div\" class=\"col-6 offset-3 mt-3\">
                    <div class=\"alert alert-danger\" role=\"alert\">
                    Password update failed!
                    </div>
                </div>";
        } else if (isset($_GET['message']) && $_GET['message'] == "notAllowed") {
            echo "
                <div id=\"message_div\" class=\"col-6 offset-3 mt-3\">
                    <div class=\"alert alert-danger\" role=\"alert\">
                    CRUD Operations on other users not allowed!
                    </div>
                </div>";
        }
        ?>
        <div class="row mt-5">
            <div class="col-6 offset-2">
                <h3 class="mb-5">User details</h3>
                <?php
                $user_id = $user['id'];
                $first_name = $user['first_name'];
                $last_name = $user['last_name'];
                $country_name = $user['country_name'];
                $passport_number = $user['passport_number'];
                echo "
                                <div class=\"col-8\">
                                    <p><small class=\"text-muted\">First name:</small></p>
                                    <p>$first_name</p>
                                </div>
                                <div class=\"col-8\">
                                    <p><small class=\"text-muted\">Last name:</small></p>
                                    <p>$last_name</p>
                                </div>
                                <div class=\"col-8\">
                                    <p><small class=\"text-muted\">Country:</small></p>
                                    <p>$country_name</p>
                                </div>
                                <div class=\"col-8\">
                                    <p><small class=\"text-muted\">Passport number:</small></p>
                                    <p>$passport_number</p>
                                </div>
                        ";
                ?>
            </div>
            <div class="col-2">
                <div class="d-grid gap-3">
                    <a class="btn btn-outline-primary" href="edit.php?id=<?= $id ?>">Edit profile</a>
                    <a class="btn btn-outline-primary" href="passwordChange.php?id=<?= $id ?>">Change password</a>
                </div>
            </div>
        </div>
    </div>


    <script src="app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var url = window.location.href;
        var getExists = false;
        if (url.indexOf('&message=') != -1) {
            getExists = true;
        }

        function hideMessage() {
            document.getElementById("message_div").style.display = "none";
        }

        if (getExists) {
            setTimeout(hideMessage, 2000);
        }
    </script>
</body>

</html>