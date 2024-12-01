<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: index.php');
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

        echo "<form method='POST' action='edit.php?table=$table&id=$id' class='container mt-4'>";

        while ($column = mysqli_fetch_assoc($result)) {
            $field = $column['Field'];
            $type = $column['Type'];

            if ($field === 'id') {
                continue;
            }

            $input_type = 'text';

            if (strpos($type, 'int') !== false || strpos($type, 'float') !== false || strpos($type, 'decimal') !== false) {
                $input_type = 'number';
            } elseif (strpos($type, 'date') !== false || strpos($type, 'time') !== false || strpos($type, 'datetime') !== false) {
                $input_type = 'date';
            } elseif ($field === 'password') {
                $input_type = 'password';
            }

            echo "<div class='mb-3'>";
            echo "<label for='$field' class='form-label'>" . ucfirst($field) . ":</label>";
            echo "<input type='$input_type' id='$field' name='$field' value='" . htmlspecialchars($record[$field]) . "' class='form-control' required><br><br>";

            echo "<label for='position_$field' class='form-label'>Change position of $field:</label>";
            echo "<input type='number' id='position_$field' name='position_$field' class='form-control' min='1'><br><br>";

            echo "</div>";
        }

        echo "<button type='submit' name='edit' class='btn btn-primary'>Update Record</button>";
        echo "</form>";
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}

if (isset($_POST['edit'])) {
    $columns = [];
    $query_columns = "SHOW COLUMNS FROM $table";
    $result_columns = mysqli_query($connection, $query_columns);

    while ($column = mysqli_fetch_assoc($result_columns)) {
        $columns[] = $column['Field'];
    }

    $set_values = [];
    foreach ($columns as $column) {
        if (isset($_POST[$column])) {
            $set_values[] = "$column = '" . mysqli_real_escape_string($connection, $_POST[$column]) . "'";
        }
    }

    $set_str = implode(", ", $set_values);
    $query = "UPDATE $table SET $set_str WHERE id = $id";
    $result = mysqli_query($connection, $query);

    if ($result) {
        echo "Record updated successfully.";
    } else {
        echo "Error: " . mysqli_error($connection);
    }

    foreach ($columns as $column) {
        if (isset($_POST['position_' . $column])) {
            $new_position = intval($_POST['position_' . $column]);

            if ($new_position > 0 && $new_position <= count($columns)) {
                $previous_column = $columns[$new_position - 1];

                $query_type = "SHOW COLUMNS FROM $table WHERE Field = '$column'";
                $result_type = mysqli_query($connection, $query_type);
                $column_info = mysqli_fetch_assoc($result_type);
                $type = $column_info['Type'];

                $query_pos = "ALTER TABLE $table MODIFY COLUMN `$column` $type AFTER `$previous_column`";
                mysqli_query($connection, $query_pos);
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Record - <?php echo ucfirst($table); ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h1 class="mt-5">Edit Record in <?php echo ucfirst($table); ?></h1>
    <?php generate_edit_form($table, $id); ?>
    <br><br>
    <a href="dashboard.php" class="btn btn-secondary">Back to Admin Panel</a>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php mysqli_close($connection); ?>
