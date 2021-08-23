<?php

//start session
session_start();

if(!$_SESSION["userID"]) header("location: login.php");

//create connection to database
$sqlConnection = new mysqli("localhost", "root", "root", "my_timer") or die("Connection failed: " .$sqlConnection->connect_error);

//get username from database
$user_id = $_SESSION["userID"];
$select_username_query = "Select username from user where id = $user_id";
$result = $sqlConnection->query($select_username_query);
$username = $result->fetch_assoc()["username"];

//get user settings from database
$select_settings_query = "Select * from settings where user_id = $user_id;";
$result = $sqlConnection->query($select_settings_query);
$settings = $result->fetch_assoc();

//get task list from database
$select_task_query = "Select name, max(date_started) from session, task where task.user_id = $user_id and task_id = task.id group by name order by max(date_started);";
$result = $sqlConnection->query($select_task_query);
$tasklist = $result->fetch_all();

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

	<link rel="icon" href="files/images/my-timer-icon.ico">
	<link rel="stylesheet" type="text/css" href="files/css/styles.css">

</head>

<body onload="onPageLoad()" onunload="onPageUnload()">

	<div id="contextMenu" class="context-menu hidden">

		<div class="context-menu-item" onclick="jumpToReminder()">Jump to <span id="breakReminderTimeSpan"></span></div>
		<div class="context-menu-item" onclick="skipPhase()">Next phase</div>

	</div>

	<button id="accountButton" onclick="show(accountPanel)" class="right"> <img id="accountIcon" src="files/images/icons8-user-24.png"> </button>

	<div id="accountPanel" class="context-menu hidden right">
		
		<button id="accountButton" onclick="hide(accountPanel)"> <img id="accountIcon" src="files/images/icons8-user-24.png"> </button>

		<br><?php echo "$username"; ?><hr>

		<form method="post">
			<input type="submit" name="logoutButton" value="Logout">
		</form>

	</div>

	<div class="centered">

		<div id="phaseLabel">Work (1/4)</div>
	
		<div id="countdownLabel">25:00</div>

		<form id="sessionForm" action="files/php/logPomodoroSession.php" method="post">

			<input id="taskName" name="task_name" autocomplete="off" list="taskList" onblur="this.placeholder = 'Unnamed task'" onfocus="this.placeholder = ''" placeholder="Unnamed task">
			<datalist id="taskList">
				<?php foreach(array_reverse($tasklist) as $task) echo "<option value='$task[0]'></option>" ?>
			</datalist>
			<input id="dateStarted" name="date_started" class="hidden">
			<input id="dateStopped" name="date_stopped" class="hidden">
			<input id="timeStarted" name="time_started" class="hidden">
			<input id="timeStopped" name="time_stopped" class="hidden">
			<input id="timeWorked" name="time_worked" class="hidden">

		</form>

		<button id="startButton" onclick="start()"> <img id="startIcon" src="files/images/play.png" class="control"> </button>
		<button id="pauseButton" class="hidden" onclick="pause()"> <img id="pauseIcon" src="files/images/pause.png" class="control"> </button>
		<button id="stopButton" class="hidden" onclick="stop()"> <img id="stopIcon" src="files/images/stop.png" class="control"> </button>

		<!--to pass setting variables to javascript-->
		<input type="checkbox" id="breakReminder" name="break_reminder" class="hidden" checked="<?php echo $settings['break_reminder']; ?>">
		<input id="breakReminderTime" name="break_reminder_time" class="hidden" value="<?php echo $settings['break_reminder_time']; ?>">
		<input id="longBreakTime" name="long_break_time" class="hidden" value="<?php echo $settings['long_break_time']; ?>">
		<input type="checkbox" id="pauseReminder" name="pause_reminder" class="hidden" checked="<?php echo $settings['pause_reminder']; ?>">
		<input id="pauseTimeLimit" name="pause_time_limit" class="hidden" value="<?php echo $settings['pause_time_limit']; ?>">
		<input type="checkbox" id="playTickSound" name="play_tick_sound" class="hidden" checked="<?php echo $settings['play_tick_sound']; ?>">
		<input id="shortBreakTime" name="short_break_time" class="hidden" value="<?php echo $settings['short_break_time']; ?>">
		<input id="timerMode" name="timer_mode" class="hidden" value="<?php echo $settings['timer_mode']; ?>">
		<input id="workTime" name="work_time" class="hidden" value="<?php echo $settings['work_time']; ?>">
		<br><br><button id="volumeButton" onclick="volumeButtonClicked()"> <img id="soundIcon" width="18px"> </button>
		<input type="range" id="volumeSlider" min="0" max="100" value="100">

	</div>

	<script type="text/javascript" src="files/js/scripts.js"></script>
	<script type="text/javascript" src="files/js/time.js"></script>
	<script type="text/javascript" src="files/js/timer.js"></script>
	<script type="text/javascript" src="files/js/pomodoro.js"></script>

</body>

</html>