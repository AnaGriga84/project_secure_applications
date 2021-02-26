<?php
    require_once("functions/secure.php");

    function createLog($conn,$action,$outcome) 
    {
        $ipAddress = Filter(trim($_SERVER['REMOTE_ADDR']));
        $sql = "INSERT INTO `logs` (action,ipAddress,outcome) VALUES (?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss",$action,$ipAddress,$outcome);
        $stmt->execute();
    } 
?>