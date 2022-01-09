<?php include "../files/php/config/config.php" ?>
<?php include "../files/php/config/sql.php" ?>

<?php
    session_start();

    // Check if user is logged in, if not, redirect to login
    if (!isset($_SESSION["userid"])) {
        header("Location: ".$filePath."login?redirect=dashboard");
        die("Bitte zuerst <a href='".$filePath."login'>einloggen</a>");
    }

    $page = "dashboard";

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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="../files/css/dashboard.css" rel="stylesheet">
</head>
<body>
    <?php include "../files/php/templates/nav.php" ?>

    <!--<div class="d-flex flex-column flex-shrink-0 p-3 bg-light" style="width: 280px;">-->
    <div class="dashboard-container">
        <?php include "../files/php/templates/dashboard-nav.php"; ?>

        <div class="main">
            <div class="accordion" id="accordionPanelsStayOpenExample">
                <!-- Insert triggers with PHP -->
                <?php
                    foreach ($systems as $systemKey => $singleSystem) {
                        //print_r($singleSystem);

                        // Get needed values
                        $id = $singleSystem["id"];
                        $name = htmlspecialchars($singleSystem["name"]);
                        $cooldown = htmlspecialchars($singleSystem["cooldown"]);
                        $maxSeconds = htmlspecialchars($singleSystem["maxSeconds"]);

                        echo <<<END
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                    $name
                                </button>
                                </h2>
                                <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
                                    <div class="accordion-body">
                                        <div id="automatic">
                                            <h2 class="mb-3">Automatikmodus</h2>
                                            <select id="selectPlant$id" class="form-select" aria-label="Default select example">
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
                                            <input type="number" id="cooldown" class="form-control" aria-describedby="cooldownHelpBlock" value="$cooldown" min="0">
                                            <div id="passwordHelpBlock" class="form-text">
                                                0 eintragen für keinen Cooldown
                                            </div>
            
                                            <label for="maxSeconds" class="form-label mt-3">Max. Sekunden / Tag</label>
                                            <input type="number" id="maxSeconds" class="form-control" aria-describedby="maxSecondsHelpBlock" value="$maxSeconds" min="0">
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
                                                <button type="button" class="btn btn-primary btn-lg" style="float: right">Speichern</button>
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


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../files/js/dashboard.js"></script>
    <script>
        <?php
            // Call function to create triggers
            foreach ($systems as $systemKey => $singleSystem) {
                foreach ($systemTriggers[$systemKey] as $triggerKey => $trigger) {
                    $data = json_encode($trigger);
                    $systemId = $singleSystem["id"];

                    echo("createTriggers($data, $systemId); \n");
                }
            }
        ?>
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>
