<?php
    session_start();
    include "../php/config/sql.php";

    // Check if user is authenticated
    if (!isset($_SESSION["userid"])) {
        die("401");
    }


    // Get data
    $statement = $pdo->prepare("SELECT * FROM systemlog WHERE systemid = :systemid ORDER BY timestamp DESC LIMIT 3");
    $result = $statement->execute(array("systemid" => 1));
    $log = $statement->fetchAll();
    
    die(json_encode($log));
?>
