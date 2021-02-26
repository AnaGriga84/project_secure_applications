<?php
    require_once("pageLogic/dashboard.php");
?>

<!--Inspired by: https://www.backlinkn.com/2020/11/landing-page-tutorial-make-webpage.html -->
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="static/css/dashboardCss.css">
</head>
<body>
<div class="smooth">
  <header>
    <h3>By Ana Griga</h3>
    <ul>
      <li><a href="changePassword.php">Change Password</a></li>
      <li><a href="logout.php">Logout</a></li>
      <li><a href="logs.php">Logs Table for Admin</a></li>
    </ul>
  </header>
  <section id="one">
    <div class="content parallax">
      <div> 
        <svg class="title">
          <text x="0" y="60"><?= "Welcome " . $_SESSION['username']; ?></text>
          <path d="M 0 66 50 61"></path>
        </svg>
        <p class="lead">
        This is a project requested by the lecturer Richard Butler from the Institute of Technology Carlow for the Secure Application Development subject as part of the continuous assessments.
        It demonstrates the development of a secure authentication application using PHP, XAMPP and MySql.
        </p>
    
        <p class="lead">
        This is the landing page after the user is securely logged in. 
        On this page, the user can change their password, and they can securely log out.
        </p>
      </div>
      <div class="blur">
        <form><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR6rNeDvr0bO0Daf65jGDV3aV3h_jl8bMaEQQ&usqp=CAU"/>
        </form>
      </div>
    </div>
  </section>
</div>
</body>
</html>
