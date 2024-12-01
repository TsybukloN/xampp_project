<?php
session_start();
global $connection;
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$table = $_GET['table'] ?? '';
$id = $_GET['id'] ?? '';

if (!$table || !$id) {
    die('Table or ID is missing.');
}

$query = "DELETE FROM $table WHERE id = $id";
$result = mysqli_query($connection, $query);

if ($result) {
    echo "Record deleted successfully.";
} else {
    echo "Error: " . mysqli_error($connection);
}

header('Location: dashboard.php');

mysqli_close($connection);
?>
