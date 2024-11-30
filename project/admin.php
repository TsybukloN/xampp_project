<?php
session_start();
global $connection;
include('db.php');

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: user.php");
    exit;
}

function display_table_links($tables) {
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li><a href='admin.php?table=$table'>$table</a></li>";
    }
    echo "</ul>";
}

$tables = ['Users', 'Exchanges', 'Cryptocurrencies', 'Prices', 'Portfolios', 'Transactions'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
</head>
<body>
    <h1>Admin Panel - Choose a Table</h1>
    
    <?php display_table_links($tables); ?>

</body>
</html>

<?php mysqli_close($connection); ?>
