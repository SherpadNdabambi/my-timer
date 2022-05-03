<?php

//Start session
session_start();

//create dataabase connection
$sqlConnection = new mysqli("localhost", "mysql", "mysql", "my_timer") or die("Couldn't connect to database: $sqlConnection->connect_error");

//get new timer mode from jQuery
$timer_mode = $_POST["timer_mode"];

//to prevent sql injection
$timer_mode = stripslashes($timer_mode);

//update timer_mode
$query = "update settings set timer_mode = '$timer_mode' where user_id = '" .$_SESSION["userID"] ."';";
$sqlConnection->query($query) or die("Couldn't update timer mode: $sqlConnection->connect_error");

//close sql connection
$sqlConnection->close();

?>