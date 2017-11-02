<?php
function get_comments($image)
{
    $image = escape($image);
    require "config/database.php";
    try
    {
        $connection = new PDO($dsn, $username, $password, $options);
        $sql = "SELECT * FROM comments WHERE image_name = :image
        ORDER BY created desc LIMIT 3";
        $statement = $connection->prepare($sql);
        $statement->execute(['image' => $image]);
        $results = $statement->fetchAll();
        $connection = null;
        return ($results);
    }
    catch(PDOException $error)
    {
        echo $sql . "<br>" . $error->getMessage();
    }
}

function get_likes($image)
{
    $image = escape($image);
    require "config/database.php";
    try
    {
        $connection = new PDO($dsn, $username, $password, $options);
        $sql = "SELECT * FROM likes WHERE image_name = :image_name
        ORDER BY created desc";
        $statement = $connection->prepare($sql);
        $statement->execute(['image_name' => $image]);
        $results = $statement->fetchAll();
        $connection = null;
        return ($results);
    }
    catch(PDOException $error)
    {
        echo $sql . "<br>" . $error->getMessage();
    }
}

function get_images($start, $stop)
{
    if ($start < 0 || $stop < 0)
        return null;
    require "config/database.php";
    try
    {
        $connection = new PDO($dsn, $username, $password, $options);
        $sql = "SELECT * FROM images ORDER BY created desc LIMIT " . $start . "," . $stop;
        $statement = $connection->prepare($sql);
        $statement->execute();
        $results = $statement->fetchAll();
        $connection = null;
        return ($results);
    }
    catch(PDOException $error)
    {
        echo $sql . "<br>" . $error->getMessage();
    }
}

function count_images()
{
    require "config/database.php";
    try
    {
        $connection = new PDO($dsn, $username, $password, $options);
        $sql = "SELECT * FROM images";
        $statement = $connection->prepare($sql);
        $statement->execute();
        $results = $statement->rowCount();
        $connection = null;
        return ($results);
    }
    catch(PDOException $error)
    {
        echo $sql . "<br>" . $error->getMessage();
    }
}
?>