<?php
session_start();
if(!$_SESSION["username"]) header("location: login.html");
?>

<!DOCTYPE html>

<html>

<head>

	<title>My Timer</title>

	<link rel="stylesheet" type="text/css" href="files/styles/styles.css">

</head>

<body onload="onPageLoad()" onunload="onPageUnload()">

	<button id="accountButton" onclick="show()" class="top-right"> <img id="accountIcon" src="files/images/icons8-user-24.png"> </button>

	<div class="centered">

		<div id="phaseLabel">Work (1/4)</div>
	
		<div id="countdownLabel">25:00</div>
	
		<form id="sessionForm" action="files/logSession.php">

			<input id="taskName" name="taskName" onblur="this.placeholder = 'Unnamed Task'" onfocus="this.placeholder = ''" placeholder="Unnamed Task">

			<input id="start_date" name="start_date" class="hidden">
		
			<input id="start_time" name="start_time" class="hidden">
			<input id="stop_time" name="stop_time" class="hidden">
			<input id="time_elapsed" name="time_elapsed" class="hidden">

		</form>

		<button id="playButton" onclick="play()"> <img id="playIcon" src="files/images/play.png" class="control"> </button>
		<button id="pauseButton" onclick="pause()"> <img id="pauseIcon" src="files/images/pause.png" class="control"> </button>
		<button id="stopButton" onclick="stop()"> <img id="stopIcon" src="files/images/stop.png" class="control"> </button>

	</div>

	<script type="text/javascript" src="files/scripts/time.js"></script>
	<script type="text/javascript" src="files/scripts/scripts.js"></script>
	<script type="text/javascript" src="files/scripts/index.js"></script>

</body>

</html>