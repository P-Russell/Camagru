<?php
require "config/database.php";
require "common.php";

session_start();

if (isset($_POST['image']) && !empty($_POST['image']) && isset($_SESSION['username'])) 
{
    $i = 1;
    $stock = array();
    while (isset($_POST['stock_image' . $i])) {
        $stock[] = $_POST['stock_image' . $i];
        $i++;
    }
	$b64 = $_POST['image'];
	$b64 = substr($b64, 22); //removes: config:image/png;base64
	$num = 1;
	$file_name =  "./public/images/user/" . $_SESSION['username'] . $num . ".png";
	while (file_exists($file_name)) {
		$num++;
		$file_name =  "./public/images/user/" . $_SESSION['username'] . $num . ".png";
	}
	$file = fopen($file_name, "wb");
    fwrite($file, base64_decode($b64));
    fclose($file);
    $dest = imagecreatefrompng($file_name);
    imagealphablending($dest, true);
    imagesavealpha($dest, true);
    foreach($stock as $fn) {
        $cur = imagecreatefrompng($fn);
        imagealphablending($cur, true);
        imagesavealpha($cur, true);
        imagecopy($dest, $cur, 0, 0, 0, 0, 648, 484);
        imagedestroy($cur);
    }
    imagepng($dest, $file_name);
    imagedestroy($dest);
    try
    {
        $connection = new PDO($dsn, $username, $password, $options);
        $image = array(
            "username" => escape($_SESSION['username']),
            "image_name" => $file_name
        );
        $sql = "INSERT INTO images (username, image_name) VALUES
        (:username, :image_name)";
        $statement = $connection->prepare($sql);
        $statement->execute($image);
        $connection = NULL;
    }
    catch(PDOException $error)
    {
        echo $sql . "<br>" . $error->getMessage();
    }
}
header("location: new_image.php");
?>