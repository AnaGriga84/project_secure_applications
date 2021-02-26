<?php
    $conn = mysqli_connect("localhost","root","");
    mysqli_select_db($conn,"project_secure_applications");
    $query = mysqli_query($conn,"SELECT * FROM `users`");
    if($query == FALSE) 
    {
        $sql = "
        SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
START TRANSACTION;
SET time_zone = '+00:00';
CREATE DATABASE IF NOT EXISTS `project_secure_applications` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `project_secure_applications`;

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `action` varchar(30) NOT NULL,
  `ipAddress` varchar(256) NOT NULL,
  `datetime` datetime NOT NULL DEFAULT current_timestamp(),
  `outcome` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `logs` (`id`, `action`, `ipAddress`, `datetime`, `outcome`) VALUES
(3, 'Login Failed', '::1', '2021-02-17 17:46:16', 'Error Message Displayed'),
(4, 'Login Success', '::1', '2021-02-17 18:06:15', 'Created session variables and rotated SessionID'),
(5, 'Login Failed', '::1', '2021-02-17 18:06:25', 'Error Message Displayed'),
(6, 'Login Success', '::1', '2021-02-17 18:06:36', 'Created session variables and rotated SessionID'),
(7, 'Login Success', '::1', '2021-02-17 18:14:27', 'Created session variables and rotated SessionID'),
(8, 'Login Success', '::1', '2021-02-17 18:16:30', 'Created session variables and rotated SessionID'),
(9, 'Login Success', '::1', '2021-02-17 18:30:57', 'Created session variables and rotated SessionID'),
(10, 'Login Success', '::1', '2021-02-17 18:41:02', 'Created session variables and rotated SessionID'),
(11, 'Login Success', '::1', '2021-02-17 18:48:54', 'Created session variables and rotated SessionID'),
(12, 'Login Failed', '::1', '2021-02-17 19:04:45', 'Error Message Displayed'),
(13, 'Login Failed', '::1', '2021-02-17 19:04:51', 'Error Message Displayed'),
(14, 'Login Failed', '::1', '2021-02-17 19:04:56', 'Error Message Displayed'),
(15, 'Login Failed', '::1', '2021-02-17 19:05:00', 'Error Message Displayed'),
(16, 'Login Failed', '::1', '2021-02-17 19:05:04', 'Error Message Displayed'),
(17, 'Login Success', '::1', '2021-02-17 19:11:07', 'Created session variables and rotated SessionID');

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(200) NOT NULL,
  `salt` varchar(128) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `users` (`id`, `username`, `password`, `salt`, `admin`) VALUES
(109, 'Ana', 'f8d8ecdb626067344460627689a9296d38fec68c477f9f9910bead5e1ee7b7c44a3668bce13b61f4c297dc045a25bac72cbe7cd738d8056ec832857191c111f1', 'c0c15302faa260dc0bf1f7d87b3accd211bb8b529445e80fab36d373bfea001ef5f925153d4e53e7fbf9b72dac93d1fdd065613cf5a9935f88f39059720d40cf', 0),
(110, 'Admin', 'fc822d71386e8f690a41d888bf0f26b8ea89e262ec2c5e600fb8e298323c627f269b2cd443a54da3bd926a1976535212a6a8b9a5836f36e08cc792be28f27be5', '11e457e53a0e8cc8ac88e4a2380dc86fe7d227be4baa6ea1f4912202375361a51ef39313920b0153b9ce48deab93a6020e25d62d281802de74dbca61e79d6472', 1);


ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`) USING BTREE;


ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;
COMMIT; 
        ";
        mysqli_multi_query($conn,$sql);
    }


    session_start();
    require_once("functions/database.php");
    require_once("functions/secure.php");
    require_once("functions/log.php");

    if(!isset($_SESSION['lockoutAttempts'])) 
    {
        $_SESSION['lockoutAttempts'] = 0;
    }


    if($_SERVER["REQUEST_METHOD"] == "POST") 
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


        $username = Filter(trim($_POST['username']));
        $password = Filter(trim($_POST['password']));

        $errorMessage = "The username $username and password could not be authenticated at the moment";

        if(empty($username) || empty($password)) 
        {
            $_SESSION['lockoutAttempts']++;
            createLog($conn,"Login Failed","Error Message Displayed");
            die($errorMessage);
        }

        // Check if user exists
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$username);
        if(!$stmt->execute()) 
        {
            die($errorMessage);
        }
        $results = $stmt->get_result()->fetch_assoc();
        if(empty($results)) 
        {
            $_SESSION['lockoutAttempts']++;
            createLog($conn,"Login Failed","Error Message Displayed");
            die($errorMessage);
        }

        // Get salt for username
        $sql = "SELECT salt FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$username);
        if(!$stmt->execute()) 
        {
            die($errorMessage);
        }
        $results = $stmt->get_result()->fetch_assoc();
        $salt = $results['salt'];
        $hashedPassword = hash("sha512",$salt . $password);

        // Check password matches
        $sql = "SELECT * FROM users WHERE password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$hashedPassword);
        if(!$stmt->execute()) 
        {
            die($errorMessage);
        }
        $results = $stmt->get_result()->fetch_assoc();
        if(empty($results)) 
        { // Posted password is incorrect
            $_SESSION['lockoutAttempts']++;
            createLog($conn,"Login Failed","Error Message Displayed");
            die($errorMessage);
        }

        unset($_SESSION['lockoutAttempts']);
        createLog($conn,"Login Success","Created session variables and rotated SessionID");
        $_SESSION['username'] = $username;

        $newDate = new DateTime(); // Create one hour inactivity
        $newDate->modify("+1 Hour");
        $_SESSION['oneHourInactivity'] = $newDate;

        session_regenerate_id();//change the session id
        header("Location: http://localhost/project_secure_applications/dashboard.php");
    }
?>
