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
	session_start();
	$select_username_query = "select username from user where email = '$email' and password = '$password'";
	$result = $sqlConnection->query($select_username_query);
	$_SESSION["username"] = $result->fetch_assoc()["username"];
	$sessionUser = $_SESSION["username"];
	header("location: ../../index.php");
}

else {
	echo "User with email address '$email' not found.";
}
?>