<?php

$sqlConnection = new mysqli("localhost", "root", "root", "my_timer") or die("Connection failed: " .$sqlConnection->connect_error);

//get session data from session form
$task_name = $_POST["task_name"];
$start_date = $_POST["start_date"];
$start_time = $_POST["start_time"];
$stop_time = $_POST["stop_time"];
$time_elapsed = $_POST["time_elapsed"];

//to prevent sql injection
$task_name = stripslashes($task_name);
$start_date = stripslashes($start_date);
$start_time = stripslashes($start_time);
$stop_time = stripslashes($stop_time);
$time_elapsed = stripslashes($time_elapsed);



?>