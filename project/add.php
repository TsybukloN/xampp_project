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

function generate_dynamic_form($table) {
    global $connection;

    $query = "SHOW COLUMNS FROM $table";
    $result = mysqli_query($connection, $query);

    if ($result) {
        echo "<form method='POST' action='' class='mt-4'>";

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

            echo "<div class='form-group'>";
            echo "<label for='$field'>" . ucfirst($field) . ":</label>";

            if ($input_type == 'number') {
                echo "<input type='number' class='form-control' id='$field' name='$field' required><br><br>";
            } elseif ($input_type == 'date') {
                echo "<input type='date' class='form-control' id='$field' name='$field' required><br><br>";
            } else {
                echo "<input type='$input_type' class='form-control' id='$field' name='$field' required><br><br>";
            }
            echo "</div>";
        }

        echo "<button type='submit' name='add' class='btn btn-primary btn-block'>Add Record</button>";
        echo "</form>";
    } else {
        echo "<div class='alert alert-danger mt-3'>Error: " . mysqli_error($connection) . "</div>";
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
        echo "<div class='alert alert-success mt-3'>Record added successfully.</div>";
    } else {
        echo "<div class='alert alert-danger mt-3'>Error: " . mysqli_error($connection) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Record - <?php echo ucfirst($table); ?></title>

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            margin-top: 50px;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .form-control {
            border-radius: 5px;
        }
        .btn-primary {
            border-radius: 5px;
        }
        h1 {
            color: #333;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="text-center">Add New Record to <?php echo ucfirst($table); ?></h1>

    <?php generate_dynamic_form($table); ?>

    <br><br>
    <a href="dashboard.php" class="btn btn-secondary btn-block">Back to Admin Panel</a>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

<?php mysqli_close($connection); ?>
