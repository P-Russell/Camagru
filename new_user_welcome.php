<?php include "public/templates/header.php"; ?>
<?php
require "common.php";
if (isset($_GET['token']) && !empty($_GET['token']) && isset($_GET['email']) && !empty($_GET['email']))
{
	require "config/database.php";
	try {
		$connection = new PDO($dsn, $username, $password, $options);
		$statement = $connection->prepare("SELECT * FROM users WHERE token = :token");
		$token = escape($_GET['token']);
		$statement->bindParam(':token', $token , PDO::PARAM_STR);
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
?>
<?php include "public/templates/footer.php"; ?>