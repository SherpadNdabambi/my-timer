<?php

$time_worked = stripslashes($time_worked);
if($time_worked != "00:00:00"){
	//start session
	session_start();

	//create connection to database
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

	echo "task_name: $task_name<br>";
	echo "date_started: $date_started<br>";
	echo "date_stopped: $date_stopped<br>";
	echo "time_started: $time_started<br>";
	echo "time_stopped: $time_stopped<br>";
	echo "time_worked: $time_worked<br>";

	//create task
	$createTaskQuery = "insert ignore into task (user_id, name) values (" . $_SESSION["userID"] . ", '$task_name');";
	if ($sqlConnection->query($createTaskQuery)) echo "task saved successfully<br>";
	else echo "Failed to save task: " . $sqlConnection->error;

	//create session
	$createSessionQuery = "insert into session (task_id, user_id, date_started, date_stopped, time_started, time_stopped, time_worked, type) values ((select id from task where name = '$task_name'), " . $_SESSION["userID"] . ", '$date_started', '$date_stopped', '$time_started', '$time_stopped', '$time_worked', 'pomodoro');";
	if($sqlConnection->query($createSessionQuery)) echo "session saved successfully";
	else echo "Failed to save session: " . $sqlConnection->error;

	//close connection to database
	$sqlConnection->close();
}
header("location: ../../pomodoro.php");

?>