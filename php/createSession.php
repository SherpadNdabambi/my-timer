<?php

	//start session
	session_start();

	//create connection to database
	$sqlConnection = new mysqli("localhost", "mysql", "mysql", "my_timer") or die("Connection failed: " .$sqlConnection->connect_error);

	//get session data from session form
	$date_started = $_POST["date_started"];
	$date_stopped = $_POST["date_stopped"];
    $task_name = $_POST["task_name"];
	$time_started = $_POST["time_started"];
	$time_stopped = $_POST["time_stopped"];
	$time_worked = $_POST["time_worked"];
	$timer_mode = $_POST["timer_mode"];

	//to prevent sql injection
	$date_started = stripslashes($date_started);
	$date_stopped = stripslashes($date_stopped);
    $task_name = stripslashes($task_name);
	$time_started = stripslashes($time_started);
	$time_stopped = stripslashes($time_stopped);
    $time_worked = stripslashes($time_worked);
    $timer_mode = stripslashes($timer_mode);

	echo "task_name: $task_name<br>";
	echo "date_started: $date_started<br>";
	echo "date_stopped: $date_stopped<br>";
	echo "time_started: $time_started<br>";
	echo "time_stopped: $time_stopped<br>";
    echo "time_worked: $time_worked<br>";
    echo "timer_mode: $timer_mode<br>";

    //create task if task does not exist
    $selectTaskQuery = "select * from task where user_id = " . $_SESSION["userID"] . " and name = '$task_name';";
    $result = $sqlConnection->query($selectTaskQuery);
    if (mysqli_num_rows($result) == 0) {
        $createTaskQuery = "insert into task (user_id, name) values (" . $_SESSION["userID"] . ", '$task_name');";
        $sqlConnection->query($createTaskQuery) or die("Failed to save task: " . $sqlConnection->error);
    }

    //create session
	$createSessionQuery = "insert into session (task_id, user_id, date_started, date_stopped, time_started, time_stopped, time_worked, type) values ((select id from task where name = '$task_name'), " . $_SESSION["userID"] . ", '$date_started', '$date_stopped', '$time_started', '$time_stopped', '$time_worked', '$timer_mode');";
	if($sqlConnection->query($createSessionQuery)) echo "session saved successfully";
	else echo "Failed to save session: " . $sqlConnection->error;

	//close connection to database
	$sqlConnection->close();