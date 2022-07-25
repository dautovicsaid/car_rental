<?php
session_start();
include '../backend/connect.php';
include '../backend/functions.php';
include '../navbar.php';
authorizeAdmin();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Car Rental - Brands</title>
</head>

<body>

    <div class="container">
        <div id="update_message" class="row d-none mt-3">
            <?php
            if (isset($_GET['message']) && $_GET['message'] == "successSave") {
                echo "
                <div id=\"message_div\" class=\"col-6 offset-3 mt-3\">
                    <div class=\"alert alert-success\" role=\"alert\">
                        You successfully edited your profile!
                    </div>
            </div>
            ";
            }
            ?>
        </div>
        <div class="row">
            <div class="col-8 offset-2 mt-3">
                <h3 class="mb-5">Users</h3>
                <table class="table table-hover text-center">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Country</th>
                            <th>Passport number</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        <?php include 'usersDisplay.php'; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>


    <script src="app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var url = window.location.href;
        var hideMessage = false;
        if (url.indexOf('?message=') != -1) hideMessage = true;

        hideMessage ? function hideMessage() {
            document.getElementById("message_div").style.display = "none";
        } : "";
        setTimeout(hideMessage, 2000);
    </script>
</body>

</html>