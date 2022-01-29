<?php
    session_start();
    include "../files/php/config/config.php";
    include "../files/php/config/sql.php";

    $page = "dashboard";

    // Check authentication
    include "../files/php/templates/check-authentication.php";

    // Get relevant data
    $statement = $pdo->prepare("SELECT * FROM systems WHERE userid = :userid");
    $result = $statement->execute(array("userid" => $_SESSION["userid"]));
    $systems = $statement->fetchAll();

    // Get trigger data
    foreach ($systems as $systemKey => $singleSystem) {
        $statement = $pdo->prepare("SELECT * FROM wateringevents WHERE systemid = :systemid");
        $result = $statement->execute(array("systemid" => $singleSystem["id"]));
        $systemTriggers[$systemKey] = $statement->fetchAll();
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
</head>
<body>
    <?php include "../files/php/templates/nav.php" ?>

    <div class="dashboard-container">
        <?php include "../files/php/templates/dashboard-nav.php"; ?>

        <div class="main">
            <div class="accordion" id="accordionSystems">
                <!-- Insert Systems with PHP -->
                <?php
                    foreach ($systems as $systemKey => $singleSystem) {
                        // Get needed values
                        $id = $singleSystem["id"];
                        $name = htmlspecialchars($singleSystem["name"]);
                        $cooldown = htmlspecialchars($singleSystem["cooldown"]);
                        $maxSeconds = htmlspecialchars($singleSystem["maxSeconds"]);

                        echo <<<END
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="systems-heading$id">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#systems-collapse$id" aria-expanded="true" aria-controls="systems-collapse$id">
                                    $name
                                </button>
                                </h2>
                                <div id="systems-collapse$id" class="accordion-collapse collapse" aria-labelledby="systems-heading$id" data-bs-parent="#accordionSystems">
                                    <div class="accordion-body">
                                        <button type="button" class="btn btn-secondary" style="float: right;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
                                                <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
                                                <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
                                            </svg>
                                        </button>

                                        <div id="automatic" class="mt-3">
                                            <h2 class="mb-3">Automatikmodus (Comming Soon)</h2>
                                            <select id="selectPlant$id" class="form-select" aria-label="Default select example">
                                                <option selected>Werte manuell eingeben</option>
                                                <option value="1" disabled>Pflanzenart 1</option>
                                                <option value="2" disabled>Pflanzenart 2</option>
                                                <option value="3" disabled>Pflanzenart 3</option>
                                            </select>
                                        </div>
            
                                        <hr class="mt-4">
            
                                        <form id="$id">
                                            <h2 class="mb-3">Einstellungen</h2>
            
                                            <label for="cooldown" class="form-label">Cooldown (in Sekunden)</label>
                                            <input type="number" id="cooldown$id" class="form-control" aria-describedby="cooldownHelpBlock" value="$cooldown" min="0">
                                            <div id="passwordHelpBlock" class="form-text">
                                                0 eintragen für keinen Cooldown
                                            </div>
            
                                            <label for="maxSeconds" class="form-label mt-3">Max. Sekunden / Tag</label>
                                            <input type="number" id="maxSeconds$id" class="form-control" aria-describedby="maxSecondsHelpBlock" value="$maxSeconds" min="0">
                                            <div id="maxSecondsHelpBlock" class="form-text">
                                                0 eintragen für kein Maximum
                                            </div>
            
                                            <hr class="mt-4">
            
                                            <h2 class="mt-3 mb-3">Auslöser</h2>

                                            <div id="addTriggers$id">
                                                <!-- Triggers are added by JS -->
                                            </div>

                                            <button type="button" onclick="addTrigger('', $id);" class="btn btn-secondary">Neuen Auslöser hinzufügen</button>
            
                                            <hr>
            
                                            <div class="d-grid">
                                                <input type="submit" value="Speichern" class="btn btn-primary btn-lg" style="float: right">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        END;
                    }

                ?>






                <!--<div class="accordion-item">
                    <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                        System 1
                    </button>
                    </h2>
                    <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
                        <div class="accordion-body">
                            <div id="automatic">
                                <h2 class="mb-3">Automatikmodus</h2>
                                <select id="selectPlant" class="form-select" aria-label="Default select example">
                                    <option selected>Werte manuell eingeben</option>
                                    <option value="1">Pflanzenart 1</option>
                                    <option value="2">Pflanzenart 2</option>
                                    <option value="3">Pflanzenart 3</option>
                                </select>
                            </div>

                            <hr class="mt-4">

                            <form>
                                <h2 class="mb-3">Einstellungen</h2>

                                <label for="cooldown" class="form-label">Cooldown (in Sekunden)</label>
                                <input type="number" id="cooldown" class="form-control" aria-describedby="cooldownHelpBlock" value="0" min="0">
                                <div id="passwordHelpBlock" class="form-text">
                                    0 eintragen für keinen Cooldown
                                </div>

                                <label for="maxSeconds" class="form-label mt-3">Max. Sekunden / Tag</label>
                                <input type="number" id="maxSeconds" class="form-control" aria-describedby="maxSecondsHelpBlock" value="0" min="0">
                                <div id="maxSecondsHelpBlock" class="form-text">
                                    0 eintragen für kein Maximum
                                </div>

                                <hr class="mt-4">

                                <h2 class="mt-3 mb-3">Auslöser</h2>

                                <div class="card mb-3">
                                    <div class="card-body trigger-body">
                                        <div id="trigger0" class="trigger-card">
                                            <b>Wenn</b>
                                            <select id="changeTrigger0" onchange="changeTrigger(0, 1);" class="form-select" aria-label="Auslöser auswählen">
                                                <option selected></option>
                                                <option value="time">Uhrzeit</option>
                                                <option value="temperature">Temperatur</option>
                                                <option value="humidity">Luftfeuchtigkeit</option>
                                            </select>

                                            <div id="triggerSecondInput0"></div>

                                            <div id="triggerThirdInput0"></div>

                                            <div id="unit1_0"></div>
                                        </div>

                                        <b>dann:</b>

                                        <div id="action0" class="trigger-action">
                                            gieße für <input id="waterSeconds0" type="number" class="form-control" min="1"> Sekunden
                                        </div>

                                        <button type="button" class="btn btn-outline-danger btn-sm">Entfernen</button>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-secondary">Neuen Auslöser hinzufügen</button>

                                <hr>

                                <div class="d-grid">
                                    <button type="button" class="btn btn-primary btn-lg" style="float: right">Speichern</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>-->
            </div>
        </div>
    </div>


    <script src="../files/addons/jquery-3.6.0.min.js"></script>
    <script src="../files/js/dashboard.js"></script>
    <script>
        <?php
            // Call function to create triggers
            foreach ($systems as $systemKey => $singleSystem) {
                $systemId = $singleSystem["id"];

                echo("// System $systemId\n");
                echo("triggerIds[$systemId] = [];\n\n");

                echo("// Triggers\n");
                foreach ($systemTriggers[$systemKey] as $triggerKey => $trigger) {
                    $data = json_encode($trigger);

                    echo("create_db_triggers($data, $systemId);\n");
                }
                echo("\n\n");
            }
        ?>
    </script>
    <script src="../files/addons/bootstrap.bundle.min.js"></script>
</body>
</html>
