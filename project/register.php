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
            echo "<div class='alert alert-success mt-3'>User added successfully</div>";
        } else {
            echo "<div class='alert alert-danger mt-3'>Error: " . $query . "<br>" . mysqli_error($connection) . "</div>";
        }
    }
    mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>
    
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f4f6f9;
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
        h2 {
            color: #333;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center">Create New Account</h2>

    <form method="POST" class="mt-4">
        <div class="form-group">
            <label for="username">Nickname:</label>
            <input type="text" class="form-control" name="username" id="username" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" name="email" id="email" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" name="password" id="password" required>
        </div>
        
        <div class="form-group">
            <label for="role">Role:</label>
            <input type="text" class="form-control" name="role" id="role">
        </div>
        
        <button type="submit" class="btn btn-primary btn-block">Create Account</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
