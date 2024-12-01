<?php
    global $connection;
    include('db.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (username, email, password_hash, role) VALUES ('$username', '$email', '$hashedPassword', '$role')";
        $result = mysqli_query($connection, $query);
        if ($result) {
            echo "User added successfully";
        } else {
            echo "Error: " . $query . "<br>" . mysqli_error($connection);
        }
    }
    mysqli_close($connection);
?>

<form method="POST">
    Nickname: <input type="text" name="username" required><br>
    Email: <input type="text" name="email" required><br>
    Password: <input type="password" name="password" required><br>
    Role: <input type="text" name="role"><br>
    <button type="submit">Create account</button>
</form>
