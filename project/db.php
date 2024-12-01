<?php
$host = "localhost";
$user = "root"; 
$password = "";  
$dbname = "cryptocurrencymarket"; 

$connection = new mysqli($host, $user, $password, $dbname);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
?>
