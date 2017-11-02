<?php
require "get_from_db.php";
require "pagination.php";

$startArticles = ($_GET['page'] - 1) * $articlesPerPage;
$images = get_images($startArticles, $articlesPerPage);


?>

<?php
if (isset($_SESSION['username']) && $images)
{
    echo "<table>";
    foreach($images as $image) {
        $comments = get_comments($image['image_name']);
        $likes = get_likes($image['image_name']);
        $total_likes = sizeof($likes);
        echo '
        <tr>
            <th><img class="home_gallery_img" src=' . substr($image['image_name'], 2) . '></th>
            <th valign="top" align="left">
            <h4>Comments:</h4>
            <ul>';
            foreach($comments as $comment) {
                echo "<li>" . $comment['username'] . " Commented: " . $comment['user_comment'] . "</li>";
            }
            echo '
            </ul>
            <form method="post" action="newcomment.php">
            <input type="hidden" name="image_name" value=' . $image['image_name'] . '>
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
            <input type="hidden" name="image_name" value=' . $image['image_name'] . '>
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
        $comments = get_comments($image['image_name']);
        $likes = get_likes($image['image_name']);
        $total_likes = sizeof($likes);
        echo '
        <tr>
            <th><img class="home_gallery_img" src=' . substr($image['image_name'], 2) . '></th>
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