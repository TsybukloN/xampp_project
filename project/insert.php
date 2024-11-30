<?php
global $connection;
include('db.php');

$table = $_GET['table'] ?? '';

// Функция для генерации формы в зависимости от таблицы
function generate_form($table) {
    switch ($table) {
        case 'Users':
            echo "
                <form method='POST' action='insert.php?table=Users'>
                    <label for='username'>Username:</label><br>
                    <input type='text' id='username' name='username' required><br><br>
                    
                    <label for='email'>Email:</label><br>
                    <input type='email' id='email' name='email' required><br><br>
                    
                    <label for='password'>Password:</label><br>
                    <input type='password' id='password' name='password' required><br><br>

                    <input type='submit' name='add' value='Add User'>
                </form>
            ";
            break;
        case 'Exchanges':
            echo "
                <form method='POST' action='insert.php?table=Exchanges'>
                    <label for='name'>Exchange Name:</label><br>
                    <input type='text' id='name' name='name' required><br><br>
                    
                    <label for='url'>URL:</label><br>
                    <input type='text' id='url' name='url' required><br><br>
                    
                    <label for='country'>Country:</label><br>
                    <input type='text' id='country' name='country'><br><br>

                    <label for='trading_volume'>Trading Volume:</label><br>
                    <input type='number' id='trading_volume' name='trading_volume' step='0.01'><br><br>

                    <input type='submit' name='add' value='Add Exchange'>
                </form>
            ";
            break;
        // Добавь другие случаи для других таблиц, например, для 'Cryptocurrencies', 'Prices' и т.д.
    }
}

if (isset($_POST['add']) && $table === 'Users') {
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT); 

    $query = "INSERT INTO Users (username, email, password_hash) VALUES ('$username', '$email', '$password_hash')";
    $result = mysqli_query($connection, $query);

    if ($result) {
        echo "User added successfully.";
    } else {
        echo "Error: " . mysqli_error($connection);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New <?php echo $table; ?></title>
</head>
<body>
    <h1>Add New <?php echo $table; ?></h1>
    
    <?php generate_form($table); ?>

    <br><br>
    <a href="admin.php">Back to Admin Panel</a> 
    <a href="user.php">Back to User Panel</a> 

</body>
</html>

<?php mysqli_close($connection); ?>
