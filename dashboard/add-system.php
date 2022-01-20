<?php
    session_start();
    include "../files/php/config/config.php";

    $page = "add-system";

    // Check authentication
    include "../files/php/templates/check-authentication.php";
?>


<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System hinzufügen - BEWÄSY</title>

    <link rel="icon" type="image/x-icon" href="../files/images/logo.svg">

    <link href="../files/addons/bootstrap.min.css" rel="stylesheet">
    <link href="../files/css/dashboard.css" rel="stylesheet">
</head>
<body>
    <?php include "../files/php/templates/nav.php" ?>


    <div class="dashboard-container">
        <?php include "../files/php/templates/dashboard-nav.php"; ?>

        <div class="main">
            <div class="card" style="margin: 20px">
                <div class="card-body">
                    <h1 class="mb-3">Ein neues Bewässerungssystem hinzufügen</h1>

                    <form>
                        <div class="mb-3">
                            <label for="nameInput" class="form-label">Name:</label>
                            <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="nameHelp" required>
                            <div id="nameHelp" class="form-text">Der Name deines neuen Bewässerungssystems</div>
                        </div>

                        <button type="submit" class="btn btn-primary">Absenden</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="../files/addons/jquery-3.6.0.min.js"></script>
    <script src="../files/addons/bootstrap.bundle.min.js"></script>
</body>
</html>
