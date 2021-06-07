//Global variables
let alarmSound = new Audio("files/sounds/tick-sound.wav"), breakIteration = 1, startTime, tickSound = new Audio("files/sounds/alarm-sound.wav"), timeLeft, workIteration = 1, timeElapsed = new Time(), timePaused;

//Settings variables
let shortBreakTime = new Time("0:5:0"), workTime = new Time("0:25:0"), longBreakTime = new Time("0:20:0"), pauseTimeLimit = new Time("0:2:0"), reminderOn = true, remindTime = "0:1:30", tickSoundOn = true;

//Page events
function onPageLoad(){
	document.getElementById("phaseLabel").innerHTML = "Work (1/4)";
	timeLeft = workTime;
	document.getElementById("countdownLabel").innerHTML = timeLeft.toString("MMSS");
	updatePageTitle();
	document.getElementById("playIcon").style.display = "block";
	document.getElementById("pauseIcon").style.display = "none";
	document.getElementById("stopIcon").style.display = "none";
	document.getElementById("playButton").focus();
	document.getElementById("playButton").disabled = false;
	document.getElementById("pauseButton").disabled = true;
	document.getElementById("stopButton").disabled = true;
}

function onPageUnload(){
	logSession();
}

//Functions
function updatePageTitle(){
	if(document.getElementById("taskName").value) currentTask = document.getElementById("taskName").value;
	else currentTask = document.getElementById("taskName").placeholder;
	document.title = "[" + document.getElementById("countdownLabel").innerHTML + "] " + currentTask + " - My Timer";
}

function logSession(){
	timeElapsed = calculateElapsedTime();
	if(timeElapsed.toString() != "00:00:00"){
		document.getElementById("stop_time").value = new Date().toLocaleTimeString();
		document.getElementById("time_elapsed").value = timeElapsed.toString();
		document.getElementById("sessionForm").submit();
	}
}

function calculateElapsedTime(){
	timeElapsed.AddHours((workIteration - 1) * workTime.hours());
	timeElapsed.AddMinutes((workIteration - 1) * workTime.minutes());
	timeElapsed.AddSeconds((workIteration - 1) * workTime.seconds());

	if(document.getElementById("phaseLabel").innerHTML.includes("Work")) {if(document.getElementById("phaseLabel").innerHTML != "Extra Work Time") timeElapsed.addTime(workTime.minus(timeLeft));}
	else timeElapsed.addTime(workTime);

	return timeElapsed;
}

//Control events
function play(){
	document.getElementById("playIcon").style.display = "none";
	document.getElementById("pauseIcon").style.display = "block";
	document.getElementById("stopIcon").style.display = "block";
	document.getElementById("pauseButton").focus();
	document.getElementById("playButton").disabled = true;
	document.getElementById("pauseButton").disabled = false;
	document.getElementById("stopButton").disabled = false;

}

function pause(){
	document.getElementById("playIcon").style.display = "block";
	document.getElementById("pauseIcon").style.display = "none";
	document.getElementById("stopIcon").style.display = "none";
	document.getElementById("playButton").focus();
	document.getElementById("playButton").disabled = false;
	document.getElementById("pauseButton").disabled = true;
	document.getElementById("stopButton").disabled = true;
}

function stop(){
	document.getElementById("playIcon").style.display = "block";
	document.getElementById("pauseIcon").style.display = "none";
	document.getElementById("stopIcon").style.display = "none";
	document.getElementById("playButton").focus();
	document.getElementById("playButton").disabled = false;
	document.getElementById("pauseButton").disabled = true;
	document.getElementById("stopButton").disabled = true;
}