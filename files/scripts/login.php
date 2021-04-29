<?php

$sqlConnection = new mysqli("localhost:/var/lib/mysql/mysql.sock", "root", "root", "my_timer") or die("Connection failed: " .$sqlConnection->connect_error);

$sqlConnection -> select_db("my_timer") or die ("cannot select database:  " .$sqlConnection->connect_error);

/*
$loginEmail  = $_POST['emailAddress'];

$loginPassword  = $_POST['password'];

$loginEmail = stripslashes($loginEmail);

$loginPassword = stripslashes($loginPassword);

$loginEmail = mysql_real_escape_string($loginEmail);

$loginPassword = mysql_real_escape_string($loginPassword);

$sql = "select * from $table_name where emailAddress = '$loginEmail' AND password = '$loginPassword'";

$result = mysql_query($sql, $connection);

$count = mysql_num_rows($result);

if ($count == 1){
	echo ":) :) LOGIN SUCCESS :) :) ";
}

else {
	echo ":( :( AUTHETICATION FAILURE :( :( ";
}
*/
?>