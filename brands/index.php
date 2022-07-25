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
        <?php
        if (isset($_GET['message']) && $_GET['message'] == "successSave") {
            echo "
                <div id=\"message_div\" class=\"col-6 offset-3 mt-3\">
                    <div class=\"alert alert-success\" role=\"alert\">
                        Brand created successfully! 
                    </div>
            </div>
            ";
        } else if (isset($_GET['message']) && $_GET['message'] == "failedSave") {
            echo "
                <div id=\"message_div\" class=\"col-6 offset-3 mt-3\">
                    <div class=\"alert alert-danger\" role=\"alert\">
                        Brand create failed! 
                    </div>
                </div>";
        } else if (isset($_GET['message']) && $_GET['message'] == "successUpdate") {
            echo "
                <div id=\"message_div\" class=\"col-6 offset-3 mt-3\">
                    <div class=\"alert alert-success\" role=\"alert\">
                        Brand updated successfully! 
                    </div>
                </div>";
        } else if (isset($_GET['message']) && $_GET['message'] == "failedUpdate") {
            echo "
                <div id=\"message_div\" class=\"col-6 offset-3 mt-3\">
                    <div class=\"alert alert-danger\" role=\"alert\">
                        Brand update failed! 
                    </div>
                </div>";
        } else if (isset($_GET['message']) && $_GET['message'] == "successDelete") {
            echo "
                <div id=\"message_div\" class=\"col-6 offset-3 mt-3\">
                    <div class=\"alert alert-success\" role=\"alert\">
                        Brand deleted successfully! 
                    </div>
                </div>";
        } else if (isset($_GET['message']) && $_GET['message'] == "failedDelete") {
            echo "
                <div id=\"message_div\" class=\"col-6 offset-3 mt-3\">
                    <div class=\"alert alert-danger\" role=\"alert\">
                        Brand delete failed! 
                    </div>
                </div>";
        }

        ?>
        <div class="row mt-5">
            <div class="col-5 offset-2">
                <table class="table table-hover text-center">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Functions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = generateSelectQuery('brands');
                        $res = mysqli_query($db_conn, $sql);
                        while ($row = mysqli_fetch_assoc($res)) {
                            echo "<tr>
                            <td style=\"width:50%\">" . $row['name'] . "</td>
                            <td>
                            <a class=\"btn btn-outline-success\" href=\"edit.php?id=" . $row['id'] . "\">Edit</a>
                            <a class=\"btn btn-outline-danger\" href=\"delete.php?id=" . $row['id'] . "\">Delete</a>
                            </td>
                            </tr>";
                        };
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="col-3 offset-2">
                <a class="btn btn-primary btn-block" href="create.php">Add new brand</a>
            </div>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        var url = window.location.href;
        var getExists = false;
        if (url.indexOf('?message=') != -1) getExists = true;

        function hideMessage() {
            document.getElementById("message_div").style.display = "none";
        }

        if (getExists) {
            setTimeout(hideMessage, 2000);
        }
    </script>
</body>

</html>