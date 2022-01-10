<?php
    session_start();
    include "../php/config/sql.php";

    // Check if user is authenticated
    if (!isset($_SESSION["userid"])) {
        die("401");
    }

    $postData = json_decode(@$_POST[0], true);

    $systemData = @$postData[0];
    $triggerData = @$postData[1];

    // System data
    $systemid = htmlspecialchars(@$systemData["id"]);
    $cooldown = htmlspecialchars(@$systemData["cooldown"]);
    $maxSeconds = htmlspecialchars(@$systemData["maxSeconds"]);

    // Check if all system data exists
    if (empty($systemid) || empty($cooldown) || empty($maxSeconds)) die("missingData");

    // Check if system exists in DB and user has privileges to write to it
    $statement = $pdo->prepare("SELECT * FROM systems WHERE id = :id");
    $result = $statement->execute(array("id" => $systemid));
    $systemDbData = $statement->fetch();

    if ($systemDbData === false || $systemDbData["userid"] != $_SESSION["userid"]) die("403");

    // Write system data to DB
    $statement = $pdo->prepare("UPDATE systems SET cooldown = :cooldown, maxSeconds = :maxSeconds WHERE id = :id");
    $statement->execute(array("id" => $systemid, "cooldown" => $cooldown, "maxSeconds" => $maxSeconds));


    // TRIGGERS
    // Get trigger data
    $statement = $pdo->prepare("SELECT * FROM wateringevents WHERE systemid = :systemid");
    $result = $statement->execute(array("systemid" => $systemid));
    $triggerRows = $statement->fetchAll();

    // Handle updated and deleted triggers
    foreach ($triggerRows as $triggerKey => $triggerRow) {
        // Check if trigger exists in user input
        $hit = array_search($triggerRow["id"], array_column($triggerData, "id"));

        if (is_numeric($hit)) {
            $insertTriggerData = $triggerData[$hit];

            // Get needed values
            $triggerid = $triggerRow["id"];
            $eventTrigger = htmlspecialchars($insertTriggerData["eventTrigger"]);
            $triggerValue1 = htmlspecialchars($insertTriggerData["triggerValue1"]);
            $triggerValue2 = htmlspecialchars(@$insertTriggerData["triggerValue2"]);
            $triggerRange = htmlspecialchars(@$insertTriggerData["triggerRange"]);
            $seconds = htmlspecialchars($insertTriggerData["seconds"]);

            // Update trigger in DB
            $statement = $pdo->prepare("UPDATE wateringevents SET eventTrigger = :eventTrigger, triggerValue1 = :triggerValue1, triggerValue2 = :triggerValue2, triggerRange = :triggerRange, seconds = :seconds WHERE id = :id");
            $statement->execute(array("eventTrigger" => $eventTrigger, "triggerValue1" => $triggerValue1, "triggerValue2" => $triggerValue2, "triggerRange" => $triggerRange, "seconds" => $seconds, "id" => $triggerid));
        } else {
            // Trigger doesn't exist in client's input, delete it from DB
            $statement = $pdo->prepare("DELETE FROM wateringevents WHERE id = :id");
            $statement->execute(array("id" => $triggerRow["id"]));
        }
    }


    // Add new triggers
    foreach ($triggerData as $triggerKey => $singleTrigger) {
        // Check if trigger is new
        if ($singleTrigger["newTrigger"] != "true") continue;

        // Get values
        $eventTrigger = htmlspecialchars($singleTrigger["eventTrigger"]);
        $triggerValue1 = htmlspecialchars($singleTrigger["triggerValue1"]);
        $triggerValue2 = htmlspecialchars(@$singleTrigger["triggerValue2"]);
        $triggerRange = htmlspecialchars(@$singleTrigger["triggerRange"]);
        $seconds = htmlspecialchars($singleTrigger["seconds"]);        

        // Add trigger to DB
        $statement = $pdo->prepare("INSERT INTO wateringevents (systemid, eventTrigger, triggerValue1, triggerValue2, triggerRange, seconds) VALUES (:systemid, :eventTrigger, :triggerValue1, :triggerValue2, :triggerRange, :seconds)");
        $statement->execute(array("systemid" => $systemid, "eventTrigger" => $eventTrigger, "triggerValue1" => $triggerValue1, "triggerValue2" => $triggerValue2, "triggerRange" => $triggerRange, "seconds" => $seconds));
    }
    

    die("Success!");
?>
