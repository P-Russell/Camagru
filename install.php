<?PHP
include ("config.php");
$con = new mysqli($host, $username, $password);

if ($con->connect_errno) {
	die("Connection failed: " .$connection->error);
}
$sql_init = file_get_contents("data/init.sql");

if (!$con->multi_query($sql_init))
	echo ("database creation failed (". $con->errno . ") " . $con->error);
else
	echo "Database and table users created successfully";

$connection->close();
?>
