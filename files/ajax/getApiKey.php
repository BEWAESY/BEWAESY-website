<?php
    session_start();
    include "../php/config/sql.php";

    // Check if user is authenticated
    if (!isset($_SESSION["userid"])) {
        die("401");
    }

    // Get required data
    $systemId = @$_POST["systemId"];
    $password = @$_POST["password"];

    // Check if required values are present
    if (empty($systemId) || empty($password)) die("400");


    // Check if user has access to this system and get API-key
    $statement = $pdo->prepare("SELECT * FROM systems WHERE id = :id");
    $result = $statement->execute(array("id" => $systemId));
    $systemDbData = $statement->fetch();

    if ($systemDbData === false || $systemDbData["userid"] != $_SESSION["userid"]) die("403");


    // Check if user password is correct
    $statement = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $result = $statement->execute(array("id" => $_SESSION["userid"]));
    $user = $statement->fetch();

    if ($user === false || !password_verify($password, $user["password"])) {
        die("wrongPassword");
    }

    die(json_encode(["Success", $systemDbData["name"], $systemDbData["apiKey"]]));
?>
