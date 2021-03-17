<?php
    session_start();
    require_once("functions/secure.php");
    isLoggedIn();
    tenMinuteInactivity();
    oneHourInactivity();
?>