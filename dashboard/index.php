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
                                        <button type="button" class="btn btn-secondary shadow-none" style="float: right;" data-bs-toggle="modal" data-bs-target="#settingsModal" data-bs-systemId="$id" data-bs-systemName="$name" data-bs-cooldown="$cooldown" data-bs-maxSeconds="$maxSeconds">
                                            Einstellungen
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

                <!-- Settings Modal -->
                <div class="modal fade" id="settingsModal" tabindex="-1" aria-labelledby="settingsModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form action=".">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="settingsModalLabel">Einstellungen System "[NAME]"</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <label for="settingsInputName" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="settingsInputName">

                                    <label for="settingsInputCooldown" class="form-label mt-3">Cooldown (in Sekunden)</label>
                                    <input type="number" id="settingsInputCooldown" class="form-control" aria-describedby="cooldownHelpBlock" min="0">
                                    <div id="cooldownHelpBlock" class="form-text">
                                        0 eintragen für keinen Cooldown
                                    </div>
    
                                    <label for="settingsInputmaxSeconds" class="form-label mt-2">Max. Sekunden / Tag</label>
                                    <input type="number" id="settingsInputMaxSeconds" class="form-control" aria-describedby="maxSecondsHelpBlock" min="0">
                                    <div id="maxSecondsHelpBlock" class="form-text">
                                        0 eintragen für kein Maximum
                                    </div>

                                    <hr class="mt-4 mb-2">

                                    <h3 class="mb-2">Bewässerungssystem löschen</h3>
                                    <button type="button" class="btn btn-danger">
                                        System löschen
                                    </button>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Abbrechen</button>
                                    <input type="submit" class="btn btn-primary" value="Speichern">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
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
