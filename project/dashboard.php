<?php
session_start();
global $connection;
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$tables = ['Exchanges', 'Cryptocurrencies', 'Prices', 'Users', 'Portfolios', 'Transactions'];

// Hide Users table from users
if ($_SESSION['role'] === 'user') {
    $tables = array_filter($tables, function ($table) {
        return $table !== 'Users';
    });

    $tables = array_values($tables);
}

$selected_table = isset($_GET['table']) ? $_GET['table'] : $tables[0];
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$order = isset($_GET['order']) ? $_GET['order'] : 'id ASC';

$query = "SELECT * FROM $selected_table";
if ($filter) {
    $query .= " WHERE CONCAT_WS('', " . implode(', ', array_map(function ($col) {
            return "`$col`";
        }, get_columns($selected_table))) . ") LIKE '%$filter%'";
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
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<form method="GET">
    <label for="table">Select Table:</label>
    <select name="table" id="table" onchange="this.form.submit()">
        <?php foreach ($tables as $table): ?>
            <option value="<?php echo $table; ?>" <?php echo $selected_table === $table ? 'selected' : ''; ?>>
                <?php echo $table; ?>
            </option>
        <?php endforeach; ?>
    </select>

    <input type="text" name="filter" placeholder="Filter" value="<?php echo $filter; ?>">
    <select name="order">
        <option value="id ASC" <?php echo $order === 'id ASC' ? 'selected' : ''; ?>>ID Ascending</option>
        <option value="id DESC" <?php echo $order === 'id DESC' ? 'selected' : ''; ?>>ID Descending</option>
    </select>
    <button type="submit">Apply</button>
</form>

<?php if ($_SESSION['role'] === 'admin'): ?>
    <div>
        <button onclick="window.location.href='add.php?table=<?php echo $selected_table; ?>'">Add Record</button>
    </div>
<?php endif; ?>

<table border="1">
    <tr>
        <?php foreach (get_columns($selected_table) as $column): ?>
            <th><?php echo $column; ?></th>
        <?php endforeach; ?>
        <?php if ($_SESSION['role'] === 'admin'): ?>
            <th>Actions</th>
        <?php endif; ?>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <?php foreach ($row as $value): ?>
                <td><?php echo htmlspecialchars($value); ?></td>
            <?php endforeach; ?>

            <?php if ($_SESSION['role'] === 'admin'): ?>
                <td>
                    <button onclick="window.location.href='edit.php?id=<?php echo $row['id']; ?>&table=<?php echo $selected_table; ?>'">Edit</button>
                    <button onclick="if(confirm('Are you sure you want to delete this record?')) window.location.href='delete.php?id=<?php echo $row['id']; ?>&table=<?php echo $selected_table; ?>'">Delete</button>
                </td>
            <?php endif; ?>
        </tr>
    <?php endwhile; ?>
</table>

</body>
</html>

<?php mysqli_close($connection); ?>
