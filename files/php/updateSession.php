<?php

//start session
session_start();

//create database connection
$sqlConnection = new mysqli("localhost", "root", "root", "my_timer") or die ("Connection failed: " .$sqlConnection->connect_error);

//get session data from session form
$task_name = $_POST["task_name"];
$date_stopped = $_POST["date_stopped"];
$time_stopped = $_POST["time_stopped"];
$time_worked = $_POST["time_worked"];

//to prevent sql injection
$task_name = stripslashes($task_name);
$date_stopped = stripslashes($date_stopped);
$time_stopped = stripslashes($time_stopped);
$time_worked = stripslashes($time_worked);

//in case user has not set task name
if($task_name == '') $task_name = "Unnamed task";

//create task if task does not exist
$selectTaskQuery = "select * from task where user_id = " . $_SESSION["userID"] . " and name = '$task_name';";
$result = $sqlConnection->query($selectTaskQuery);
if(mysqli_num_rows($result) == 0) {
    $createTaskQuery = "insert into task (user_id, name) values (" . $_SESSION["userID"] . ", '$task_name');";
    $sqlConnection->query($createTaskQuery) or die("Failed to save task: " . $sqlConnection->error);
}

//update session
$createSessionQuery = "update session set task_id = (select id from task where name = '$task_name'), date_stopped = '$date_stopped', time_stopped = '$time_stopped', time_worked = '$time_worked' order by id desc limit 1;";
$sqlConnection->query($createSessionQuery) or die("Failed to save session: " . $sqlConnection->error);

//close connection to database
$sqlConnection->close();

?>