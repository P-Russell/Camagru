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
                $_SESSION['email'] = $result['email'];
                $connection = NULL;
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

if (isset($_POST['reset']))
{  
	if (isset($_POST['newpass']) && !empty($_POST['newpass']) && strlen($_POST['newpass']) >= 8)
	{
		require "../config.php";
		try
		{
			$connection = new PDO($dsn, $username, $password, $options);
			$user = [
                'password' => hash('whirlpool', escape($_POST['newpass'])),
                'email' => $_SESSION['email']
            ];
			$sql = "UPDATE users SET password = :password WHERE email = :email";
			$statement = $connection->prepare($sql);
			$statement->execute($user);
			$connection = NULL;
			header('location: signin.php');
            echo "password updated";
		}
		catch(PDOException $error)
		{
			echo $sql . "<br>" . $error->getMessage();
		}
    }
    else if (isset($_POST['reset']) && empty($_POST['newpass'])) {
        echo "Incomplete form";
    }
    else if (isset($_POST['reset']) && !empty($_POST['newpass']) && strlen($_POST['newpass']) < 8) {
        echo "Password need to be at least 8 chars";
    }
}

?>

<h2>Reset your password</h2>

<form method="post">
	<label for="newpass">New Password</label>
	<input type="password" name="newpass" id="newpass">
	<input type="submit" name="reset" value="reset">
</form>
<br><br>

<?php include "templates/footer.php"; ?>
