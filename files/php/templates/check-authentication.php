<?php
    // Check if user is logged in, if not, redirect to login
    if (!isset($_SESSION["userid"])) {
        header("Location: ".$filePath."login?redirect=".@$page);
        die("Bitte zuerst <a href='".$filePath."login'>einloggen</a>");
    }
?>
