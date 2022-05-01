<!--My Timer 2.8.3-->

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

<!DOCTYPE html>

<html lang="en">

<head>

    <title>My Timer</title>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="author" content="Sherpad Ndabambi">

    <meta name="description" content="My Timer allows you to track your work time using a pomodoro timer, stopwatch, or countdown timer.">

    <meta property="og:image" content="img/my-timer-icon.ico">

    <meta property="og:title" content="My Timer">

    <meta property="og:description" content="My Timer allows you to track your work time using a pomodoro timer, stopwatch, or countdown timer.">

    <meta name="twitter:title" content="My Timer">

    <link rel="stylesheet" type="text/css" href="css/styles.css">

</head>

</html>