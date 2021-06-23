//declare global variables
let alarmSound = new Audio("files/sounds/alarm-sound.wav"), breakIteration, dateStarted, dateStopped, pauseTimer = new Timer(), phase, tickSound = new Audio("files/sounds/tick-sound.wav"), timeElapsed, timeLeft, timePaused, timer = new Timer(), timeStarted, workIteration;

//initialize setting variables
let breakReminder = true, breakReminderTime = new Time("0:01:30"), longBreakTime = new Time("0:20:00"), pauseReminder = true, pauseTimeLimit = new Time("0:02:00"), playTickSound = true, shortBreakTime = new Time("0:05:00"), workTime = new Time("0:25:00");

function displayTimer(){
	document.getElementById("phaseLabel").innerHTML = phase;
	updatePageTitle();
	document.getElementById("countdownLabel").innerHTML = timeLeft.toString("MMSS");
}

function initializeTimer(){
	breakIteration = 1;
	workIteration = 1;
	phase = "Work (1/4)";
	timeLeft = new Time(workTime.toString());
}

function InitiateNextPhase(){
	
}

function onPageLoad(){
	initializeTimer();
	displayTimer();
}

function pause(){
	alarmSound.play();
	hide(pauseButton);
	show(startButton);
	startButton.focus();
	timePaused = new Time();
	timer.stop();
	pauseTimer.start();
}

function start(){
	timer.start();
	if(pauseTimer.isRunning) pauseTimer.stop();
	alarmSound.play();
	tickSound.play();
	hide(startButton);
	show(pauseButton, stopButton);
	pauseButton.focus();
	if(workIteration == 1 && timeLeft.toString() == workTime.toString()){
		let date = new Date;
		dateStarted = date.getFullYear() + '/' + (date.getMonth() + 1) + '/' + date.getDate();
		timeStarted = new Time(date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds());
	}
}

function stop(){
}

function updatePageTitle(){
	if(document.getElementById("taskName").value) currentTask = document.getElementById("taskName").value;
	else currentTask = document.getElementById("taskName").placeholder;
	document.title = "[" + document.getElementById("countdownLabel").innerHTML + "] " + currentTask + " - My Timer";
}

timer.tick = function(){
	if(/Work/.test(phase)){
		if(playTickSound) tickSound.play();
		if(phase == "Extra Work Time"){
			timeElapsed.addSeconds(1);
			document.getElementById("countdownLabel").innerHTML = timeLeft.toString("MMSS");
		}
		else{
			timeLeft.addSeconds(-1);
			document.getElementById("countdownLabel").innerHTML = timeLeft.toString("MMSS");
			if(breakReminder && timeLeft == breakReminderTime){
				if(workIteration < 4) alert("Short break will start in " + timeLeft.toString("MMSS"));
				else alert("Long break will start in " + timeLeft.toString());
				if(timeLeft.toString() == "00:00:00") InitiateNextPhase();
			}
		}
	}
	else{
		timeLeft.addSeconds(-1);
		document.getElementById("countdownLabel").innerHTML = timeLeft.toString("MMSS");
		if(timeLeft.toString() == "00:00:00") InitiateNextPhase();
	}
}