<?php $is_admin = $_SESSION['user']['is_admin']; ?>


<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light " style="background-color:#ffffff;">
    <!-- Container wrapper -->
    <div class="container">
        <div class="navbar-nav me-auto mb-2 mb-lg-0">
            <a class="nav-item nav-link" href="/car_rental/index.php">Cars</a>
            <a class="nav-item nav-link" href="/car_rental/reservations/index.php"><?= $is_admin ? "Reservations" : "My reservations" ?></a>
            <?= $is_admin ? "
                <a class=\"nav-item nav-link \" href=\"/car_rental/models/index.php\">Models</a>
                <a class=\"nav-item nav-link\" href=\"/car_rental/brands/index.php\">Brands</a>
                <a class=\"nav-item nav-link\" href=\"/car_rental/countries/index.php\">Countries</a>
                " : "";
            ?>
        </div>
        <div class="navbar-nav d-flex flex-row me-1">
            <?= $is_admin ?
                "<a class=\"nav-item nav-link \" href=\"/car_rental/administration/index.php\">Administration</a>"
                : "<a class=\"nav-item nav-link \" href=\"/car_rental/users/index.php?id=" . $_SESSION['user']['id'] . "\">Profile</a>"; ?>
            <a class="nav-item nav-link" href="/car_rental/backend/auth/login.php">Logout</a>
        </div>
    </div>
</nav>