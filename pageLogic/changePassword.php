<?php
    session_start();
    require_once("functions/secure.php");
    require_once("functions/database.php");
    isLoggedIn();
    tenMinuteInactivity();
    oneHourInactivity();

    if(isset($_GET['currentPassword']) && isset($_GET['newPassword']) && isset($_GET['confirmPassword']) && isset($_GET['token'])) 
    {
        $currentPassword = Filter(trim($_GET['currentPassword']));
        $newPassword = Filter(trim($_GET['newPassword']));
        $confirmPassword = Filter(trim($_GET['confirmPassword']));
        $csrfToken = Filter(trim($_GET['token'])); 

        $errorMessage = "The password details are unable to be verified.";

        if($csrfToken != $_SESSION['CSRFToken']) 
        {
            $_SESSION['CSRFToken'] = bin2hex(random_bytes(64));
            die($errorMessage);
        }

        if(empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) 
        {
            $_SESSION['CSRFToken'] = bin2hex(random_bytes(64));
            die($errorMessage);
        }

        if(!validatePassword($newPassword,$confirmPassword)) 
        {
            $_SESSION['CSRFToken'] = bin2hex(random_bytes(64));
            die($errorMessage);
        }

        // Check if current password is valid
        // First. Get Salt
        $sql = "SELECT salt FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$_SESSION['username']);
        if(!$stmt->execute()) 
        {
            die($errorMessage);
        }
        $results = $stmt->get_result()->fetch_assoc();
        $salt = $results['salt'];
        $hashedPassword = hash('sha512',$salt . $currentPassword);

        // Second. Check password against database
        $sql = "SELECT * FROM users WHERE password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$hashedPassword);
        if(!$stmt->execute()) 
        {
            die($errorMessage);
        }
        $results = $stmt->get_result()->fetch_assoc();
        if(empty($results)) 
        {
            $_SESSION['CSRFToken'] = bin2hex(random_bytes(64));
            die($errorMessage); // current password is incorrect
        }

        $salt = bin2hex(random_bytes(64));
        $hashedPassword = hash('sha512',$salt . $newPassword);

        $sql = "UPDATE `users` SET password = ?, salt = ? WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss",$hashedPassword,$salt,$_SESSION['username']);
        if(!$stmt->execute()) 
        {
            die($errorMessage);
        }
        header("Location: http://localhost/project_secure_applications/logout.php");
    }
    $_SESSION['CSRFToken'] = bin2hex(random_bytes(64));
?>