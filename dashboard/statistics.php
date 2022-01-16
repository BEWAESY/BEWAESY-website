<?php
    session_start();

    include "../files/php/config/config.php";
    include "../files/php/config/sql.php";

    // Check if user is logged in, if not, redirect to login
    if (!isset($_SESSION["userid"])) {
        header("Location: ".$filePath."login?redirect=dashboard");
        die("Bitte zuerst <a href='".$filePath."login'>einloggen</a>");
    }

    $page = "statistics";

    
    // Get data
    // Get system data
    $statement = $pdo->prepare("SELECT id, name FROM systems WHERE userid = :userid");
    $result = $statement->execute(array("userid" => $_SESSION["userid"]));
    $systems = $statement->fetchAll();

    // Get the logs for the systems from the last 7 days
    foreach ($systems as $systemKey => $singleSystem) {
        $statement = $pdo->prepare("SELECT seconds, timestamp FROM systemlog WHERE systemid = :systemid AND timestamp >= DATE(NOW()) - INTERVAL 14 DAY");
        $result = $statement->execute(array("systemid" => $singleSystem["id"]));
        $systemLogs = $statement->fetchAll();

        // Append systemLogs to Systems
        $systems[$systemKey]["logs"] = $systemLogs;
    }
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BEWÄSY</title>
    <link rel="icon" type="image/x-icon" href="../files/images/logo.svg">

    <link href="../files/addons/bootstrap.min.css" rel="stylesheet">
    <link href="../files/css/dashboard.css" rel="stylesheet">
    <link href="../files/css/statistics.css" rel="stylesheet">
</head>
<body>
    <?php include "../files/php/templates/nav.php" ?>

    <!--<div class="d-flex flex-column flex-shrink-0 p-3 bg-light" style="width: 280px;">-->
    <div class="dashboard-container">
        <?php include "../files/php/templates/dashboard-nav.php"; ?>

        <div class="main main-statistics">
            <div class="card text-center">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link disabled" id="gießzeiten-tab" data-bs-toggle="tab" data-bs-target="#gießzeiten" type="button" role="tab" aria-controls="gießzeiten" aria-selected="true">Gießzeiten</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="ereignisse-tab" data-bs-toggle="tab" data-bs-target="#ereignisse" type="button" role="tab" aria-controls="ereignisse" aria-selected="false">Letzte Ereignisse</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link disabled" id="sensordaten-tab" data-bs-toggle="tab" data-bs-target="#sensordaten" type="button" role="tab" aria-controls="sonsordaten" aria-selected="false">Sensordaten der Pflanze</button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade" id="gießzeiten" role="tabpanel" aria-labelledby="gießzeiten-tab">
                            <!-- Gießzeiten -->
                            <canvas class="my-4 w-100" id="myChart" width="900" height="380"></canvas>
                        </div>

                        <div class="tab-pane fade show active" id="ereignisse" role="tabpanel" aria-labelledby="ereignisse-tab">
                            <p style="text-align: left">Gießzeiten der letzten 14 Tage</p>

                            <?php
                                foreach ($systems as $systemKey => $singleSystem) {
                                    // Get needed values
                                    $systemName = htmlspecialchars($singleSystem["name"]);
                                    

                                    echo <<<END
                                        <h2 class="mt-3 mb-3" style="text-align: left">$systemName</h2>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Timestamp</th>
                                                        <th scope="col" style="width: 50%;">Gießzeit (in s)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                    END;

                                    // Insert data into empty table
                                    foreach ($singleSystem["logs"] as $singleSystemLog) {
                                        // Get needed values
                                        $logTimestamp = strtotime($singleSystemLog["timestamp"]." UTC");
                                        $logInLocalTime = date("d.m.Y H:i:s", $logTimestamp);
                                        $logDuration = $singleSystemLog["seconds"];


                                        echo <<<END
                                                    <tr>
                                                        <td>$logInLocalTime</td>
                                                        <td>$logDuration</td>
                                                    </tr>
                                        END;
                                    }

                                    // Insert bottom of table
                                    echo <<<END
                                                </tbody>
                                            </table>
                                        </div>
                                    END;
                                }
                            ?>
                        </div>

                        <div class="tab-pane fade" id="sensordaten" role="tabpanel" aria-labelledby="sensordaten-tab">
                            <!-- Sensordaten der Pflanze -->
                            Sensordaten der Pflanze
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../files/addons/bootstrap.bundle.min.js"></script>
    <script src="../files/Chart.min.js"></script>
    <script src="../files/js/statistics.js"></script>
</body>
</html>
