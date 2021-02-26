<?php
    require_once("pageLogic/register.php");
?>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="static/css/newCssLoginRegister.css">
</head>
<body>   
    <form action="" method="POST">
        <h1> Registration Form </h1>
            <div class = "inset">
                <p> 
                    <label for = "username"> Username</label>
                    <input type="text" id="username" name="username" placeholder="Username" autofocus/>
                
                    <label for = "password"> Password</label>
                    <input type="password" id="password" name="password" placeholder="Password" />

                    <label for = "password"> Password</label>
                    <input type="password" id="password" name="confirmPassword" placeholder="Password" />
                </p>
            </div>
            <p class = "p-container">             
                <span>Already have an account? <a href="login.php">Login</span>
                <input type="submit" name ="go" id = "go" value="Register">
            </p>
    </form>
</body>
</html>

<!-- https://codepen.io/ardiaaan/pen/RBdjpX-->










