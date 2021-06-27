<?php

$sqlConnection = new mysqli("localhost", "root", "root", "my_timer") or die("Connection failed: " .$sqlConnection->connect_error);

//get session data from session form
$task_name = $_POST["task_name"];
$date_started = $_POST["date_started"];
$date_stopped = $_POST["date_stopped"];
$time_started = $_POST["time_started"];
$time_stopped = $_POST["time_stopped"];
$time_worked = $_POST["time_worked"];

//to prevent sql injection
$task_name = stripslashes($task_name);
$date_started = stripslashes($date_started);
$date_stopped = stripslashes($date_stopped);
$time_started = stripslashes($time_started);
$time_stopped = stripslashes($time_stopped);
$time_worked = stripslashes($time_worked);

?>