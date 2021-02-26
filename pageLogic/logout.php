<?php
    session_start();
    session_unset();
    session_destroy();
    header("Location: http://localhost/project_secure_applications/login.php");
?>