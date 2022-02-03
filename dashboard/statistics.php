<?php
    session_start();

    include "../files/php/config/config.php";
    include "../files/php/config/sql.php";

    $page = "statistics";

    // Check authentication
    include "../files/php/templates/check-authentication.php";

    
    // Get data
    // Get system data
    $statement = $pdo->prepare("SELECT id, name FROM systems WHERE userid = :userid");
    $result = $statement->execute(array("userid" => $_SESSION["userid"]));
    $systems = $statement->fetchAll();

    // Get the logs for the systems from the last 14 days
    foreach ($systems as $systemKey => $singleSystem) {
        $statement = $pdo->prepare("SELECT seconds, timestamp FROM systemlog WHERE systemid = :systemid ORDER BY timestamp desc LIMIT 5");
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

    <div class="dashboard-container">
        <?php include "../files/php/templates/dashboard-nav.php"; ?>

        <div class="main main-statistics">
            <div class="card">
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
                            <?php
                                foreach ($systems as $systemKey => $singleSystem) {
                                    // Get needed values
                                    $systemId = $singleSystem["id"];
                                    $systemName = htmlspecialchars($singleSystem["name"]);
                                    

                                    echo <<<END
                                        <h2 class="mt-3 mb-3">$systemName</h2>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Timestamp</th>
                                                        <th scope="col" style="width: 50%;">Gießzeit (in s)</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tbody$systemId">

                                                </tbody>
                                            </table>
                                        </div>
                                        <div><button id="moreDataButton$systemId" type="button" class="btn btn-outline-secondary shadow-none" onclick="loadAdditionalLogData($systemId)" style="display: none;">Mehr laden</button></div>
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
    <script src="../files/addons/jquery-3.6.0.min.js"></script>
    <script src="../files/addons/Chart.min.js"></script>
    <script src="../files/js/statistics.js"></script>

    <script>
        <?php
            //echo("var initialData = [$logData]")

            // Call function to create systems
            foreach ($systems as $systemKey => $singleSystem) {
                $systemId = $singleSystem["id"];

                echo("// System $systemId\n\n");

                // Skip if there is no log data
                if (empty($singleSystem["logs"])) {
                    echo("// No Logs for System $systemId\n\n");
                    break;
                }

                // Show more data button if all events are there
                if (count($singleSystem["logs"]) >= 5) echo("// Show more data button\n$('#moreDataButton$systemId').show();");


                echo("// Logs\n");
                foreach ($singleSystem["logs"] as $singleSystemLog) {
                    $data = json_encode($singleSystemLog);

                    // Get data
                    $seconds = $singleSystemLog["seconds"];

                    $logTimestamp = strtotime($singleSystemLog["timestamp"]." GMT+0100");
                    $logInLocalTime = json_encode(date("d.m.Y H:i", $logTimestamp));

                    echo("addLog($logInLocalTime, $seconds, $systemId);\n");

                    //echo("insertInitialData($data, $systemId);\n");
                }
                echo("\n\n");
            }
        ?>
    </script>
</body>
</html>
