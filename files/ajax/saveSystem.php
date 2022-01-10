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

    die("Success!");
?>
