<?php
session_start();
$is_admin = $_SESSION['user']['is_admin'];
include 'backend/connect.php';
include 'backend/functions.php';
include 'navbar.php';
authorize();

$sqlBrands = "SELECT * FROM brands ORDER BY name";
$resBrands = mysqli_query($db_conn, $sqlBrands);
$brands = [];

while ($rowBrand = mysqli_fetch_assoc($resBrands)) {
  $brands[] = $rowBrand;
}

$sqlClasses = "SELECT * FROM car_classes ORDER BY name";
$resClasses = mysqli_query($db_conn, $sqlClasses);
$classes = [];

while ($rowClass = mysqli_fetch_assoc($resClasses)) {
  $classes[] = $rowClass;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <style>
    #filters {
      height: 100vh;
    }

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
  <title>Car Rental - Cars</title>
</head>

<body onload="<?= ($is_admin) ? "filterCars();" : "" ?>">
  <div class="container">
    <div id="update_message" class="row d-none mt-3">
      <?php
      if (isset($_GET['message']) && $_GET['message'] == "successSave") {
        echo "
                <div id=\"message_div\" class=\"col-6 offset-3 mt-3\">
                    <div class=\"alert alert-success\" role=\"alert\">
                        Car created successfully! 
                    </div>
            </div>
            ";
      } else if (isset($_GET['message']) && $_GET['message'] == "successLogin") {
        echo "
                <div id=\"message_div\" class=\"col-6 offset-3 mt-3\">
                    <div class=\"alert alert-success\" role=\"alert\">
                        Logged in successfully! 
                    </div>
            </div>
            ";
      } else if (isset($_GET['message']) && $_GET['message'] == "successRegister") {
        echo "
                <div id=\"message_div\" class=\"col-6 offset-3 mt-3\">
                    <div class=\"alert alert-success\" role=\"alert\">
                        Profile created successfully! 
                    </div>
            </div>
            ";
      }
      ?>
    </div>
    <div id="filters" class="row mt-5 <?= ($is_admin) ? "d-none" : "" ?>">
      <div class="col-4 offset-4">
        <h3 class="text-center">Enter date:</h3>
        <form action="index.php" method="GET" onsubmit="filterCars(event)">
          <div class="form-group">
            <label for="date_from">Date from:</label>
            <input type="date" id="filter_date_from" name="date_from" class="form-control mb-3" placeholder="Date from" required>
          </div>
          <div class="form-group">
            <label for="date_to">Date to:</label>
            <input type="date" id="filter_date_to" name="date_to" class="form-control mb-3" placeholder="Date to" required>
          </div>
          <div class="form-group mb-3">
            <label for="brand">Brands:</label>
            <select name="brand" id="filter_brand" class="form-control" onchange="loadModelsForFilter()">
              <option value="" selected>- Choose brand -</option>
              <?php
              foreach ($brands as $brand) {
                $brandId = $brand['id'];
                $brandName = $brand['name'];
                echo "<option value=\"$brandId\">$brandName</option>";
              }
              ?>
            </select>
            </select>
          </div>
          <div class="form-group mb-3">
            <label for="model">Models:</label>
            <select name="model" id="filter_model" class="form-control"></select>
          </div>
          <div class="form-group mb-3">
            <label for="class">Classes:</label>
            <select name="class" id="filter_class" class="form-control">
              <option value="" selected>- Choose class -</option>
              <?php
              foreach ($classes as $class) {
                $classId = $class['id'];
                $className = $class['name'];
                echo "<option value=\"$classId\">$className</option>";
              }
              ?>
            </select>
          </div>
          <div class="form-group mb-3">
            <label for="price">Price per day from:</label>
            <input type="number" name="price_from" id="filter_price_from" class="form-control" placeholder="Price per day">
          </div>
          <div class="form-group mb-3">
            <label for="price">Price per day to:</label>
            <input type="number" name="price_to" id="filter_price_to" class="form-control" placeholder="Price per day">
          </div>
          <button type="submit" class="btn btn-primary">Search</button>
        </form>
      </div>
    </div>
    <div id="carsList" class="row mt-5">

    </div>
  </div>

  <div class="modal fade" id="editCarModal" tabindex="-1" aria-labelledby="editCarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editCarModalLabel"></h5>
          <button type="button" class="btn-close" aria-label="Close" data-bs-dismiss="modal"></button>
        </div>
        <div class=" modal-body">
          <form action="index.php" id="carForm" method="GET" onsubmit="validateAndUpdateCarModal(event)" enctype="multipart/form-data">
            <input type="hidden" name="car_id" id="car_id">
            <div class="form-group mb-3">
              <label for="brand">Brands:</label>
              <select name="brand" id="brand" class="form-control" onchange="loadModels()">
                <option value="" disabled>- Choose brand -</option>
                <?php
                foreach ($brands as $brand) {
                  $brandId = $brand['id'];
                  $brandName = $brand['name'];
                  echo "<option value=\"$brandId\">$brandName</option>";
                }
                ?>
              </select>
              <small class="text-danger input-error d-none" id="error-brand_id"></small>
            </div>
            <div class="form-group mb-3">
              <label for="model">Models:</label>
              <select name="model" id="model" class="form-control"> </select>
              <small class="text-danger input-error d-none" id="error-model_id"></small>
            </div>
            <div class="form-group mb-3">
              <label for="class">Classes:</label>
              <select name="class" id="class" class="form-control">
                <option value="" disabled>- Choose class -</option>
                <?php
                foreach ($classes as $class) {
                  $classId = $class['id'];
                  $className = $class['name'];
                  echo "<option value=\"$classId\">$className</option>";
                }
                ?>
              </select>
              <small class="text-danger input-error d-none" id="error-class_id"></small>
            </div>
            <div class="form-group mb-3">
              <label for="register_number">Register number:</label>
              <input type="text" name="register_number" id="register_number" class="form-control" placeholder="Register number">
              <small class="text-danger input-error d-none" id="error-register_number"></small>
            </div>
            <div class="form-group mb-3">
              <label for="production_year">Production year:</label>
              <input type="number" name="production_year" id="production_year" class="form-control" placeholder="Register number">
              <small class="text-danger input-error d-none" id="error-production_year"></small>
            </div>
            <div class="form-group mb-3">
              <label for="price">Price per day:</label>
              <input type="number" name="price" id="price" class="form-control" placeholder="Price per day">
              <small class="text-danger input-error d-none" id="error-price_per_day"></small>
            </div>
            <div class="form-group mb-3">
              <label for="photos">Change car photos (uploaded photos will overwrite existing):</label>
              <input type="file" id="photos" name="files[]" multiple class="form-control">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button id="closeCarUpdateModalBtn" type="button" data-bs-dismiss="modal" class="btn btn-secondary">Close</button>
          <button type="submit" form="carForm" class="btn btn-primary">Update car</button>
        </div>
      </div>
    </div>
  </div>

  <script src="./cars/app.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    var editModal = document.getElementById("editCarModal");
    editModal.addEventListener("show.bs.modal", async function(event) {
      var button = event.relatedTarget;
      var car = JSON.parse(button.getAttribute("data-bs-car"));
      document.getElementById('car_id').value = car.id;
      document.getElementById('brand').value = car.brand_id;
      await loadModels();
      document.getElementById('model').value = car.model_id;
      document.getElementById('class').value = car.class_id;
      document.getElementById('register_number').value = car.register_number;
      document.getElementById('production_year').value = car.production_year;
      document.getElementById('price').value = car.price_per_day;

    });

    function hideMessage() {
      document.getElementById("message_div").style.display = "none";
    }

    editModal.addEventListener("hide.bs.modal", async function(event) {
      clearUpdateCarModal();
      setTimeout(hideMessage, 2000);
    });

    var url = window.location.href;
    var getExists = false;
    if (url.indexOf('?message=') != -1) getExists = true;

    if (getExists) {
      updateMessage.classList.remove("d-none");
      setTimeout(hideMessage, 2000);
    }
  </script>
</body>

</html>