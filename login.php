<?php
    require_once("pageLogic/login.php");
?>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="static/css/newCssLoginRegister.css">
</head>
<body>   
    <form action="" method="POST">
        <h1> Login Form </h1>
            <div class = "inset">
                <p> 
                    <label for = "username"> Username</label>
                    <input type="text" id="username" name="username" placeholder="Username" autofocus/>
                
                    <label for = "password"> Password</label>
                    <input type="password" id="password" name="password" placeholder="Password" />
                </p>
            </div>
            <p class = "p-container">             
                <span>Not registered? <a href="register.php">Register</span>
                <input type="submit" name ="go" id = "go" value="Login">
            </p>
    </form>
</body>
</html>








