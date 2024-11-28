<?php
    global $connection;
    include('db.php');

    if(isset($_POST['add'])) {
        $title = $_POST['title'];
        $image = $_POST['image'];
        $content = $_POST['content'];

        $query = "INSERT INTO articles (title, image, content) VALUES ('$title', '$image', '$content')";
        $result = mysqli_query($connection, $query);
        if ($result) {
            echo "Article added successfully";
        } else {
            echo "Error: " . $query . "<br>" . mysqli_error($connection);
        }

        header("Location: index.php");
    }
?>