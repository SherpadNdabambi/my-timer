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

$select_user_query = "select * from user where email = '$email' and password = '$password'";
$result = $sqlConnection->query($select_user_query);

if ($result->num_rows == 1){
	$select_userID_query = "select id from user where email = '$email' and password = '$password'";
	$result = $sqlConnection->query($select_userID_query);
	session_start();
	$_SESSION["userID"] = $result->fetch_assoc()["id"];
	header("location: ../../index.php");
}

else {
	echo "User with email address '$email' not found.";
}
?>