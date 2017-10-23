<?php include "templates/header.php"; ?>
<?php
require "../common.php";
require "../home_gallary.php";

if (isset($_POST['image']) && !empty($_POST['image']) && !empty($_SESSION['username'])) 
{
	$b64 = $_POST['image'];
	$b64 = substr($b64, 22); //removes: data:image/png;base64
	$num = 1;
	$file_name =  "./images/user/" . $_SESSION['username'] . $num . ".png";
	while (file_exists($file_name)) {
		$num++;
		$file_name =  "./images/user/" . $_SESSION['username'] . $num . ".png";
	}
	$file = fopen($file_name, "wb");
    fwrite($file, base64_decode($b64));
	fclose($file);
	echo "new image saved <br> <br>";
}
?>
<?php include "templates/footer.php"; ?>