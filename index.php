<?php include "files/php/config/config.php" ?>
<?php session_start(); ?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BEWÄSY</title>
    <link rel="icon" type="image/x-icon" href="files/images/logo.svg">

    <link href="files/css/main.css" rel="stylesheet">
    <link href="files/addons/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include "files/php/templates/nav.php" ?>

    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner" style="height: 500px;">
            <div class="carousel-item active">
                <img src="files/images/slider/Blumentopf1.png" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="files/images/slider/Technikum.jpg" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="files/images/slider/3D-Drucker.jpg" class="d-block w-100" alt="...">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <?php //include "files/php/templates/footer.php" ?>

    <script src="files/addons/bootstrap.bundle.min.js"></script>
</body>
</html>
