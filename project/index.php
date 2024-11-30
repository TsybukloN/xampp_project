<?php
include('db.php');

$tables = ['Exchanges', 'Cryptocurrencies', 'Prices', 'Users', 'Portfolios', 'Transactions'];

$selected_table = isset($_GET['table']) ? $_GET['table'] : $tables[0];
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$order = isset($_GET['order']) ? $_GET['order'] : 'id ASC';

$query = "SELECT * FROM $selected_table";
if ($filter) {
    $query .= " WHERE CONCAT_WS('', " . implode(', ', array_map(fn($col) => "`$col`", get_columns($selected_table))) . ") LIKE '%$filter%'";
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

<table border="1">
    <tr>
        <?php foreach (get_columns($selected_table) as $column): ?>
            <th><?php echo $column; ?></th>
        <?php endforeach; ?>
    </tr>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <?php foreach ($row as $value): ?>
                <td><?php echo htmlspecialchars($value); ?></td>
            <?php endforeach; ?>
        </tr>
    <?php endwhile; ?>
</table>

</body>
</html>


<?php
global $connection;
include('db.php');

$query = "SELECT * FROM Users";
$result = mysqli_query($connection, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User List</title>
</head>
<body>
    <h1>User List</h1>
    <ul>
        <?php while ($user = mysqli_fetch_assoc($result)) { ?>
            <li>
                <a href="user.php?id=<?php echo $user['id']; ?>">
                    <?php echo htmlspecialchars($user['username']); ?>
                </a>
            </li>
        <?php } ?>
    </ul>
</body>
</html>
<?php mysqli_close($connection); ?>