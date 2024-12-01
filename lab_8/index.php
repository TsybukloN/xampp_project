<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <?php
        global $connection;
        include('db.php');

        $query = "SELECT * FROM articles";
        $result = mysqli_query($connection, $query);

        $rowCount = mysqli_num_rows($result);

        if ($rowCount > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                echo "id: " . $row["id"]. "<br> <img src=" . $row["image"]. " > <br>" . " - Title: " . $row["title"]. "<br>" . " Content :" . $row["content"]. "<br>";
                echo "<a href=\"delete.php?id=" . $row["id"] . "\"><span>Delete</span>></a> <br>";
            }
        } else {
            echo "0 results";
        }

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

            header("Location: dashboard.php");

        }

        /* while($article = mysqli_fetch_array($result, MYSQLI_NUM)) {
            print_r($article);
        } */

        //regular array
        /*$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        printf($row[0]." ".$row[1]." ".$row[2]." ".$row[3]."<br>");*/

        /*$row = mysqli_fetch_array($result, MYSQLI_NUM);
        printf("%s %s (%s)\n", $row["title"], $row["image"], $row["content"]);*/

        mysqli_close($connection);
    ?>
    <h2>Add an article:</h2>
    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
        <input class="form-control" type="text" name="title" placeholder="Add an article">
        <br>
        <input class="form-control" type="text" name="image" placeholder="Add an image">
        <br>
        <textarea class="form-control" name="content" cols="40" rows="10" placeholder="Content of article"></textarea>
        <br>
        <input class="btn btn-success" type="submit" name="add" placeholder="Add an article">
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</div>>
</body>
</html>