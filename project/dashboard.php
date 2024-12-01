<?php
session_start();
global $connection;
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$tables = ['Exchanges', 'Cryptocurrencies', 'Prices', 'Users', 'Portfolios', 'Transactions'];

if ($_SESSION['role'] === 'user') {
    $tables = array_filter($tables, function ($table) {
        return $table !== 'Users';
    });
    $tables = array_values($tables);
}

$selected_table = isset($_GET['table']) ? $_GET['table'] : $tables[0];
$filter1 = isset($_GET['filter1']) ? $_GET['filter1'] : '';
$filter2 = isset($_GET['filter2']) ? $_GET['filter2'] : '';
$role_filter = isset($_GET['role']) ? $_GET['role'] : ''; // Фильтр по роли
$order = isset($_GET['order']) ? $_GET['order'] : 'id ASC';

$query = "SELECT * FROM $selected_table";

if ($filter1) {
    $query .= " WHERE (username LIKE '%$filter1%' OR email LIKE '%$filter1%')";
}

if ($filter2) {
    $query .= ($filter1 ? " AND" : " WHERE") . " users.created_time = '$filter2'";
}

if ($selected_table === 'Users' && $role_filter) {
    $query .= ($filter1 || $filter2 ? " AND" : " WHERE") . " role = '$role_filter'";
}

$query .= " ORDER BY $order";

$result = mysqli_query($connection, $query);

function get_columns($table) {
    global $connection;
    $columns = [];
    $result = mysqli_query($connection, "SHOW COLUMNS FROM $table");
    while ($row = mysqli_fetch_assoc($result)) {
        $columns[] = $row['Field'];
    }
    return $columns;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tables Viewer</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container">

<form method="GET" class="mt-4">
    <div class="form-group">
        <label for="table">Select Table:</label>
        <select name="table" id="table" class="form-control" onchange="this.form.submit()">
            <?php foreach ($tables as $table): ?>
                <option value="<?php echo $table; ?>" <?php echo $selected_table === $table ? 'selected' : ''; ?>>
                    <?php echo $table; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label for="filter1">Filter 1 (Username/Email):</label>
        <input type="text" name="filter1" id="filter1" class="form-control" value="<?php echo $filter1; ?>">
    </div>

    <div class="form-group">
        <label for="filter2">Filter 2 (Date/Time):</label>
        <input type="text" name="filter2" id="filter2" class="form-control" value="<?php echo $filter2; ?>" placeholder="YYYY-MM-DD HH:MM:SS">
    </div>

    <?php if ($selected_table === 'Users'): ?>
        <!-- Чекбоксы для фильтрации по роли -->
        <div class="form-group">
            <label>Filter by Role:</label><br>
            <input type="checkbox" name="role" value="admin" <?php echo $role_filter === 'admin' ? 'checked' : ''; ?>> Admin
            <input type="checkbox" name="role" value="user" <?php echo $role_filter === 'user' ? 'checked' : ''; ?>> User
        </div>
    <?php endif; ?>

    <div class="form-group">
        <label for="order">Order By:</label>
        <select name="order" id="order" class="form-control">
            <option value="id ASC" <?php echo $order === 'id ASC' ? 'selected' : ''; ?>>ID Ascending</option>
            <option value="id DESC" <?php echo $order === 'id DESC' ? 'selected' : ''; ?>>ID Descending</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Apply Filters</button>
</form>

<?php if ($_SESSION['role'] === 'admin'): ?>
    <div class="mt-3">
        <button onclick="window.location.href='add.php?table=<?php echo $selected_table; ?>'" class="btn btn-success">Add Record</button>
    </div>
<?php endif; ?>

<table class="table table-bordered mt-4">
    <thead>
        <tr>
            <?php foreach (get_columns($selected_table) as $column): ?>
                <th><?php echo $column; ?></th>
            <?php endforeach; ?>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <th>Actions</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <?php foreach ($row as $value): ?>
                    <td><?php echo htmlspecialchars($value); ?></td>
                <?php endforeach; ?>

                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <td>
                        <button onclick="window.location.href='edit.php?id=<?php echo $row['id']; ?>&table=<?php echo $selected_table; ?>'" class="btn btn-warning btn-sm">Edit</button>
                        <button onclick="if(confirm('Are you sure you want to delete this record?')) window.location.href='delete.php?id=<?php echo $row['id']; ?>&table=<?php echo $selected_table; ?>'" class="btn btn-danger btn-sm">Delete</button>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>

<?php mysqli_close($connection); ?>
