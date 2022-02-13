<?php
    session_start();
    include "../php/config/sql.php";

    // Check if user is authenticated
    if (!isset($_SESSION["userid"])) {
        die("401");
    }

    $mondayDateInput = @$_POST["mondayDate"];

    // Check if data is there
    if (empty($mondayDateInput)) die("400");

    // Convert date into a format usable by MySQL
    $mondayDate = date("Y-m-d H:i:s", strtotime($mondayDateInput));

    
    // Get systems in user account
    $statement = $pdo->prepare("SELECT * FROM systems WHERE userid = :userid");
    $result = $statement->execute(array("userid" => $_SESSION["userid"]));
    $systems = $statement->fetchAll();


    $output = array();

    // Loop through each system and get log data
    foreach ($systems as $systemkey => $singleSystem) {
        // Prepare array
        $output += [$singleSystem["id"] => array("name" => $singleSystem["name"], "eventCounterData" => array())];

        // Get logs for this system
        $statement = $pdo->prepare("SELECT * FROM systemlog WHERE systemid = :systemid AND timestamp >= Date(:mondayDate) AND timestamp <= DATE_ADD(:mondayDate, INTERVAL 7 DAY)");
        $result = $statement->execute(array("systemid" => $singleSystem["id"], "mondayDate" => $mondayDate));
        $systemlog = $statement->fetchAll();

        // Loop through each day of the week
        for ($addValue = 0; $addValue <= 6; $addValue++) {
            $checkDate = date("Y-m-d", strtotime($mondayDate . " +$addValue day"));
            $day = date("D", strtotime($mondayDate . " +$addValue day"));

            $singleDay = array_filter($systemlog, function($var) use($checkDate) {
                return(substr($var["timestamp"], 0, -9) == $checkDate);
            });

            $output[$singleSystem["id"]]["eventCounterData"] += [$day => count($singleDay)];
        }
    }

    print_r(json_encode(["Success", $output]));
?>
