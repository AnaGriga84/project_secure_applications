<?php
    define("DB_SERVER",'localhost');
    define("DB_USERNAME",'root');
    define("DB_PASSWORD",'');
    define("DB_DATABASE",'project_secure_applications');

    $conn = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);

    if($conn-> connect_error) 
    {
        die("Error connecting to database" . $conn->connect_error . "<br>");
    }
?>