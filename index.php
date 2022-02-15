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

    <link href="files/addons/bootstrap.min.css" rel="stylesheet">
    <link href="files/css/main.css" rel="stylesheet">
    <link href="files/css/landingPage.css" rel="stylesheet">
</head>
<body>
    <?php include "files/php/templates/nav.php" ?>

    <div id="carouselExampleIndicators" class="carousel slide mb-5" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner" style="max-height: 500px;">
            <div class="carousel-item active">
                <img src="files/images/slider/3D-Drucker.jpg" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="files/images/slider/Technikum.jpg" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="files/images/slider/Blumentopf1.png" class="d-block w-100" alt="...">
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


    <div class="container">
        <!-- Version 1 -->
        <div class="row justify-content-center mb-5">
            <div class="col-md-6 align-self-center">
                <h3>Bewässerungssystem V1</h3>
                <p>Unsere erste Version eines Bewässerungssystems, eine Box mit einem Raspberry Pi und einem Relay, die neben den Blumentopf gestellt wird.</p>
            </div>
            <div class="col-md-6">
                <img src="files/images/slider/System_v1.jpg" alt="..." class="w-100">
            </div>
        </div>

        
        <!-- Version 2 -->
        <div class="row justify-content-center mb-5">
            <div class="col-md-6 order-md-2 align-self-center">
                <h3>Bewässerungssystem V2</h3>
                <p>Die zweite Version unseres Bewässerungssystems. Unser Wunsch für dieses System war, das Bewässerungssystem möglichst unsichtbar zu machen. Die Lösung: Ein Blumentopf, in dem bereits alle Komponenten verbaut sind, von der Pumpe bis zum Raspberry Pi.</p>
            </div>
            <div class="col-md-6 order-md-1">
                <img src="files/images/slider/Blumentopf1.png" alt="..." class="w-100">
            </div>
        </div>

        <hr class="mb-4" style="height: 2px">

        <!-- Software + GitHub -->
        <div class="row justify-content-center mb-5">
            <div class="col-md-6 align-self-center">
                <h3>Unser Code</h3>
                <p>Unsere Software, von der Website über eine API bis zu den Programmen für die einzelnen Bewässerungssysteme, bringt unser ganzes Projekt zum Laufen. Wir hosten unseren Code frei verfügbar auf GitHub.</p>
                <a href="https://github.com/BEWAESY" class="btn btn-outline-dark" target="blank">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-github" viewBox="0 0 16 16">
                        <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.012 8.012 0 0 0 16 8c0-4.42-3.58-8-8-8z"/>
                    </svg>
                    GitHub
                </a>
            </div>
            <div class="col-md-6">
                <img src="files/images/slider/Code.jpg" alt="..." class="w-100">
            </div>
        </div>

        <hr class="mb-4" style="height: 2px">

        <!-- Technikum -->
        <div class="row justify-content-center mb-5">
            <div class="col-md-9 order-md-2 align-self-center">
                <h3>Technikum</h3>
                <p>Dieses Projekt ist im Technikum am Schubart-Gymansium entstanden.</p>
                <a href="https://sg-aalen.de/technikum" class="btn btn-bwstiftung" target="blank">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M14 2.5a.5.5 0 0 0-.5-.5h-6a.5.5 0 0 0 0 1h4.793L2.146 13.146a.5.5 0 0 0 .708.708L13 3.707V8.5a.5.5 0 0 0 1 0v-6z"/>
                    </svg> 
                    Schulwebsite
                </a>
            </div>
            <div class="col-md-3 order-md-1">
                <img src="files/images/slider/LogoSGTechnikumFarbe.svg" alt="..." class="w-100">
            </div>
        </div>

        <!-- BW-Stiftung -->
        <div class="row justify-content-center">
            <div class="col-md-9 order-md-2 align-self-center">
                <h3>Baden-Württemberg Stiftung</h3>
                <p>Unser Projekt wurde im Rahmen des Programms "mikro makro mint" von der Baden-Württemberg Stiftung gefördert.</p>
                <a href="https://www.bwstiftung.de/de/bereiche-programme/gesellschaft-kultur/mikro-makro-mint" class="btn btn-bwstiftung" target="blank">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M14 2.5a.5.5 0 0 0-.5-.5h-6a.5.5 0 0 0 0 1h4.793L2.146 13.146a.5.5 0 0 0 .708.708L13 3.707V8.5a.5.5 0 0 0 1 0v-6z"/>
                    </svg>    
                    Weitere Infos
                </a>
            </div>
            <div class="col-md-3 order-md-1">
                <img src="files/images/slider/BWS_Logo_Standard_rgb.jpg" alt="..." class="w-100">
            </div>
        </div>
    </div>

    <?php include "files/php/templates/footer.php" ?>

    <script src="files/addons/bootstrap.bundle.min.js"></script>
</body>
</html>
