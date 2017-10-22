<?php include "templates/header.php"; ?>
<?PHP
include "../common.php";
require "../email.php";
if (isset($_POST['submit']))
{
	$statement = 0;
	if ($_POST['username'] != NULL && $_POST['psword'] != NULL)
	{
		require "../config.php";
		try
		{
			$connection = new PDO($dsn, $username, $password, $options);
			$user = array(
				"username" => escape($_POST['username']),
				"password" => escape($_POST['psword'])
			);
			$user['password'] = hash('whirlpool', $user['password']);
			$sql = "SELECT * FROM users WHERE username = :username";
			$statement = $connection->prepare($sql);
			$statement->bindParam(':username', $user['username'], PDO::PARAM_STR);
			$statement->execute();
			$result = $statement->fetch();
			$connection = NULL;
			if ($result) {
				if ($result['password'] == $user['password'] && $result['active'] == 1) {
					session_start();
					$_SESSION['username'] = $result['username'];
					header('Location: index.php');
				}
				else if ($result['password'] == $user['password'] && $result['active'] == 0) {
					echo "Hello " . $result['username'] . " you still have to validate your email";
				}
				else {
					echo "Hello " . $result['username'] . " you have entered the wrong password";
				}
			}
			else
				echo "User not found";
		}
		catch(PDOException $error)
		{
			echo $sql . "<br>" . $error->getMessage();
		}
	}
	else if (isset($_POST['submit']) && empty($_POST['username']) || empty($_POST['psword']))
		echo "Incomplete form";
}

if (isset($_POST['reset']))
{
	$statement = 0;
	if ($_POST['username'] != NULL)
	{
		require "../config.php";
		try
		{
			$connection = new PDO($dsn, $username, $password, $options);
			$user = array(
				"username" => escape($_POST['username'])
			);
			$sql = "SELECT * FROM users WHERE username = :username";
			$statement = $connection->prepare($sql);
			$statement->bindParam(':username', $user['username'], PDO::PARAM_STR);
			$statement->execute();
			$result = $statement->fetch();
			$connection = NULL;
			if ($result) {
				$user = array(
					"username" => $result['username'],
					"email" => $result['email'],
					"password" => $result['password'],
					"token" => $result['token']
				);
				send_password_email($user);
			}
			else
				echo "Username not found";
		}
		catch(PDOException $error)
		{
			echo $sql . "<br>" . $error->getMessage();
		}
	}
	else if (isset($_POST['submit']) && empty($_POST['username']) || empty($_POST['psword']))
		echo "Incomplete form";
}

?>

<h2>Signin</h2>

<form method="post">
	<label for="username">User Name</label>
	<input type="text" name="username" id="username">
	<label for="psword">Password</label>
	<input type="password" name="psword" id="psword">
	<input type="submit" name="submit" value="Signin">
</form>
<br><br>
<h2>Reset your password</h2>

<form method="post">
	<label for="username">User Name</label>
	<input type="text" name="username" id="username">
	<input type="submit" name="reset" value="reset">
</form>
<br><br>

<a href="index.php">Back to home</a>

<?php include "templates/footer.php"; ?>
