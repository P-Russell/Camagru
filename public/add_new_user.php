<?PHP
include "templates/header.php";
include "../config.php";
if (isset($_POST['submit']))
{
	if (!$_POST['firstname'] || !$_POST['lastname'] ||
		!$_POST['email'] || !$_POST['age'] ||
		!$_POST['location']){
			echo "invalid data supplied";
		}
	else
		echo "good data thanks";
}

include "templates/footer.php";
?>
