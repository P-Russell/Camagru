<?PHP

require "config.php";

try 
{
	$connection = new PDO("mysql:host=$host", $username, $password, $options);
	$sql = file_get_contents("data/init.sql");
	$connection->exec($sql);
	$connection = NULL;
	echo "Database and tables created successfully.";
}
catch(PDOException $error)
{
	echo $sql . "<br>" . $error->getMessage();
}
?>
