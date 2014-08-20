<?php
    $dbconnection = "127.0.0.1";
    $dblogin="root";
    $dbpass="";
    $dbname="square_coordinates";
    $mysqli = new mysqli($dbconnection,$dblogin,$dbpass);
    mysqli_select_db($mysqli, $dbname);
    if ($mysqli->connect_errno) {
        echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }
?>