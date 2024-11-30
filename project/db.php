<?
    global $connection;
    $connection = mysqli_connect('localhost', 'root', '', 'CryptocurrencyMarket');
    if(!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>
