<?php
session_start();
include '../backend/connect.php';
include '../backend/functions.php';
authorize();
if (isset($_GET) && $_GET != []) {
  if (isset($_GET['brand'])) {
    $brand_id = $_GET['brand'];
  }
  if (isset($_GET['model'])) {
    $model_id = $_GET['model'];
  }
  if (isset($_GET['class'])) {
    $class_id = $_GET['class'];
  }
  $price_from = $_GET['price_from'];
  $price_to = $_GET['price_to'];
  $date_from = readInput($_GET, 'date_from');
  $date_to = readInput($_GET, 'date_to');
}
$is_admin = $_SESSION['user']['is_admin'];

?>


<div class="col-8 offset-2">
  <h1 class="mb-3"><?= $is_admin ? "Car list" : "Choose your car" ?></h1>
  <?php
  $sql = "SELECT
cars.id,
cars.register_number,
cars.price_per_day,
cars.production_year,
car_models.id as model_id,
car_models.name as model_name,
brands.id as brand_id,
brands.name as brand_name,
car_classes.id as class_id,
car_classes.name as class_name,
(SELECT path from photos WHERE car_id=cars.id LIMIT 1) AS main_photo
from cars
JOIN car_models on model_id=car_models.id
JOIN car_classes on class_id=car_classes.id
JOIN brands on cars.brand_id=brands.id 
WHERE 1=1
";
  if (!$is_admin) {
    if (isset($brand_id) && $brand_id != "") {
      $sql .= "AND cars.brand_id = $brand_id ";
    }
    if (isset($model_id) && $model_id != "") {
      $sql .= "AND cars.model_id = $model_id ";
    }
    if (isset($class_id) && $class_id != "") {
      $sql .= "AND cars.class_id = $class_id ";
    }
    if ($price_from != "") {
      $sql .= "AND (cars.price_per_day > $price_from) ";
    }
    if ($price_to != "") {
      $sql .= "AND (cars.price_per_day < $price_to) ";
    }
    $sql .= "AND NOT EXISTS
  (SELECT * from reservations
  WHERE car_id=cars.id
  AND reservations.is_cancelled = false
  AND ((date_from BETWEEN \"$date_from\"
  AND \"$date_to\")
  OR (date_to BETWEEN \"$date_from\" AND \"$date_to\")))";
  }

  $res = mysqli_query($db_conn, $sql);

  while ($car = mysqli_fetch_assoc($res)) {
    $carJSON = json_encode($car);
    $carId = $car['id'];
    $carRegisterNumber = $car['register_number'];
    $carPricePerDay = $car['price_per_day'];
    $carProductionYear = $car['production_year'];
    $brandName = $car['brand_name'];
    $className = $car['class_name'];
    $modelName = $car['model_name'];
    $photo = $car['main_photo'];
    if ($is_admin) {
      $dynamicContent = "
      <form action\"index.php\" method=\"GET\" onsubmit=\"deleteCar(event)\">
                    <input type=\"hidden\" name=\"car_id\" value=\"$carId\">
                    <div class=\"d-grid gap-3\">
                      <button type=\"button\" class=\"btn btn-outline-success btn-block mb-3\" data-bs-toggle=\"modal\" data-bs-target=\"#editCarModal\" data-bs-car='$carJSON'>Edit</button>
                      <button type=\"submit\" class=\"btn btn-outline-danger btn-block\">Delete</button>
                    </div>
                  </form>
      ";
      $dynamicPrice = "
      <div class=\"col-md-1 text-center \">
                                          <div class=\"card-body\">
                                            <p><small class=\"text-muted\">Daily:</small></p>
                                            <h6 >$carPricePerDay €</h6>
                                          </div>
                                      </div>";
    } else {

      $dynamicContent = "
    <form action=\"reservations/save.php\" method=\"POST\">
    <input type=\"hidden\" name=\"car\" value=\"$carId\">
    <input type=\"hidden\" name=\"date_from\" value=\"$date_from\">
    <input type=\"hidden\" name=\"date_to\" value=\"$date_to\">
    <input type=\"hidden\" name=\"price\" value=\"" . totalPrice($carPricePerDay, $date_from, $date_to) . "\">
    <button class=\"btn btn-outline-warning btn-block\">Reserve car</button>
    </form>
  ";
      $dynamicPrice = "<div class=\"col-md-1 text-center \">
        <div class=\"card-body\">
          <p><small class=\"text-muted\">Daily:</small></p>
          <h6 >$carPricePerDay €</h6>
          <p><small class=\"text-muted\">Total:</small></p>
          <h6>" . totalPrice($carPricePerDay, $date_from, $date_to) . "€</h6>
        </div>
    </div>";
    }
    echo "<div class=\"card mb-3\">
                                    <div class=\"row g-0\">
                                      <div class=\"fill col-md-3\">
                                        <img src=\"$photo\"
                                        class=\"img-fluid rounded-start\" alt=\"...\">
                                      </div>
                                      <div class=\"col-md-6\">
                                          <div class=\"card-body\">
                                            <h3 class=\"card-title\">$className</h3>
                                            <h4><a href='cars/show.php?id=$carId'>$brandName $modelName</a></h4>
                                            <p class=\"card-text\">Production year: $carProductionYear</p>
                                            <p class=\"card-text\">Register number: $carRegisterNumber</p>
                                          </div>
                                      </div>
                                      $dynamicPrice
                                      
                                      <div class=\"col-md-2 text-center\">
                                        <div class=\"card-body\">" . $dynamicContent .
      "</div>
                                      </div>
                                    </div>
                                  </div>
                                    ";
  }
  ?>
</div>
<?= $is_admin ? "<div class=\"col-2\">
            <a class=\"btn btn-primary btn-block\" href=\"cars/create.php\">Add new car</a>
            </div> " : "" ?>