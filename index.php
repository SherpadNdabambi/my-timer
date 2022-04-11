<!--My Timer 2.7.3-->

<?php

session_start();

if($_SESSION["userID"]){
	//create connection to database
	$sqlConnection = new mysqli("localhost", "root", "root", "my_timer") or die("Connection failed: " .$sqlConnection->connect_error);

	//get timer_mode from database
	$user_id = $_SESSION["userID"];
	$select_timer_mode_query = "select timer_mode from settings where id = '$user_id'";
	$result = $sqlConnection->query($select_timer_mode_query);
	$timer_mode = $result->fetch_assoc()["timer_mode"];

	//close connection to database
	$sqlConnection->close();

	//redirect to user's last used timer mode
	if($timer_mode == "countdown") header("location: countdown.php");
	else
		if($timer_mode == "pomodoro") header("location: pomodoro.php");
		else header("location: stopwatch.php");
}
else header("location: login.php");

?>