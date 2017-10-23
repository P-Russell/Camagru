<?php
$target_dir = "./public/images/user";
$index = 1;
while (file_exists($target_dir . $index . basename($_FILES["fileToUpload"]["name"]))) {
    $index++;
}
$target_file = $target_dir . $index . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "png") {
    echo "Sorry, only PNG files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

require "config.php";
require "common.php";

session_start();

if ($uploadOk && isset($_SESSION['username']) && isset($_POST['stock_image1'])) 
{
    $i = 1;
    $stock = array();
    while (isset($_POST['stock_image' . $i])) {
        $stock[] = $_POST['stock_image' . $i];
        $i++;
    }
	$dest = imagecreatefrompng($target_file);
    imagealphablending($dest, true);
    imagesavealpha($dest, true);
    foreach($stock as $fn) {
        $cur = imagecreatefrompng($fn);
        imagealphablending($cur, true);
        imagesavealpha($cur, true);
        imagecopy($dest, $cur, 0, 0, 0, 0, 648, 484);
        imagedestroy($cur);
    }
    imagepng($dest, $target_file);
    imagedestroy($dest);
    try
    {
        $connection = new PDO($dsn, $username, $password, $options);
        $image = array(
            "username" => escape($_SESSION['username']),
            "image_name" => $target_file
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
header("location: public/new_image.php");
?>