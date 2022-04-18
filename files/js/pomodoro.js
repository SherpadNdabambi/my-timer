//declare global variables
let alarmSound = new Audio("files/sounds/alarm-sound.wav"), breakIteration, extraTimeWorked = new Time(), pauseReminderCount = 0, pauseTimer = new Timer(), phase, tickSound = new Audio("files/sounds/tick-sound.wav"), timeLeft, timePaused = new Time(), timer = new Timer(), timeWorked = new Time(), workIteration;

//get setting variables
let breakReminder = document.getElementById("breakReminder").checked, breakReminderTime = new Time(document.getElementById("breakReminderTime").value), longBreakTime = new Time(document.getElementById("longBreakTime").value), pauseReminder = document.getElementById("pauseReminder").checked, pauseTimeLimit = new Time(document.getElementById("pauseTimeLimit").value), playTickSound = document.getElementById("playTickSound").checked, shortBreakTime = new Time(document.getElementById("shortBreakTime").value), volume = volumeSlider.value / 100, workTime = new Time(document.getElementById("workTime").value);

//To submit form without reloading page
$(document).ready(function(){
	$("#sessionForm").submit(function(){
		$.post($(this).attr("action"), $(this).serializeArray());
		return false;
	});
});

function addContextMenu(){
	  document.addEventListener('contextmenu', function(e){
  		breakReminderTimeSpan.innerHTML = breakReminderTime.toString("MMSS");
    	show(contextMenu);
    	e.preventDefault();
  		contextMenu.style.setProperty('--mouse-x', event.clientX + 'px');
  		contextMenu.style.setProperty('--mouse-y', event.clientY + 'px');
  	}, false);
  	document.addEventListener('click', function(){
  		hide(contextMenu);
	});
}

function calculateTimeStarted(){
	dateStarted.value = currentDateTime().split(' ')[0];
	timeStarted.value = currentDateTime().split(' ')[1];
}

function calculateTimeStopped() {
	dateStopped.value = currentDateTime().split(' ')[0];
	timeStopped.value = currentDateTime().split(' ')[1];
}

function currentDateTime(){
	let date = new Date;
	return date.getFullYear() + '/' + (date.getMonth() + 1) + '/' + date.getDate() + ' ' + new Time(date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds());
}

function displayButtons(){
	hide(pauseButton);
	hide(stopButton);
	show(startButton);
	startButton.focus();
}

function displayTimer(){
	phaseLabel.innerHTML = phase;
	updatePageTitle();
	countdownLabel.innerHTML = timeLeft.toString("MMSS");
}

function endSession(){
	timer.stop();
	saveSession();
	initializeTimer();
	displayTimer();
	displayButtons();
}

function initializeTimer(){
	breakIteration = 1;
	workIteration = 1;
	phase = "Work (1/4)";
	timeLeft = new Time(workTime.toString());
	timeWorked = new Time();
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
			timeLeft = new Time(workTime.toString());
			timer.start();
			breakIteration++;
		}
		else endSession();
	}
}

function jumpToReminder(){
	let jumpTime = new Time(breakReminderTime.toString());
	jumpTime.addSeconds(1);
	timeLeft = jumpTime;
	countdownLabel.innerHTML = timeLeft.toString("MMSS");
}

function muteButtonClicked(){
	if(volume == 0){
		volume = volumeSlider.value;
		volumeSlider.disabled = false;
	}
	else{
		volume = 0;
		volumeSlider.disabled = true;
	}
	setSoundIcon();
	setVolume();
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

function remind(message){
	let speech = new SpeechSynthesisUtterance(message);
	speech.rate = 0.9;
	speechSynthesis.speak(speech);
	alert(message);
}

function saveSession(){
	calculateTimeStopped();
	document.getElementById("timeWorked").value = timeWorked;
	if(!taskName.value) taskName.value = taskName.placeholder;
	submitSessionForm.click();
}

function setSoundIcon(){
	if(volume == 0) soundIcon.src = "files/images/icons8-mute-50.png";
	else soundIcon.src = "files/images/icons8-audio-50.png";
}

function setVolume(){
	alarmSound.volume = volume;
	tickSound.volume = volume;
	$.post("files/php/updateVolume.php", {volume: $("#volumeSlider").val()});
}

function skipPhase(){
	timeLeft = new Time("0:0:1");
}

function start(){
	timer.start();
	if(pauseTimer.isRunning){
		pauseTimer.stop();
		pauseReminderCount = 0;
	}
	alarmSound.play();
	tickSound.play();
	hide(startButton);
	show(pauseButton, stopButton);
	pauseButton.focus();
	if(workIteration == 1 && timeLeft.toString() == workTime.toString()){
		calculateTimeStarted();
		saveSession();
	}
}

function stop(){
	if(confirm("Are you sure you want to end this session?")){
		if(pauseTimer.isRunning){
			pauseTimer.stop();
		}
		alarmSound.play();
		timer.stop();
		endSession();
	}
}

function updatePageTitle(){
	if(document.getElementById("taskName").value) currentTask = document.getElementById("taskName").value;
	else currentTask = document.getElementById("taskName").placeholder;
	document.title = "[" + timeLeft.toString("MMSS") + "] " + currentTask + " - My Timer";
}

function updateSession(){
	calculateTimeStopped();
	document.getElementById("timeWorked").value = timeWorked;
	$.post("files/php/updateSession.php", $("#sessionForm").serializeArray());
}

function updateTaskName(){
	document.getElementById("task_name").value = document.getElementById("taskName").value;
}
function volumeSliderChanged(){
	volume = volumeSlider.value / 100;
	setVolume();
}

pauseTimer.tick = function(){
	timePaused.addSeconds(1);
	updatePageTitle();
	if(pauseReminder && timePaused.toString() == pauseTimeLimit.times(pauseReminderCount + 1).toString()){
		remind("Your session has been paused for " + pauseTimeLimit.times(pauseReminderCount + 1).inWords());
		pauseReminderCount++;
	}
	updateSession();
	if(document.activeElement != "[object HTMLInputElement]") startButton.focus();
}

timer.tick = function(){
	if(/Work/.test(phase)){
		timeWorked.addSeconds(1);
		if(playTickSound) tickSound.play();
		if(phase == "Extra Work Time"){
			extraTimeWorked.addSeconds(1);
			countdownLabel.innerHTML = extraTimeWorked.toString("MMSS");
		}
		else{
			timeLeft.addSeconds(-1);
			countdownLabel.innerHTML = timeLeft.toString("MMSS");
			if(breakReminder && timeLeft.toString() == breakReminderTime.toString()){
				if(workIteration < 4) remind("Short break will start in " + timeLeft.inWords());
				else remind("Long break will start in " + timeLeft.inWords());
			}
			if(timeLeft.toString() == "00:00:00") initiateNextPhase();
		}
	}
	else{
		timeLeft.addSeconds(-1);
		countdownLabel.innerHTML = timeLeft.toString("MMSS");
		if(timeLeft.toString() == "00:00:00") initiateNextPhase();
	}
	updatePageTitle();
	updateSession();
	if(document.activeElement != "[object HTMLInputElement]") pauseButton.focus();
}

setSoundIcon();
setVolume();