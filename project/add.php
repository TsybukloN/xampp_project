<?php
session_start();
global $connection;
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$table = $_GET['table'] ?? '';

function generate_dynamic_form($table) {
    global $connection;

    $query = "SHOW COLUMNS FROM $table";
    $result = mysqli_query($connection, $query);

    if ($result) {
        while ($column = mysqli_fetch_assoc($result)) {
            $field = $column['Field'];
            $type = $column['Type'];

            if ($field === 'id') {
                continue;
            }

            $input_type = 'text';

            if (strpos($type, 'int') !== false || strpos($type, 'float') !== false || strpos($type, 'decimal') !== false) {
                $input_type = 'number';
            }
            elseif (strpos($type, 'date') !== false || strpos($type, 'time') !== false || strpos($type, 'datetime') !== false) {
                $input_type = 'date';
            }
            elseif ($field === 'password') {
                $input_type = 'password';
            }

            echo "<label for='$field'>" . ucfirst($field) . ":</label><br>";

            if ($input_type == 'number') {
                echo "<input type='number' id='$field' name='$field' required><br><br>";
            } elseif ($input_type == 'date') {
                echo "<input type='date' id='$field' name='$field' required><br><br>";
            } else {
                echo "<input type='$input_type' id='$field' name='$field' required><br><br>";
            }
        }

        echo "<input type='submit' name='add' value='Add Record'></form>";
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}

if (isset($_POST['add'])) {
    $columns = [];
    $values = [];

    $query = "SHOW COLUMNS FROM $table";
    $result = mysqli_query($connection, $query);

    while ($column = mysqli_fetch_assoc($result)) {
        $field = $column['Field'];

        if ($field === 'id') {
            continue;
        }

        if (isset($_POST[$field])) {
            $columns[] = $field;
            $values[] = "'" . mysqli_real_escape_string($connection, $_POST[$field]) . "'";
        }
    }

    $columns_str = implode(", ", $columns);
    $values_str = implode(", ", $values);

    $query = "INSERT INTO $table ($columns_str) VALUES ($values_str)";
    $result = mysqli_query($connection, $query);

    if ($result) {
        echo "Record added successfully.";
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Record - <?php echo ucfirst($table); ?></title>
</head>
<body>
<h1>Add New Record to <?php echo ucfirst($table); ?></h1>

<?php generate_dynamic_form($table); ?>

<br><br>
<a href="dashboard.php">Back to Panel</a>
</body>
</html>

<?php mysqli_close($connection); ?>
