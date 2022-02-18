<?php
    session_start();
    include "../files/php/config/config.php";
    include "../files/php/config/sql.php";

    $page = "dashboard";

    // Check authentication
    include "../files/php/templates/check-authentication.php";

    // Get relevant data
    $statement = $pdo->prepare("SELECT * FROM systems WHERE userid = :userid ORDER BY created");
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
                        $name = htmlspecialchars(htmlspecialchars_decode($singleSystem["name"]));
                        $cooldown = htmlspecialchars(htmlspecialchars_decode($singleSystem["cooldown"]));
                        $maxSeconds = htmlspecialchars(htmlspecialchars_decode($singleSystem["maxSeconds"]));

                        echo <<<END
                            <div id="accordion$id" class="accordion-item">
                                <h2 class="accordion-header" id="systems-heading$id">
                                <button id="systemAccordion$id" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#systems-collapse$id" aria-expanded="true" aria-controls="systems-collapse$id">
                                    <div id="systemAccordionName$id">$name</div> <span id="systemBadge$id" class="badge ms-2"></span>
                                </button>
                                </h2>
                                <div id="systems-collapse$id" class="accordion-collapse collapse" aria-labelledby="systems-heading$id" data-bs-parent="#accordionSystems">
                                    <div class="accordion-body">
                                        <button type="button" id="settingsButton$id" class="btn btn-secondary shadow-none" style="float: right;" data-bs-toggle="modal" data-bs-target="#settingsModal" data-bs-systemId="$id" data-bs-systemName="$name" data-bs-cooldown="$cooldown" data-bs-maxSeconds="$maxSeconds">
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
            
                                        <form id="$id" class="systemForm">
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
                            <form id="saveSettingsForm" data-bs-systemId="">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="settingsModalLabel">Einstellungen <b id="settingsModalLabelName">[NAME]</b></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <label for="settingsInputName" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="settingsInputName" required>

                                    <label for="settingsInputCooldown" class="form-label mt-3">Cooldown (in Sekunden)</label>
                                    <input type="number" id="settingsInputCooldown" class="form-control" aria-describedby="cooldownHelpBlock" min="0" required>
                                    <div id="cooldownHelpBlock" class="form-text">
                                        0 eintragen für keinen Cooldown
                                    </div>
    
                                    <label for="settingsInputmaxSeconds" class="form-label mt-2">Max. Sekunden / Tag</label>
                                    <input type="number" id="settingsInputMaxSeconds" class="form-control" aria-describedby="maxSecondsHelpBlock" min="0" required>
                                    <div id="maxSecondsHelpBlock" class="form-text">
                                        0 eintragen für kein Maximum
                                    </div>

                                    <hr class="mt-4 mb-3">

                                    <h3 class="mb-3">System verbinden</h3>
                                    <button id="apiKeyPasswordModalTriggerButton" type="button" class="btn btn-secondary" data-bs-target="#apiKeyPasswordModal" data-bs-toggle="modal">
                                        Details abrufen
                                    </button>

                                    <hr class="mt-4 mb-3">

                                    <h3 class="mb-3">Bewässerungssystem löschen</h3>
                                    <button id="settingsDeleteModal" type="button" class="btn btn-danger" data-bs-target="#deleteSystemModal" data-bs-toggle="modal">
                                        System löschen
                                    </button>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Abbrechen</button>
                                    <div><button type="submit" id="settingsSubmitButton" class="btn btn-primary">Speichern</button></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- API Key password modal -->
                <div class="modal fade" id="apiKeyPasswordModal" tabindex="-1" aria-labelledby="apiKeyPasswordModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="apiKeyPasswordModalForm" data-bs-systemId="">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="apiKeyPasswordModal">API-Key für <b id="apiKeyPasswordModalLabelName">[NAME]</b> abrufen</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

                                    <label for="apiKeyPassword" class="form-label">Passwort für <b id="apiKeyPasswordEmail"></b>:</label>
                                    <input type="password" class="form-control" id="apiKeyPassword" required autofocus>
                                    <div class="invalid-feedback">Falsches Passwort</div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Abbrechen</button>
                                    <input type="submit" id="apiKeyPasswordModalSubmitButton" class="btn btn-primary" value="Weiter">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- API KEY display data -->
                <div class="modal fade" id="apiKeyDataModal" tabindex="-1" aria-labelledby="apiKeyDataModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="apiKeyDataModal">API-Key für <b id="apiKeyDataModalLabelName">[NAME]</b> abrufen</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">

                                <div class="alert alert-primary d-flex align-items-center" role="alert">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                                        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                                    </svg>
                                    <div>Informationen, wie du dein Bewässerungssystem hinzufügen kannst, findest du <a href="../help/add-system" target="blank">hier</a></div>
                                </div>


                                ID: <p id="apiKeyDataModalIdPlaceholder" style="display: inline"></p><br>
                                API-Key: <p id="apiKeyDataModalApiKeyPlaceholder" style="display: inline"></p><br>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Schließen</button>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Delete System Modal -->
                <div class="modal fade" id="deleteSystemModal" tabindex="-1" aria-labelledby="deleteSystemModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="deleteSystemForm" data-bs-systemId="" autocomplete="off">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteSystemModalLabel"><b id="deleteSystemModalLabelName">[NAME]</b> löschen</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p id="deleteSystemBodyText" class="mb-2">Soll dieses Bewässerungssystem wirklich gelöscht werden? Dabei gehen auch alle Statistiken über dieses System verloren. Gib bitte <b id="deleteSystemBodyTextName">[NAME]</b> ein und klicke anschließend auf "Löschen":</p>

                                    <input type="text" class="form-control" id="deleteSystemNameInput" placeholder="[NAME]" required autofocus>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Abbrechen</button>
                                    <div><button type="submit" id="deleteSystemSubmitButton" class="btn btn-danger" disabled>Löschen</div>
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
            $userEmail = $_SESSION["userEmail"];
            echo("var userEmail = '$userEmail';");


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
