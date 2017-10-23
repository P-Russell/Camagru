<?php
function send_verification_email($user) 
{
	$to      = $user['email']; // Send email to our user
	$subject = 'Signup | Verification'; // Give the email a subject 
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
    $headers .= 'From: <pdrussell011@gmail.com>' . "\r\n";
    $message = '
      <html>
        <head>
          <title>' . $subject . '</title>
        </head>
        <body>
          Hello ' . $user['username'] . ' </br>
          Click the link to validate your email </br>
          <a href="http://localhost:8080/camagru/public/new_user_welcome.php?token=' . $user['token'] . '&email=' . 
          $user['email'] . '">Verify email</a>
        </body>
      </html>
  ';
	if (mail($to, $subject, $message, $headers))
		echo "Follow the link sent to your email to validate your signup<br>";
	else
		echo "sending email validation failed";
}

function send_password_email($user) 
{
	$to      = $user['email']; // Send email to our user
	$subject = 'Password Reset'; // Give the email a subject 
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
    $headers .= 'From: <pdrussell011@gmail.com>' . "\r\n";
    $message = '
      <html>
        <head>
          <title>' . $subject . '</title>
        </head>
        <body>
          Hello ' . $user['username'] . ' </br>
          Click the link to reset your password </br>
          <a href="http://localhost:8080/camagru/public/password_reset.php?token=' . $user['token'] . '&email=' . 
          $user['email'] . '">Verify email</a>
        </body>
      </html>
  ';
	if (mail($to, $subject, $message, $headers))
		echo "a password reset has been sent to you please follow the link in the email<br>";
	else
		echo "sending email validation failed";
}

function send_comment_email($userTo, $userFrom) 
{
	  $to      = $userTo['email']; // Send email to our user
	  $subject = 'New Comment on Your Image'; // Give the email a subject 
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
    $headers .= 'From: <pdrussell011@gmail.com>' . "\r\n";
    $message = '
      <html>
        <head>
          <title>' . $subject . '</title>
        </head>
        <body>
          Hello ' . $userTo['username'] . ' </br>
          User ' . $userFrom['username']. ' said some BS bout your picture '. $userTo['image_title'].'</br>
          They said ' . $userFrom['user_comment'] . ' <br>
          nice eh? <br><br>
          Cheers!
        </body>
      </html>
  ';
	if (mail($to, $subject, $message, $headers))
		echo "a password reset has been sent to you please follow the link in the email<br>";
	else
		echo "sending email validation failed";
}

?>