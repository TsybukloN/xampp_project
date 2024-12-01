<?php
session_start();
global $connection;
include('db.php');

if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
    header("Location: admin.php");
    exit;
}

$is_admin = isset($_SESSION['is_admin']) && $_SESSION['is_admin']; 

$table = isset($_GET['table']) ? $_GET['table'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : ''; 


function fetchTableData($table, $id) {
    global $connection;
    $query = "SELECT * FROM $table WHERE id = $id";
    return mysqli_query($connection, $query);
}


function displayEditForm($table, $data) {
    echo "<h2>Edit Record</h2>";
    echo "<form method='POST' action='user.php?table=$table&id={$data['id']}'>";
    foreach ($data as $key => $value) {
        if ($key != 'id') {
            echo "<label for='$key'>$key</label><br>";
            echo "<input type='text' id='$key' name='$key' value='" . htmlspecialchars($value) . "'><br>";
        }
    }
    echo "<input type='submit' value='Update'>";
    echo "</form>";
}

function updateRecord($table, $id, $columns, $values) {
    global $connection;
    $set_values = [];
    foreach ($columns as $key => $column) {
        $set_values[] = "$column = '" . mysqli_real_escape_string($connection, $values[$key]) . "'";
    }
    $set_clause = implode(", ", $set_values);
    $query = "UPDATE $table SET $set_clause WHERE id = $id";
    return mysqli_query($connection, $query);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_admin) {
    
    $columns = array_keys($_POST);
    $values = array_values($_POST);
    updateRecord($table, $id, $columns, $values);
    header("Location: user.php?table=$table");  
}

if ($id && $table) {
    $result = fetchTableData($table, $id);
    $data = mysqli_fetch_assoc($result);
    if ($data) {
        if ($is_admin) {
            displayEditForm($table, $data);  
        } else {
            echo "<h2>View Record</h2>";
            foreach ($data as $key => $value) {
                if ($key != 'id') {
                    echo "<strong>$key:</strong> " . htmlspecialchars($value) . "<br>";
                }
            }
        }
    } else {
        echo "Record not found!";
    }
}

mysqli_close($connection);
?>

