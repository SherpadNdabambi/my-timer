<?php

$sqlConnection = new mysqli("localhost", "root", "root", "my_timer") or die("Connection failed: " .$sqlConnection->connect_error);

$email = $_POST['email'];
$password = $_POST['password'];
/*
$email = stripslashes($email);
$password = stripslashes($password);
$email = mysql_real_escape_string($email);
$password = mysql_real_escape_string($password);
*/

$query = "select * from user where email = '$email' and password = '$password'";
$result = $sqlConnection->query($query);

if ($result->num_rows == 1){
	echo ":) :) LOGIN SUCCESS :) :) ";
}

else {
	echo "User with email address '$email' not found.";
}
?>