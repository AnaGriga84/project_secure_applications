<?php
    session_start();
    require_once("functions/database.php");

    if(!isset($_SESSION['lockoutAttempts'])) 
    {
        $_SESSION['lockoutAttempts'] = 0;
    }

    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        if($_SESSION['lockoutAttempts'] == 5) 
        {
            $newDate = new DateTime();
            $newDate->modify("+3 minutes");
            $_SESSION['lockoutTime'] = $newDate;
            unset($_SESSION['lockoutAttempts']);
        }

        if(isset($_SESSION['lockoutTime'])) 
        {
            if($_SESSION['lockoutTime'] < new DateTime()) 
            {
                unset($_SESSION["lockoutTime"]);
            }
            else 
            {
                die("You are locked out.");
            }
        }

        require_once("functions/secure.php");

        $username = Filter(trim($_POST['username']));
        $password = Filter(trim($_POST['password']));
        $confirmPassword = Filter(trim($_POST['confirmPassword']));

        $errorMessage = "The username is unavailable and/or the password is incorrect";

        if(empty($username) || empty($password) || empty($confirmPassword)) 
        {
            $_SESSION['lockoutAttempts']++;
            die($errorMessage);
        }

        if(!validatePassword($password,$confirmPassword)) 
        {
            $_SESSION['lockoutAttempts']++;
            die($errorMessage);
        }

        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$username);
        if(!$stmt->execute()) 
        {
            die($errorMessage);
        }
        $results = $stmt->get_result()->fetch_assoc();
        if(!empty($results)) 
        { // Username already exists as a signed up user
            $_SESSION['lockoutAttempts']++;
            die($errorMessage);
        }

        $salt = bin2hex(random_bytes(64));
        $hashedPassword = hash("sha512",$salt . $password);

        $sql = "INSERT INTO users (username,password,salt) VALUES (?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss",$username,$hashedPassword,$salt);
        if(!$stmt->execute()) 
        {
            die($errorMessage);
        }

        unset($_SESSION['lockoutAttempts']);
        header("Location: http://localhost/project_secure_applications/login.php");
    }
?>
