<?php
require "config/database.php";
require "common.php";
session_start();
if (isset($_POST['image']))
{
    $image = '.' . substr($_POST['image'], 29);
    try
    {
        $connection = new PDO($dsn, $username, $password, $options);
        $sql = "DELETE FROM images WHERE image_name = :image_name 
        AND username = :username";
        $statement = $connection->prepare($sql);
        $statement->execute([
            'image_name' => $image,
            'username' => $_SESSION['username']
            ]);
        $connection = null;
        unlink($image);
        header('location: new_image.php');
    }
    catch(PDOException $error)
    {
        echo $sql . "<br>" . $error->getMessage();
    }
 }
?>