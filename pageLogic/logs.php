<?php
    session_start();

    require_once("functions/database.php");
    require_once("functions/secure.php");
    isAdmin($conn);
    tenMinuteInactivity();
    oneHourInactivity();
    $sql = "SELECT * from `logs`";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $results = $stmt->get_result();
?>