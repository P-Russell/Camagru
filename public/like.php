<?php
require "../common.php";
session_start();
if (isset($_SESSION['username']) && !empty($_SESSION['username']))
{
    if (isset($_POST['image_name']) && !empty($_POST['image_name']))
    {
        echo "post varibals found";
        require "../config.php";
        try{
            $connection = new PDO($dsn, $username, $password, $options);
            $sql = "INSERT INTO likes (username, image_name) VALUES
            (:username, :image_name)";
            $statement = $connection->prepare($sql);
            $statement->execute([
                'username' => escape($_SESSION['username']),
                'image_name' => escape($_POST['image_name'])
                ]);
            $connection = null;
            $_SESSION['image_name'] = null;
            header('Location: index.php');
        }
        catch(PDOException $error) {
            echo $sql . "<br>" . $error->getMessage();
        }
    }
}
?>