<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Car Rental - login</title>
</head>

<body>

    <div class="container">
        <div id="update_message" class="row d-none mt-3">
            <?php
            if (isset($_GET['message']) && $_GET['message'] == "failedLogin") {
                echo "
                <div id=\"message_div\" class=\"col-6 offset-3 mt-3\">
                    <div class=\"alert alert-success\" role=\"alert\">
                     Logging in failed! 
                    </div>
                </div>
                ";
            }
            ?>
        </div>
        <div class="row">
            <div class="col-6 offset-3 mt-5">
                <h3 class="text-center mb-3">Login</h3>
                <form action="backend/auth/login.php" id="loginForm" method="POST" onsubmit="validateLogin(event)">
                    <div class="form-group mb-4">
                        <input type="text" name="username" class="form-control" placeholder="Enter username" />
                        <small class="text-danger input-error d-none" id="error-username"></small>
                    </div>
                    <div class="form-group mb-4">
                        <input type="password" name="password" class="form-control" placeholder="Enter password" />
                        <small class="text-danger input-error d-none" id="error-password"></small>
                    </div>
                    <button class="btn btn-primary btn-block mb-4">Sign in</button>
                </form>
                <div class="text-center">
                    <p>Not a member? <a href="register.php">Register</a></p>
                </div>
            </div>
        </div>
    </div>


    <script src="app.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var url = window.location.href;
        var getExists = false;
        if (url.indexOf('?message=') != -1) getExists = true;

        function hideMessage() {
            document.getElementById("message_div").style.display = "none";
        }

        if (getExists) {
            updateMessage.classList.remove("d-none");
            setTimeout(hideMessage, 2000);
        }
    </script>
</body>

</html>