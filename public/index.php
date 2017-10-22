<?php include "templates/header.php"; ?>
<?php
session_start();
require "../common.php";
require "../home_gallary.php";

if (isset($_SESSION['username']) && !empty($_SESSION['username']))
{
	?>
	<script>
	console.log("Hello there");
	var new_li = document.createElement('li');
	new_li.className = 'menu_li';
	new_li.innerHTML = '<a href="log_out.php">Log Out</a>';
	var new_li2 = document.createElement('li');
	new_li2.className = 'menu_li';
	new_li2.innerHTML = '<a href="new_image.php">New Image</a>';
	var menu = document.getElementById('menu');
	menu.appendChild(new_li2);
	menu.appendChild(new_li);
	</script>
	<blockquote> Hello <?php echo escape($_SESSION['username']); ?></blockquote>
	<?php 
}

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