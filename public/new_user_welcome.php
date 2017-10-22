<?php include "templates/header.php"; ?>
<?php
session_start();
require "../common.php";
if (isset($_GET['token']) && !empty($_GET['token']) && isset($_GET['email']) && !empty($_GET['email']))
{
	require "../config.php";
	try {
		$connection = new PDO($dsn, $username, $password, $options);
		$statement = $connection->prepare("SELECT * FROM users WHERE token = :token");
		$statement->bindParam(':token', escape($_GET['token']), PDO::PARAM_STR);
		$statement->execute();
		$result = $statement->fetch();
		$connection = NULL;
		if ($result) {
			if ($result['email'] == escape($_GET['email'])) {
				$connection = new PDO($dsn, $username, $password, $options);
				$sql = "UPDATE users SET active = '1' WHERE token= :token AND email = :email";
				$statement = $connection->prepare($sql);
				$statement->execute(['token' => $result['token'], 'email' => $result['email']]);
				$_SESSION['username'] = $result['username'];
                $connection = NULL;
                echo '
                Welcome ' . $_SESSION['username'] . '. Your email has now been verified. 
                ';
			}
			else
				echo "email in get params does not match email address in database";
		}
		else
			echo "could not find token";
	}
	catch(PDOException $error) {
			echo $sql . "<br>" . $error->getMessage();
        }
}
if (isset($_SESSION['username']) && !empty($_SESSION['username']))
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
?>

<a href="index.php">Continue to home page</a>
<br> <br>

<?php include "templates/footer.php"; ?>