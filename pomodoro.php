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

//get task list from database
$select_task_query = "Select name, max(date_started) from session, task where task.user_id = $user_id and task_id = task.id group by name order by max(date_started);";
$result = $sqlConnection->query($select_task_query);
$tasklist = $result->fetch_all();

//close connection to database
$sqlConnection->close();

?>

<!DOCTYPE html>

<html lang="en">

<head>

	<title>My Timer</title>

	<link rel="icon" href="img/my-timer-icon.ico">
	<link rel="stylesheet" type="text/css" href="css/styles.css">
	<link rel="stylesheet" type="text/css" href="css/pomodoro.css">

</head>

<body>

	<div id="contextMenu" class="context-menu hidden">

		<div class="context-menu-item" onclick="jumpToReminder()">Jump to <span id="breakReminderTimeSpan"></span></div>
		<div class="context-menu-item" onclick="skipPhase()">Next phase</div>

	</div>

	<button id="accountButton" onclick="show(accountPanel)" class="right"> <img id="accountIcon" alt="user icon" src="img/icons8-user-24.png"> </button>

	<div id="accountPanel" class="context-menu hidden right">
		
		<button id="accountButton" onclick="hide(accountPanel)"> <img id="accountIcon" alt="user icon" src="img/icons8-user-24.png"> </button>

		<p>
			<?php echo "$username"; ?>
		</p>
		
		<hr>
			
		<div onclick="setTimerMode('stopwatch');">
			
			Stopwatch mode

		</div>

		<div class="hidden">

			Countdown mode

		</div>

		<hr>

		<button onclick="logout();"><img id="logout icon" alt="logout button" src="img/logout.png"></button>

	</div>

	<div class="centered">

		<div id="phaseLabel">Work (1/4)</div>
	
		<div id="timerDiv">25:00</div>

        <p>
            <input id="taskName" autocomplete="off" list="taskList" onblur="this.placeholder = 'Unnamed task';" onchange="updateTaskName();" onfocus="this.placeholder = '';" placeholder="Unnamed task">
            <datalist id="taskList">
                <?php foreach(array_reverse($tasklist) as $task) echo "<option value='$task[0]'></option>" ?>
            </datalist>
        </p>

		<button id="startButton" onclick="start()"> <img id="startIcon" alt="play icon" src="img/play.png" class="control"> </button>
		<button id="pauseButton" class="hidden" onclick="pause()"> <img id="pauseIcon" alt="pause icon" src="img/pause.png" class="control"> </button>
		<button id="stopButton" class="hidden" onclick="stop()"> <img id="stopIcon" alt="stop icon" src="img/stop.png" class="control"> </button>

		<p>
            <button id="muteButton" onclick="muteButtonClicked()"><img id="soundIcon" alt="sound icon" width="18px"></button>
		    <input type="range" id="volumeSlider" min="0" max="100" onchange="volumeSliderChanged()">
        </p>

	</div>

	<!--Footer-->
	<footer>&copy Sherpad Ndabambi <span id="year"></span></footer>

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script type="text/javascript">window.jQuery || document.write("<script type='text/javascript' src='js/vendor/jquery-3.6.0.min.js'><\/script>")</script>
    <script type="text/javascript" src="js/time.js"></script>
    <script type="text/javascript" src="js/timer.js"></script>
	<script type="text/javascript" src="js/scripts.js"></script>
	<script type="text/javascript" src="js/pomodoro.js"></script>

</body>

</html>