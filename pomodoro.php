<?php

session_start();

if(!$_SESSION["userID"]) header("location: login.php");

//create connection to database
$sqlConnection = new mysqli("localhost", "root", "root", "my_timer") or die("Connection failed: " .$sqlConnection->connect_error);

//get username from database
$user_id = $_SESSION["userID"];
$select_username_query = "Select username from user where id = '$user_id'";
$result = $sqlConnection->query($select_username_query);
$username = $result->fetch_assoc()["username"];

//close connection to database
$sqlConnection->close();

if(isset($_POST["logoutButton"])) logout();

if(isset($_POST["logoutButton"])) logout();

function logout(){
	$_SESSION["userID"] = null;
	header("location: login.php");
}

?>

<!DOCTYPE html>

<html>

<head>

	<title>My Timer</title>

	<link rel="stylesheet" type="text/css" href="files/css/styles.css">

</head>

<body onload="onPageLoad()" onunload="onPageUnload()">

	<div id="contextMenu" class="context-menu hidden">

		<div class="context-menu-item" onclick="jumpToReminder()">Jump to <span id="breakReminderTime"></span></div>
		<div class="context-menu-item" onclick="skipPhase()">Next phase</div>

	</div>

	<button id="accountButton" onclick="show(accountPanel)" class="right"> <img id="accountIcon" src="files/images/icons8-user-24.png"> </button>

	<div id="accountPanel" class="context-menu hidden right">
		
		<button id="accountButton" onclick="hide(accountPanel)"> <img id="accountIcon" src="files/images/icons8-user-24.png"> </button>

		<br><?php echo "$username"; ?>

		<hr>

		<form method="post">
			<input type="submit" name="logoutButton" value="Logout">
		</form>

	</div>

	<div class="centered">

		<div id="phaseLabel">Work (1/4)</div>
	
		<div id="countdownLabel">25:00</div>
	
		<form id="sessionForm" action="files/php/logSession.php" method="post">

			<input id="taskName" name="task_name" onblur="this.placeholder = 'Unnamed Task'" onfocus="this.placeholder = ''" placeholder="Unnamed Task">
			<input id="dateStarted" name="date_started" class="hidden">
			<input id="dateStopped" name="date_stopped" class="hidden">
			<input id="dateStopped" name="time_stopped" class="hidden">
			<input id="timeStarted" name="time_started" class="hidden">
			<input id="timeStopped" name="time_stopped" class="hidden">
			<input id="timeWorked" name="time_worked" class="hidden">

		</form>

		<button id="startButton" onclick="start()"> <img id="startIcon" src="files/images/play.png" class="control"> </button>
		<button id="pauseButton" class="hidden"  onclick="pause()"> <img id="pauseIcon" src="files/images/pause.png" class="control"> </button>
		<button id="stopButton" class="hidden" onclick="stop()"> <img id="stopIcon" src="files/images/stop.png" class="control"> </button>

	</div>

	<script type="text/javascript" src="files/js/scripts.js"></script>
	<script type="text/javascript" src="files/js/time.js"></script>
	<script type="text/javascript" src="files/js/timer.js"></script>
	<script type="text/javascript" src="files/js/pomodoro.js"></script>

</body>

</html>