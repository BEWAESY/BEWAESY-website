<?php
    session_start();
    include "../php/config/sql.php";

    // Check if user is authenticated
    if (!isset($_SESSION["userid"])) {
        die("401");
    }

    // Get required data
    $systemId = @$_POST["systemId"];

    // Check if all required variables exist
    if (empty($systemId)) die("400");


    // Check if user is allowed to delete this system
    $statement = $pdo->prepare("SELECT * FROM systems WHERE id = :id");
    $result = $statement->execute(array("id" => $systemId));
    $systemDbData = $statement->fetch();

    if ($systemDbData === false || $systemDbData["userid"] != $_SESSION["userid"]) die(403);


    // Delete all events associated with this system
    $statement = $pdo->prepare("DELETE FROM wateringevents WHERE systemid = :systemid");
    $statement->execute(array("systemid" => $systemId));

    // Delete all logs from this system
    $statement = $pdo->prepare("DELETE FROM systemlog WHERE systemid = :systemid");
    $statement->execute(array("systemid" => $systemId));

    // Delete the system
    $statement = $pdo->prepare("DELETE FROM systems WHERE id = :systemid");
    $statement->execute(array("systemid" => $systemId));
    
    die("Success");
?>
