<?php include "templates/header.php"; ?>
<?PHP

require "../email.php";

function in_use($user)
{
	try 
	{
		require "../config.php";
		$connection = new PDO($dsn, $username, $password, $options);
		$sql = "SELECT * FROM users WHERE email = :email OR username = :username";
		$statement = $connection->prepare($sql);
		$statement->execute(['email' => $user['email'], 'username' => $user['username']]);
		$result = $statement->fetch();
		if (!empty($result)) {
			$connection = NULL;
			return (TRUE);
		}
		$connection = NULL;
		return (FALSE);
	}
	catch (PDOException $error) 
	{
		echo $sql . "<br>" . $error->getMessage();
		return (FALSE);
	}	
}

include "../common.php";

if (isset($_POST['submit']))
{
	if ($_POST['username'] != NULL && $_POST['email'] != NULL && 
	$_POST['psword'] != NULL && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
	&& strlen($_POST['psword']) >= 8)
	{
		require "../config.php";
		try
		{
			$connection = new PDO($dsn, $username, $password, $options);
			$new_user = array(
				"username" => $_POST['username'],
				"email" => $_POST['email'],
				"password" => $_POST['psword'],
				"token" => md5(rand(0,1000))
			);
			foreach ($new_user as $key => $value) {
				$value = escape($value);
			}
			if (!in_use($new_user)) {
				$new_user['password'] = hash('whirlpool', $new_user['password']);
				$sql = sprintf(
					"INSERT INTO %s (%s) values (%s)",
					"users",
					implode(", ", array_keys($new_user)),
					":" . implode(", :", array_keys($new_user)));
				$statement = $connection->prepare($sql);
				$statement->execute($new_user);
				send_verification_email($new_user);
			}
			else
				echo "Username or email allready in use";
			$connection = NULL;
		}
		catch(PDOException $error)
		{
			echo $sql . "<br>" . $error->getMessage();
		}
	}
	else if (isset($_POST['submit']) && !empty($_POST['email']) &&
	 !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		?>
		<blockquote><?php echo escape($_POST['email']); ?> is not a valid email.</blockquote>
		<?php 
	}
	else if (isset($_POST['submit']) && ($_POST['username'] == NULL || 
	$_POST['email'] == NULL || $_POST['psword'] == NULL)) 
	{ ?>
		<blockquote>Incomplete form</blockquote>
	<?php 
	}
	elseif (isset($_POST['submit']) && strlen($_POST['psword']) < 8)
	{?>
		<blockquote>Password should be at least 8 charaters long</blockquote>
	<?php 
	}
}
?>
<h2>Signup</h2>

<form method="post">
	<label for="username">User Name</label>
	<input type="text" name="username" id="username">
	<label for="email">Email Address</label>
	<input type="text" name="email" id="email">
	<label for="psword">Password</label>
	<input type="password" name="psword" id="psword">
	<input type="submit" name="submit" value="Submit">
</form>

<?php include "templates/footer.php"; ?>