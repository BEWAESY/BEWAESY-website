<?php
    session_start();
    include "../php/config/sql.php";

    // Check if user is authenticated
    if (!isset($_SESSION["userid"])) {
        die("401");
    }

    // Get required data
    $systemId = @$_POST["systemId"];
    $records = @$_POST["records"];

    // Check if all required variables exist
    if (empty($systemId) || empty($records)) die("400");


    // Check if user is allowed to get those details
    $statement = $pdo->prepare("SELECT * FROM systems WHERE id = :id");
    $result = $statement->execute(array("id" => $systemId));
    $systemDbData = $statement->fetch();

    if ($systemDbData === false || $systemDbData["userid"] != $_SESSION["userid"]) die("403");


    // Get next 5 records
    $statement = $pdo->prepare("SELECT seconds, timestamp FROM systemlog WHERE systemid = :systemid ORDER BY timestamp desc LIMIT 5 OFFSET :offset");
    $statement->bindValue(":systemid", $systemId);
    $statement->bindValue(":offset", (int) $records, PDO::PARAM_INT);
    $statement->execute();
    $systemDbData = $statement->fetchAll();

    die(json_encode($systemDbData));
?>
