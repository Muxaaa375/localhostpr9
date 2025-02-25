<?php
    $server = "localhost"; 
    $user = "root";
    $password = ""; 
    $database = "pr9";

    $mysqli = new mysqli($server, $user, $password, $database);

    if ($mysqli->connect_error) {
        die("Connection failed: " . $mysqli->connect_error);
    } else {
        echo "Успешное подключение к базе данных!";
    }
?>
