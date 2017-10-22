<?PHP
$host		= "localhost";
$username	= "root";
$password	= "thatsit";
$dbname		= "camagru"; 
$dsn		= "mysql:host=$host;dbname=$dbname";
$options 	= array(
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
			 	);
?>
