//declare global variables
let alarmSound = new Audio("files/sounds/alarm-sound.wav"), breakIteration, dateStarted, dateStopped, pauseTimer = new Timer(), phase, tickSound = new Audio("files/sounds/tick-sound.wav"), timeWorked, timeLeft, timePaused = new Time(), timer = new Timer(), timeStarted, workIteration;

//initialize setting variables
let breakReminder = true, breakReminderTime = new Time("0:01:30"), longBreakTime = new Time("0:20:00"), pauseReminder = true, pauseTimeLimit = new Time("0:02:00"), playTickSound = true, shortBreakTime = new Time("0:05:00"), workTime = new Time("0:25:0");

function addContextMenu() {
	  document.addEventListener('contextmenu', function(e) {
  		document.getElementById("breakReminderTime").innerHTML = breakReminderTime.toString("MMSS");
    	show(contextMenu);
    	e.preventDefault();
  		contextMenu.style.setProperty('--mouse-x', event.clientX + 'px');
  		contextMenu.style.setProperty('--mouse-y', event.clientY + 'px');
  	}, false);
  	document.addEventListener('click', function(){
  		hide(contextMenu);
	});
}

function calculateTimeWorked(){
	timeWorked = new Time();
	timeWorked.hours = (workIteration - 1) * workTime.hours;
	timeWorked.minutes = (workIteration - 1) * workTime.minutes;
	timeWorked.seconds = (workIteration - 1) * workTime.seconds;
	if(/Work/.test(phase)) if(phase = "Extra Work Time") timeWorked = timeWorked.plus(workTime);
	else timeWorked = timeWorked.plus(workTime.minus(timeLeft));
}

function displayTimer(){
	phaseLabel.innerHTML = phase;
	updatePageTitle();
	countdownLabel.innerHTML = timeLeft.toString("MMSS");
}

function endSession(){
	timer.stop();
	calculateElapsedTime();
	sessionForm.submit();
	initializeTimer();
	displayTimer();
}

function initializeTimer(){
	breakIteration = 1;
	workIteration = 1;
	phase = "Work (1/4)";
	timeLeft = new Time(workTime.toString());
}

function initiateNextPhase(){
	timer.stop();
	alarmSound.play();
	if(/Work/.test(phase)){
		if(workIteration < 4){
			phase = "Short Break (" + breakIteration + "/3)";
			phaseLabel.innerHTML = phase;
			timeLeft = new Time(shortBreakTime.toString());
			workIteration++;
		}
		else{
			if(confirm("Your session is complete. Do you wish to continue working?")){
				phase = "Extra Work Time";
				phaseLabel.innerHTML = phase;
				workIteration++;
			}
			else{
				phase = "Long Break";
				phaseLabel.innerHTML = phase;
				timeLeft = new Time(longBreakTime.toString());
			}
		}
		timer.start();
	}
	else{
		if(breakIteration < 4){
			phase = "Work (" + workIteration + "/4)";
			phaseLabel.innerHTML = phase;
			timeLeft = new Time(workTime);
			timer.start();
			breakIteration++;
		}
		else endSession();
	}
}

function jumpToReminder() {
	let jumpTime = new Time(breakReminderTime.toString());
	jumpTime.addSeconds(1);
	timeLeft = jumpTime;
	countdownLabel.innerHTML = timeLeft.toString("MMSS");
}

function onPageLoad(){
	initializeTimer();
	displayTimer();
	addContextMenu();
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

function skipPhase() {
	timeLeft = new Time("0:0:1");
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

pauseTimer.tick = function(){
	timePaused.addSeconds(1);
	if(timePaused.toString() == pauseTimeLimit.toString()){
		alert("Your session has been paused for " + pauseTimeLimit);
		timePaused = new Time();
	}
}

timer.tick = function(){
	if(/Work/.test(phase)){
		if(playTickSound) tickSound.play();
		if(phase == "Extra Work Time"){
			timeWorked.addSeconds(1);
			countdownLabel.innerHTML = timeLeft.toString("MMSS");
		}
		else{
			timeLeft.addSeconds(-1);
			countdownLabel.innerHTML = timeLeft.toString("MMSS");
			if(breakReminder && timeLeft.toString() == breakReminderTime.toString()){
				if(workIteration < 4) alert("Short break will start in " + timeLeft.toString("MMSS"));
				else alert("Long break will start in " + timeLeft.toString());
			}
			if(timeLeft.toString() == "00:00:00") initiateNextPhase();
		}
	}
	else{
		timeLeft.addSeconds(-1);
		countdownLabel.innerHTML = timeLeft.toString("MMSS");
		if(timeLeft.toString() == "00:00:00") initiateNextPhase();
	}
}