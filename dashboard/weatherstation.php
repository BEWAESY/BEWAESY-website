<?php
    session_start();
    include "../files/php/config/config.php";
    include "../files/php/config/sql.php";

    $page = "weatherStation";

    // Check authentication
    include "../files/php/templates/check-authentication.php";

    // Get system data
    $statement = $pdo->prepare("SELECT name, temperature, humidity, lastCall FROM systems WHERE userid = :userid ORDER BY created");
    $result = $statement->execute(array("userid" => $_SESSION["userid"]));
    $systems = $statement->fetchAll();
?>


<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wetterstation - BEWÄSY</title>

    <link rel="icon" type="image/x-icon" href="../files/images/logo.svg">

    <link href="../files/addons/bootstrap.min.css" rel="stylesheet">
    <link href="../files/css/dashboard.css" rel="stylesheet">
</head>
<body>
    <?php include "../files/php/templates/nav.php" ?>


    <div class="dashboard-container">
        <?php include "../files/php/templates/dashboard-nav.php"; ?>

        <div class="main">
            <?php
                foreach ($systems as $systemKey => $singleSystem) {
                    // Get needed values
                    $name = htmlspecialchars(htmlspecialchars_decode($singleSystem["name"]));
                    $temperature = htmlspecialchars(htmlspecialchars_decode($singleSystem["temperature"]));
                    $humidity = htmlspecialchars(htmlspecialchars_decode($singleSystem["humidity"]));
                    $date = htmlspecialchars(htmlspecialchars_decode($singleSystem["lastCall"]));

                    echo <<<END
                        <div class="card" style="margin: 20px;">
                            <div class="card-header">
                                <h2 class="h4 mb-0">$name</h2>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="d-flex col-md-6 mb-0 justify-content-center align-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="#2596be" class="bi bi-thermometer-half" viewBox="0 0 16 16">
                                            <path d="M9.5 12.5a1.5 1.5 0 1 1-2-1.415V6.5a.5.5 0 0 1 1 0v4.585a1.5 1.5 0 0 1 1 1.415z"/>
                                            <path d="M5.5 2.5a2.5 2.5 0 0 1 5 0v7.55a3.5 3.5 0 1 1-5 0V2.5zM8 1a1.5 1.5 0 0 0-1.5 1.5v7.987l-.167.15a2.5 2.5 0 1 0 3.333 0l-.166-.15V2.5A1.5 1.5 0 0 0 8 1z"/>
                                        </svg>

                                        <p class="mb-0">Temperatur: $temperature °C</p>
                                    </div>

                                    <div class="d-flex col-md-6 mb-0 justify-content-center align-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="#ff5050" class="bi bi-droplet" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M7.21.8C7.69.295 8 0 8 0c.109.363.234.708.371 1.038.812 1.946 2.073 3.35 3.197 4.6C12.878 7.096 14 8.345 14 10a6 6 0 0 1-12 0C2 6.668 5.58 2.517 7.21.8zm.413 1.021A31.25 31.25 0 0 0 5.794 3.99c-.726.95-1.436 2.008-1.96 3.07C3.304 8.133 3 9.138 3 10a5 5 0 0 0 10 0c0-1.201-.796-2.157-2.181-3.7l-.03-.032C9.75 5.11 8.5 3.72 7.623 1.82z"/>
                                            <path fill-rule="evenodd" d="M4.553 7.776c.82-1.641 1.717-2.753 2.093-3.13l.708.708c-.29.29-1.128 1.311-1.907 2.87l-.894-.448z"/>
                                        </svg>

                                        <p class="mb-0 ms-2">Luftfeuchtigkeit: $humidity %</p>
                                    </div>
                                </div>
                                <p class="mt-2 mb-0">Daten von: $date</p>
                            </div>
                        </div>
                    END;
                }
            ?>
        </div>
    </div>


    <script src="../files/addons/jquery-3.6.0.min.js"></script>
    <script src="../files/addons/bootstrap.bundle.min.js"></script>

    <script src="../files/js/add-system.js"></script>
</body>
</html>
