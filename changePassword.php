<?php
    require_once("pageLogic/changePassword.php");
?>
<html>
<head>
    <title>Change Password</title>
    <link rel="stylesheet" href="static/css/newCssLoginRegister.css">
</head>
<body>   
    <form action="" method="GET">
        <h1> Change Password</h1>
            <div class = "inset">
                <p> 
                    <label for = "password"> Current Password</label>
                    <input type="password" id="currentPassword" name="currentPassword" placeholder="Password" autofocus/>
                
                    <label for = "password"> New Password</label>
                    <input type="password" id="newPassword" name="newPassword" placeholder="Password" />

                    <label for = "password"> Password</label>
                    <input type="password" id="password" name="confirmPassword" placeholder="Password" />

                    <input type="hidden" name="token" value="<?= $_SESSION['CSRFToken'] ?>" />

                </p>
            </div>
            <p class = "p-container">             
                <input type="submit" name ="go" id = "go" value="Change Password">
            </p>
    </form>
</body>
</html>
