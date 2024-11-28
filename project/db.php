<?php
    global $connection;
    $connection = mysqli_connect('localhost', 'root', '', 'lab_8');
    if($connection) {
        echo "Connected successfully" . "<br>";
    } else {
        die("Connection failed: " . mysqli_connect_error());
    }
?>