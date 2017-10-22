<?php include "templates/header.php"; ?>
<?php
require "../common.php";
session_start();
if (isset($_SESSION['username']) && !empty($_SESSION['username']))
{
    if (isset($_POST['image_name']) && !empty($_POST['image_name']))
    {
        $_SESSION['image'] = escape($_POST['image_name']);
    }
    if (!empty($_SESSION['username']))
    {
        ?>
        <script>
        console.log("Hello there");
        var new_li = document.createElement('li');
        new_li.className = 'menu_li';
        new_li.innerHTML = '<a href="log_out.php">Log Out</a>';
        var new_li2 = document.createElement('li');
        new_li2.className = 'menu_li';
        new_li2.innerHTML = '<a href="new_image.php">New Image</a>';
        var menu = document.getElementById('menu');
        menu.appendChild(new_li2);
        menu.appendChild(new_li);
        </script>
        <?php 
    }
} else 
{
    echo "<p> You need to be loggen in to comment </p>";
}



if (isset($_POST['submit']) && $_POST['submit'] == "Submit" && !empty($_POST['comment']))
{
    $comment = escape($_POST['comment']);
    require "../config.php";
    try{
        $connection = new PDO($dsn, $username, $password, $options);
        $sql = "INSERT INTO comments (username, user_comment, image_name) VALUES
        (:username, :user_comment, :image_name)";
        $statement = $connection->prepare($sql);
        $statement->execute([
            'username' => $_SESSION['username'],
            'user_comment' => $comment,
            'image_name' => $_SESSION['image']
            ]);
        $connection = null;
        $_SESSION['image_name'] = null;
        header('Location: index.php');
    }
    catch(PDOException $error) {
        echo $sql . "<br>" . $error->getMessage();
    }
}


?>

<form method="post" id="new">
    <label for="Comment">Comment</label>
    <textarea name="comment" rows="4" cols="50" form="new">Enter text here...</textarea>
	<input style = "display: block;" type="submit" name="submit" value="Submit">
</form>

<a href="index.php">Back to home</a>

<?php include "templates/footer.php"; ?>