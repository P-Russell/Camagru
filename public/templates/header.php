<html>
	<head>
		<title>Camagru</title>
		<link href="css/style.css" rel="stylesheet">
	</head>
	<body>
		<ul class="menu_ul" id="menu">
			<li class="menu_li"><a href="index.php">Home</a></li>
			<li class="menu_li"><a href="signin.php">Signin</a></li>
			<li class="menu_li"><a href="signup.php">Signup</a></li>
 		</ul>
		<h1>Camagru</h1>
<?php
session_start();
if (isset($_SESSION['username']) && !empty($_SESSION['username']))
{
    if (isset($_POST['image_name']) && !empty($_POST['image_name']))
    {
        $_SESSION['image'] = escape($_POST['image_name']);
    }
    if (!empty($_SESSION['username']))
    {
        ?>
        <script>
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
        <?php 
	}
}
?>