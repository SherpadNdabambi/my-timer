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

		<button id="playButton" onclick="play()"> <img id="playIcon" src="files/images/play.png" class="btn"> </button>
		<button id="pauseButton" onclick="pause()"> <img id="pauseIcon" src="files/images/pause.png" class="btn"> </button>
		<button id="stopButton" onclick="stop()"> <img id="stopIcon" src="files/images/stop.png" class="btn"> </button>

	</div>

	<script type="text/javascript" src="files/scripts/time.js"></script>
	<script type="text/javascript" src="files/scripts/index.js"></script>

</body>

</html>