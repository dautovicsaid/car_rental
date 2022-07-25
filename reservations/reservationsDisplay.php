
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once '../backend/connect.php';
include_once '../backend/functions.php';
$is_admin = $_SESSION["user"]["is_admin"];
$userId = $_SESSION["user"]["id"];

$sql = "SELECT
reservations.id,
DATE_FORMAT(date_from,'%Y-%m-%d') as date_from,
DATE_FORMAT(date_to, '%Y-%m-%d') as date_to,
reservations.total_price,
reservations.is_cancelled,
cars.id as car_id,
cars.price_per_day,
cars.production_year,
cars.register_number,
brands.name as brand_name,
car_models.name as model_name,
car_classes.name as class_name,
(SELECT path from photos WHERE car_id=cars.id LIMIT 1) AS main_photo
 FROM `reservations`
 JOIN cars ON reservations.car_id=cars.id
 JOIN brands ON cars.brand_id=brands.id
 JOIN car_models ON cars.model_id=car_models.id
 JOIN car_classes ON cars.class_id=car_classes.id
 ";

$is_admin ? $sql .= "" : $sql .= " WHERE is_cancelled = false AND user_id=$userId ";

$sql .= "ORDER BY date_from,date_to";

$res = mysqli_query($db_conn, $sql);
$reservationUserHistory = "";

while ($reservation = mysqli_fetch_assoc($res)) {
    $reservationId = $reservation["id"];
    $reservationTotalPrice = $reservation["total_price"];
    $carProductionYear = $reservation["production_year"];
    $carRegisterNumber = $reservation["register_number"];
    $carId = $reservation["car_id"];
    $carPrice = $reservation["price_per_day"];
    $dateFrom = $reservation["date_from"];
    $dateTo = $reservation["date_to"];
    $brandName = $reservation['brand_name'];
    $modelName = $reservation['model_name'];
    $carClass = $reservation["class_name"];
    $photo = $reservation['main_photo'];
    $reservationJSON = json_encode($reservation);
    $reservationGrid = "";
    if ($is_admin) {
        $is_cancelled = $reservation["is_cancelled"];
        $is_cancelled == 1 ?
            $cancelledText = "<p class=\"text-danger\"> Cancelled </p>"
            : $cancelledText = "";
        $dynamicContent = "
        <div class=\"col-md-2\">
            <div class=\"card-body text-center\">
                $cancelledText
            </div>
        </div>";
        $reservationGrid .= "
        <div class=\"card mb-3\">
            <div class=\"row g-0\">
                <div class=\"fill col-md-3\">
                    <img src=\"../$photo\"
                    class=\"img-fluid rounded-start\">
                </div>
                <div class=\"col-md-4\">
                    <div class=\"card-body\">
                    <h3 class=\"card-title\">$carClass</h3>
                    <h4><a href='../cars/show.php?id=$carId'>$brandName $modelName</a></h4>
                    <p class=\"card-text\">Production year: $carProductionYear</p>
                    <p class=\"card-text\">Register number: $carRegisterNumber</p>
                    </div>
                </div>
                <div class=\"col-md-3\">
                    <div class=\"card-body text-center\">
                        <div class=\"row\">
                            <div class=\"col-6\">
                                <p class=\"card-text\"><small class=\"text-muted\">Date from:</small> </p>
                                <h6>$dateFrom</h6>
                            </div>
                            <div class=\"col-6\">
                                <p class=\"card-text\"><small class=\"text-muted\">Date to:</small> </p>
                                <h6>$dateTo</h6>
                            </div>
                        </div>
                        <div class=\"row\">
                            <div class=\"col-6\">
                                <p class=\"card-text\"><small class=\"text-muted\">Daily:</small> </p>
                                <h6>$carPrice €</h6>
                            </div>
                            <div class=\"col-6\">
                                <p class=\"card-text\"><small class=\"text-muted\">Total:</small> </p>
                                <h6>$reservationTotalPrice €</h6>
                            </div>
                        </div>
                    </div>
                </div>
                " . $dynamicContent . "
            </div>
        </div>";
    } else {
        $dynamicContent = "
        <div class=\"col-md-2\">
            <div class=\"card-body text-center\">
                <div class=\"d-grid gap-3\">
                    <button class=\"btn btn-outline-success \" data-bs-toggle=\"modal\" data-bs-target=\"#editReservationModal\" data-bs-reservation='$reservationJSON'>Edit</button>
                    <button form=\"cancelForm\" class=\"btn btn-outline-danger \">Cancel</button>
                </div>
                <form action\"index.php\" id=\"cancelForm\" method=\"GET\" onsubmit=\"cancelReservation(event)\">
                    <input type=\"hidden\" name=\"reservation_id\" value=\"$reservationId\">
                </form>
            </div>
        </div>";
        if (strtotime($dateFrom) < strtotime("now")) {
            $reservationUserHistory .= "
            <div class=\"card mb-3\">
                <div class=\"row g-0\">
                    <div class=\"fill col-md-3\">
                        <img src=\"../$photo\"
                        class=\"img-fluid rounded-start\">
                    </div>
                    <div class=\"col-md-4\">
                        <div class=\"card-body\">
                        <h3 class=\"card-title\">$carClass</h3>
                        <h4><a href='../cars/show.php?id=$carId'>$brandName $modelName</a></h4>
                        <p class=\"card-text\">Production year: $carProductionYear</p>
                        <p class=\"card-text\">Register number: $carRegisterNumber</p>
                        </div>
                    </div>
                    <div class=\"col-md-3\">
                        <div class=\"card-body text-center\">
                            <div class=\"row\">
                                <div class=\"col-6\">
                                    <p class=\"card-text\"><small class=\"text-muted\">Date from:</small> </p>
                                    <h6>$dateFrom</h6>
                                </div>
                                <div class=\"col-6\">
                                    <p class=\"card-text\"><small class=\"text-muted\">Date to:</small> </p>
                                    <h6>$dateTo</h6>
                                </div>
                            </div>
                            <div class=\"row\">
                                
                                <div class=\"col-12 mt-5\">
                                    <h6><small class=\"text-muted\">Total: </small>$reservationTotalPrice €</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>";
        } else {
            $reservationGrid .= "
        <div class=\"card mb-3\">
            <div class=\"row g-0\">
                <div class=\"fill col-md-3\">
                    <img src=\"../$photo\"
                    class=\"img-fluid rounded-start\">
                </div>
                <div class=\"col-md-4\">
                    <div class=\"card-body\">
                    <h3 class=\"card-title\">$carClass</h3>
                    <h4><a href='../cars/show.php?id=$carId'>$brandName $modelName</a></h4>
                    <p class=\"card-text\">Production year: $carProductionYear</p>
                    <p class=\"card-text\">Register number: $carRegisterNumber</p>
                    </div>
                </div>
                <div class=\"col-md-3\">
                    <div class=\"card-body text-center\">
                        <div class=\"row\">
                            <div class=\"col-6\">
                                <p class=\"card-text\"><small class=\"text-muted\">Date from:</small> </p>
                                <h6>$dateFrom</h6>
                            </div>
                            <div class=\"col-6\">
                                <p class=\"card-text\"><small class=\"text-muted\">Date to:</small> </p>
                                <h6>$dateTo</h6>
                            </div>
                        </div>
                        <div class=\"row\">
                            <div class=\"col-6\">
                                <p class=\"card-text\"><small class=\"text-muted\">Daily:</small> </p>
                                <h6>$carPrice €</h6>
                            </div>
                            <div class=\"col-6\">
                                <p class=\"card-text\"><small class=\"text-muted\">Total:</small> </p>
                                <h6>$reservationTotalPrice €</h6>
                            </div>
                        </div>
                    </div>
                </div>
                " . $dynamicContent . "
            </div>
        </div>";
        }
    }

    echo $reservationGrid;
};


$isInReservationHistory = $reservationUserHistory != "" ? "<div class=\"mt-5\"><h3 class=\"mb-5\">Reservation history</h3>" . $reservationUserHistory . "</div>" : "";
echo !$is_admin ? $isInReservationHistory : "";

?>