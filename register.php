<?php
include 'backend/connect.php';
include 'backend/functions.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Car Rental - Register</title>
</head>

<body>

    <div class="container">

        <div class="row">
            <div class="col-6 offset-3 mt-5">
                <h3 class="text-center mb-3">Register</h3>
                <form action="backend/auth/register.php" id="registerForm" method="POST" onsubmit="validateRegister(event)">
                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="form-group">
                                <input type="text" class="form-control" name="first_name" placeholder="First name">
                                <small class="text-danger input-error d-none" id="error-first_name"></small>

                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <input type="text" class="form-control" name="last_name" placeholder="Last name">
                                <small class="text-danger input-error d-none" id="error-last_name"></small>

                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <input type="text" class="form-control" name="username" placeholder="Username">
                        <small class="text-danger input-error d-none" id="error-username"></small>
                    </div>
                    <div class="form-group mb-3">
                        <input type="password" class="form-control" name="password" placeholder="Password">
                        <small class="text-danger input-error d-none" id="error-password"></small>
                    </div>
                    <div class="form-group mb-3">
                        <input type="password" class="form-control" name="confirm_password" placeholder="Confirm password">
                        <small class="text-danger input-error d-none" id="error-confirm_password"></small>
                    </div>
                    <div class="form-group mb-3">
                        <input type="text" class="form-control" name="passport_number" placeholder="Passport number">
                        <small class="text-danger input-error d-none" id="error-passport_number"></small>
                    </div>
                    <div class="form-group mb-3">
                        <select name="country_id" class="form-control">
                            <option value="" selected disabled>- odaberite državu -</option>
                            <?php
                            $sql = "SELECT * FROM countries ORDER BY name";
                            $countries = mysqli_query($db_conn, $sql);
                            while ($country = mysqli_fetch_assoc($countries)) {
                                $countryId = $country['id'];
                                $countryName = $country['name'];
                                echo "<option value=\"$countryId\" >$countryName</option>";
                            }
                            ?>
                        </select>
                        <small class="text-danger input-error d-none" id="error-country_id"></small>
                    </div>
                    <button class="btn btn-primary">Register</button>
                </form>
            </div>
            <a href="login.php" class="text-center">Already registered? Click here to login</a>
        </div>
    </div>


    <script src="app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>