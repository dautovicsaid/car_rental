<?php
session_start();
$is_admin = $_SESSION['user']['is_admin'];
include '../backend/connect.php';
include '../backend/functions.php';
include '../navbar.php';
authorize();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <style>
    .fill {
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden
    }

    .fill img {
      flex-shrink: 0;
      min-width: 100%;
      min-height: 100%
    }
  </style>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <title>Car Rental - Reservations</title>
</head>

<body>

  <div class="container">
    <div id="update_message" class="row d-none mt-3">
      <?php
      if (isset($_GET['message']) && $_GET['message'] == "successSave") {
        echo "
                <div id=\"message_div\" class=\"col-6 offset-3 mt-3\">
                    <div class=\"alert alert-success\" role=\"alert\">
                    Reservation created successfully! 
                    </div>
                </div>
                ";
      } else if (isset($_GET['message']) && $_GET['message'] == "failedSave") {
        echo "
                <div id=\"message_div\" class=\"col-6 offset-3 mt-3\">
                    <div class=\"alert alert-danger\" role=\"alert\">
                        Reservation create failed! 
                    </div>
                </div>";
      }
      ?>
    </div>
    <div class="row">
      <div class="col-8 offset-2 mt-3">
        <h3 class="mb-5"><?= $is_admin ? "Reservations history" : "Current reservations" ?></h3>
        <div id="reservations-grid">
          <?php include 'reservationsDisplay.php'; ?>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="editReservationModal" tabindex="-1" aria-labelledby="editReservationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editReservationModalLabel"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="index.php" id="reservationForm" method="GET" onsubmit="updateReservation(event)">
            <input type="hidden" id="id" name="id">
            <input type="hidden" id="car_id" name="car_id">
            <input type="hidden" id="price_per_day" name="price_per_day">
            <div class="form-group">
              <label for="date_from">Date from:</label>
              <input type="date" id="date_from" name="date_from" class="form-control mb-3" placeholder="Date from" onchange="totalPrice()">
            </div>
            <div class="form-group">
              <label for="date_to">Date to:</label>
              <input type="date" id="date_to" name="date_to" class="form-control mb-3" placeholder="Date to" onchange="totalPrice()">
            </div>
            <div class="form-group">
              <label id="total_price_label"></label>
              <input type="hidden" id="total_price" name="total_price">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" form="reservationForm" data-bs-dismiss="modal" class="btn btn-primary">Update reservation</button>
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