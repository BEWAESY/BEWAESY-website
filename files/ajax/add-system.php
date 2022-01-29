<?php
    session_start();
    include "../php/config/sql.php";

    // Check if user is authenticated
    if (!isset($_SESSION["userid"])) {
        die("401");
    }

    $newSystemName = htmlspecialchars(@$_POST["name"]);


    // Check if needed data does exist
    if (empty($newSystemName)) die("400");


    // Generate random id
    while (true) {
        $systemId = rand(1, 100000);

        // Check if id allready exists
        $statement = $pdo->prepare("SELECT * FROM systems WHERE id = :id");
        $result = $statement->execute(array("id" => $systemId));
        $checkResult = $statement->fetch();

        if ($checkResult === false) break;
    }


    // Generate random API key
    $apiKey = md5(random_bytes(500));

    // Add new system
    $statement = $pdo->prepare("INSERT INTO systems (id, userid, name, maxSeconds, cooldown, apiKey) VALUES (?, ?, ?, ?, ?, ?)");
    $statement->execute(array($systemId, $_SESSION["userid"], $newSystemName, 0, 0, $apiKey));

    $clientData = array($systemId, $newSystemName, $apiKey);

    die(json_encode($clientData));
?>
