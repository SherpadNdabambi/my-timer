<?php

//Start session
session_start();

//create database connection
$sqlConnection = new mysqli("localhost", "root", "root", "my_timer") or die("Connection failed: " .$sqlConnection->connect_error);

//get volume from slider
$volume = $_POST["volume"];

//to prevent sql injection
$volume = stripslashes($volume);

//update settings
$query = "update settings set volume = '$volume' where user_id = " .$_SESSION["userID"] .";";
$sqlConnection->query($query) or die("Failed to update settings: " .$sqlConnection->error);

//close sql connection
$sqlConnection->close();

?>