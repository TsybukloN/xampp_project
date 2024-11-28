<?php
    global $connection;
    include('db.php');

    if(isset($_GET['id'])) {
        $id = $_GET['id'];
        $query = "DELETE FROM articles WHERE id = $id";
        $result = mysqli_query($connection, $query);
        if($result) {
            echo "Article deleted successfully";
        } else {
            echo "Error: " . $query . "<br>" . mysqli_error($connection);
        }
        header("Location: index.php");
    }
    mysqli_close($connection);

?>
