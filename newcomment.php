<?php include "public/templates/header.php"; ?>
<?php
require "common.php";
require "email.php";

if (isset($_POST['image_name']) && !empty($_POST['image_name']))
{
    $_SESSION['image'] = $_POST['image_name'];
}

if (isset($_POST['submit']) && $_POST['submit'] == "Submit" && !empty($_POST['comment']))
{
    $comment = escape($_POST['comment']);
    require "config/database.php";
    try {
        $connection = new PDO($dsn, $username, $password, $options);
        $sql = "INSERT INTO comments (username, user_comment, image_name) VALUES
        (:username, :user_comment, :image_name)";
        $statement = $connection->prepare($sql);
        $userFrom = [
                'username' => $_SESSION['username'],
                'user_comment' => $comment,
                'image_name' => $_SESSION['image']
        ];
        $statement->execute($userFrom);
        $sql = "SELECT * FROM images WHERE image_name = :image_name";
        $statement = $connection->prepare($sql);
        $statement->execute(['image_name' => $_SESSION['image']]);
        $result = $statement->fetch();
        $userTo = ['username' => $result['username']];
        $sql = "SELECT * FROM users WHERE username = :username";
        $statement = $connection->prepare($sql);
        $statement->execute($userTo);
        $result = $statement->fetch();
        $userTo['email'] = $result['email'];
        $userTo['image_title'] = $_SESSION['image'];
        send_comment_email($userTo, $userFrom);
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

<?php include "public/templates/footer.php"; ?>