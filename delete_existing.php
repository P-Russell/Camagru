<?php
require "config.php";
require "common.php";
session_start();
if (isset($_POST['image']))
{
    $image = '.' . substr($_POST['image'], 29);
    echo $image;
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
    }
    catch(PDOException $error)
    {
        echo $sql . "<br>" . $error->getMessage();
    }
    unlink($image);
    header('location: public/new_image.php');
}
?>