<?php
session_start();
include '../backend/connect.php';
include '../backend/functions.php';
include '../navbar.php';
authorize();
$id = $_SESSION['user']['id'];
if (isset($_GET['id']) && $id != $_GET['id']) {
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
    <title>Car Rental - Change password</title>
</head>

<body>

    <div class="container">
        <div class="row mt-5">
            <div class="col-6 offset-3">
                <div class="row">
                    <h3 class="text-center">Change Password</h3>
                    <form action="updatePassword.php" method="POST">
                        <div class="form-group mb-3">
                            <input type="hidden" name="id" value="<?= $id ?>">
                            <label for="old_password">Old password:</label>
                            <input type="password" id="old_password" class="form-control" name="old_password" placeholder="Enter old password">
                        </div>
                        <div class="form-group mb-3">
                            <label for="new_password">New password:</label>
                            <input type="password" id="new_password" class="form-control " name="new_password" placeholder="Enter new password">
                        </div>
                        <div class="form-group mb-3">
                            <label for="new_password_confirm">Confirm password:</label>
                            <input type="password" id="new_password_confirm" class="form-control " name="new_password_confirm" placeholder="Confirm password">
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