<html>
	<head>
		<title>Camagru</title>
		<link href="public/css/style.css" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>
	<body>
		<ul class="menu_ul" id="menu">
			<li class="menu_li"><a href="index.php">Home</a></li>
			<li class="menu_li" id="signin"><a href="signin.php">Signin</a></li>
			<li class="menu_li" id="signup"><a href="signup.php">Signup</a></li>
 		</ul>
		<h1>Camagru</h1>
<?php
session_start();
if (isset($_SESSION['username']) && !empty($_SESSION['username']))
{

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
        document.getElementById("signin").remove();
        document.getElementById("signup").remove();
        </script>
        <?php 
	}
}
?>