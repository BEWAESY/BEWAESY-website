<?php
    session_start();
    include "../php/config/sql.php";

    // Check if user is authenticated
    if (!isset($_SESSION["userid"])) {
        die("401");
    }

    // Get required data
    $systemid = @$_POST["systemId"];
    $name = htmlspecialchars(@$_POST["name"]);
    $cooldown = htmlspecialchars(@$_POST["cooldown"]);
    $maxSeconds = htmlspecialchars(@$_POST["maxSeconds"]);

    // Check if all required variables exist
    if (empty($systemid) || empty($name)) die("400");


    // Check if user is allowed to change this data
    $statement = $pdo->prepare("SELECT * FROM systems WHERE id = :id");
    $result = $statement->execute(array("id" => $systemid));
    $systemDbData = $statement->fetch();

    if ($systemDbData === false || $systemDbData["userid"] != $_SESSION["userid"]) die("403");


    // Update data in DB
    $statement = $pdo->prepare("UPDATE systems SET name = :name, cooldown = :cooldown, maxSeconds = :maxSeconds WHERE id = :id");
    $statement->execute(array("id" => $systemid, "name" => $name, "cooldown" => $cooldown, "maxSeconds" => $maxSeconds));


    die("Success");
?>
