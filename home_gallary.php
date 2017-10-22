<?php

function get_comments($image)
{
    $image = escape($image);
    require "config.php";
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
    require "config.php";
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

$dirname = "images/user/";
$images = glob($dirname."*.png");

if (isset($_SESSION['username']) && $images)
{
    echo "<table>";
    foreach($images as $image) {
        $comments = get_comments($image);
        $likes = get_likes($image);
        $total_likes = sizeof($likes);
        echo '
        <tr>
            <th><img class="home_gallery_img" src=' . $image . '></th>
            <th valign="top" align="left">
            <h4>Comments:</h4>
            <ul>';
            foreach($comments as $comment) {
                echo "<li>" . $comment['username'] . " Commented: " . $comment['user_comment'] . "</li>";
            }
            echo '
            </ul>
            <form method="post" action="newcomment.php">
            <input type="hidden" name="image_name" value=' . $image . '>
            <button type="submit" value="New Comment">
            New Comment
            </button>
            </form>
            <h4>Total Likes: ' . $total_likes . '. Most recently by:</h4>
            <ul>';
            $i = 0;
            while ($i < 3 && $i < $total_likes) {
                echo "<li>User " . $likes[$i]['username'] . " On " . $likes[$i]['created'] . "</li>";
                $i++;
            }
            echo '
            </ul>
            <form method="post" action="like.php">
            <input type="hidden" name="image_name" value=' . $image . '>
            <button type="submit" name="submit" value="like">
            Like!
            </button>
            </form>
            </th>
        </tr>
        ';
    }
    echo "<table>";
}
else if ($images) {
    echo "<table>";
    foreach($images as $image) {
        $comments = get_comments($image);
        $likes = get_likes($image);
        $total_likes = sizeof($likes);
        echo '
        <tr>
            <th><img class="home_gallery_img" src=' . $image . '></th>
            <th valign="top" align="left">
            <h4>Comments:</h4>
            <ul>';
            foreach($comments as $comment) {
                echo "<li>" . $comment['username'] . " Commented: " . $comment['user_comment'] . "</li>";
            }
            echo '
            </ul>
            <h4>Total Likes: ' . $total_likes . '. Most recently by:</h4>
            <ul>';
            $i = 0;
            while ($i < 3 && $i < $total_likes) {
                echo "<li>User " . $likes[$i]['username'] . " On " . $likes[$i]['created'] . "</li>";
                $i++;
            }
            echo '
            </ul>
            </th>
        </tr>
        ';
    }
    echo "<table>";
}
?>