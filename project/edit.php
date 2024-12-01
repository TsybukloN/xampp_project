<?php
session_start();
global $connection;
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if ($_SESSION['role'] != 'admin') {
    header('Location: dashboard.php');
    exit;
}

$table = $_GET['table'] ?? '';
$id = $_GET['id'] ?? '';

if (!$table || !$id) {
    die('Table or ID is missing.');
}

function generate_edit_form($table, $id) {
    global $connection;

    $query = "SHOW COLUMNS FROM $table";
    $result = mysqli_query($connection, $query);

    if ($result) {
        $query = "SELECT * FROM $table WHERE id = $id";
        $record_result = mysqli_query($connection, $query);
        $record = mysqli_fetch_assoc($record_result);

        echo "<form method='POST' action='edit.php?table=$table&id=$id'>";

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
                echo "<input type='number' id='$field' name='$field' value='" . htmlspecialchars($record[$field]) . "' required><br><br>";
            } elseif ($input_type == 'date') {
                echo "<input type='date' id='$field' name='$field' value='" . htmlspecialchars($record[$field]) . "' required><br><br>";
            } else {
                echo "<input type='$input_type' id='$field' name='$field' value='" . htmlspecialchars($record[$field]) . "' required><br><br>";
            }
        }

        echo "<input type='submit' name='edit' value='Update Record'></form>";
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}

if (isset($_POST['edit'])) {
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

    $set_values = [];
    foreach ($columns as $index => $column) {
        $set_values[] = "$column = " . $values[$index];
    }

    $set_str = implode(", ", $set_values);

    $query = "UPDATE $table SET $set_str WHERE id = $id";
    $result = mysqli_query($connection, $query);

    if ($result) {
        echo "Record updated successfully.";
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Record - <?php echo ucfirst($table); ?></title>
</head>
<body>
<h1>Edit Record in <?php echo ucfirst($table); ?></h1>

<?php generate_edit_form($table, $id); ?>

<br><br>
<a href="dashboard.php">Back to Admin Panel</a>
</body>
</html>

<?php mysqli_close($connection); ?>
