
<?php
    function Filter($value)
    {
        $specialChars = Array("&", "<", ">", "(", ")","{", "}", "[", "]", '"', "'", ";", "\\");
        $replacedChars = Array("&amp", "&lt", "&gt", "&#40", "&#41", "&#123", "&#125", "&#91", "&#93", "&#34", "&#39", "&#59", "&#92");
    
        $cleanedString = str_replace($specialChars, $replacedChars, $value);
        return $cleanedString;
        echo "";
        echo "";
    }
    //validate function
    function validatePassword($password, $confirm) 
    {
        // minimum 8 character
        // minimum 1 number
        // minimum 1 uppercase letter
        // minimum 1 lowercase letter
        // 1 special character (!, £, @)

        if($password !== $confirm || strlen($password) < 8 || strlen($confirm) < 8) 
        {
            return false;
        }

        // Inspired by
        // https://stackoverflow.com/questions/9587907/how-to-check-if-string-has-at-least-one-letter-number-and-special-character-in

        $containsLowercase = preg_match('/[a-z]/',$password);
        $containsUppercase = preg_match('/[A-Z]/',$password);
        $containsNumbers = preg_match('/\d/',$password);
        $containsSpecial = preg_match('/[^a-zA-Z\d]/',$password);

        return $containsLowercase && $containsUppercase && $containsNumbers && $containsSpecial;
    }

    function isLoggedIn() 
    {
        if(!isset($_SESSION['username'])) 
        {
            header("Location: http://localhost/project_secure_applications/login.php");
            exit;
        }
    }

    function isAdmin($conn) 
    {
        isLoggedIn();
        $username = $_SESSION['username'];
        $sql = "SELECT * FROM `users` WHERE username = ? AND admin = 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$username);
        if(!$stmt->execute()) 
        {
            die($errorMessage);
        }
        $results = $stmt->get_result()->fetch_assoc();
        if(empty($results)) 
        {
            header("Location: http://localhost/project_secure_applications/login.php");
            exit;
        }

    }

    function tenMinuteInactivity() 
    {
        if(isset($_SESSION['tenMinuteInactivity']) && $_SESSION['tenMinuteInactivity'] < new DateTime()) {
            header('Location: http://localhost/project_secure_applications/logout.php');
            exit;
        }

        $newDate = new DateTime();
        $newDate->modify("+10 Minutes");
        $_SESSION['tenMinuteInactivity'] = $newDate;
    } 

    function oneHourInactivity() 
    {
        if($_SESSION['oneHourInactivity'] < new DateTime()) 
        {
            header('Location: http://localhost/project_secure_applications/logout.php');
            exit;
        }
    } 
?>