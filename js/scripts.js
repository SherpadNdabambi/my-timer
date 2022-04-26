//get DOM elements
const accountPanel = document.querySelector("#accountPanel"), breakReminderTimeSpan = document.querySelector("#breakReminderTimeSpan"), contextMenu  = document.querySelector("#contextMenu"), pauseButton = document.querySelector("#pauseButton"), soundIcon = document.querySelector("#soundIcon"), startButton = document.querySelector("#startButton"), stopButton = document.querySelector("#stopButton"), taskNameDialogue = document.querySelector("#taskNameDialogue"), timerDiv = document.querySelector("#timerDiv"), volumeSlider = $("#volumeSlider");

//declare global variables
let alarmSound = new Audio("sounds/alarm-sound.wav"), dateStarted, dateStopped, pauseReminderCount = 0, pauseTimer = new Timer(), taskName = "Unnamed task", tickSound = new Audio("sounds/tick-sound.wav"), timePaused = new Time(), timer = new Timer(), timeStarted, timeStopped, timeWorked;

//declare setting variables
let breakReminder, breakReminderTime, longBreakTime, pauseReminder, pauseTimeLimit, playTickSound, shortBreakTime, timerMode, volume, workTime;

document.addEventListener('click', function(){

	if(contextMenu)
        if(isVisible(contextMenu)) hide(contextMenu);

    if(isVisible(accountPanel)){
        
        //declare local variables
        let mousePosition = {
            x: event.clientX,
            y: event.clientY
        }
        
        if(!isWithin(accountPanel, mousePosition)) hide(accountPanel);
    }
});

//add context menu
function addContextMenu(){
	  document.addEventListener('contextmenu', function(e){
  		breakReminderTimeSpan.innerHTML = breakReminderTime.toString("MMSS");
    	show(contextMenu);
    	e.preventDefault();
  		contextMenu.style.setProperty('--mouse-x', event.clientX + 'px');
  		contextMenu.style.setProperty('--mouse-y', event.clientY + 'px');
  	}, false);
}

function calculateTimeStarted(){
    let dateTime = currentDateTime();
    dateStarted = dateTime.date;
    timeStarted = dateTime.time;
}

function calculateTimeStopped(){
    let dateTime = currentDateTime();
    dateStopped = dateTime.date;
    timeStopped = dateTime.time;
}

function closeTaskNameDialogue(){
    taskNameDialogue.style.display = "none";
}

function currentDateTime(){
    let date = new Date, time = new Time(date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds());

    return {
        "date": date.getFullYear() + '/' + (date.getMonth() + 1) + '/' + date.getDate(),
        "time": time.toString()
    }
}

function endSession(){
    timer.stop();
    saveSession().then(() => {
        initializeTimer();
        displayTimer();
    });
    displayButtons();
}

function getSettingVariables(){
    return new Promise((resolve) => {
        //declare local variables
        let settings;

        //get settings from database
        $.get("php/readSetting.php", (message) =>{
            settings = JSON.parse(message);
        }).then(() => {
            //assign settings to setting variables
            breakReminder = settings.break_reminder;
            breakReminderTime = new Time(settings.break_reminder_timer);
            longBreakTime = new Time(settings.long_break_time);
            pauseReminder = settings.pause_reminder;
            pauseTimeLimit = new Time(settings.pause_time_limit);
            playTickSound = settings.play_tick_sound;
            shortBreakTime = new Time(settings.short_break_time);
            timerMode = settings.timer_mode;
            volume = settings.volume / 100;
            workTime = new Time(settings.work_time);
            resolve();
        });
    });
}

function hide(){
    for(let i = 0; i < arguments.length; i++) arguments[i].setAttribute("class", (arguments[i].getAttribute("class") === '') ? "hidden" : arguments[i].getAttribute("class") + " hidden");
}

function isWithin(element, mousePosition){
    return (element.offsetLeft < mousePosition.x) && (mousePosition.x < (element.offsetLeft + element.offsetWidth)) && (element.offsetTop < mousePosition.y) && (mousePosition.y < (element.offsetTop + element.offsetHeight))
}

function isVisible(element){
    return !element.classList.contains("hidden");
}

function logout(){
    if(confirm("Are you sure you want to logout? Your current session will be saved.")) {
        $.post("php/logout.php");
        window.location.href = "index.php";
    }
}

function muteButtonClicked(){
    if(volume === 0){
        volume = volumeSlider.val() / 100;
        volumeSlider.disabled = false;
    }
    else{
        volume = 0;
        volumeSlider.disabled = true;
    }
    setSoundIcon();
    setVolume();
}

function remind(message){
    let speech = new SpeechSynthesisUtterance(message);
    speech.rate = 0.9;
    speech.volume = volume;
    speechSynthesis.speak(speech);
    alert(message);
}

function saveSession(){
    return new Promise((resolve) => {
        calculateTimeStopped();
        $.post("php/createSession.php",
            {
                date_started: dateStarted,
                date_stopped: dateStopped,
                task_name: taskName,
                time_started: timeStarted,
                time_stopped: timeStopped,
                time_worked: timeWorked.toString(),
                timer_mode: timerMode
            }).then(() => {
                resolve();
            });
    });
}

function setSoundIcon(){
    if(volumeSlider.val() === 0) soundIcon.src = "img/icons8-mute-50.png";
    else soundIcon.src = "img/icons8-audio-50.png";
}

function setTimerMode(mode){
    if(confirm(`Switch to ${mode} mode? Your current session will be saved.`)) {
        $.post("php/updateTimerMode.php", {timer_mode: mode});
        window.location.href = "index.php";
    }
}

function setVolume(){
    alarmSound.volume = volume;
    tickSound.volume = volume;
    $.post("php/updateVolume.php", {volume: (volume * 100)});
}

function setYear(){
    let date = new Date(), year = date.getFullYear();
    $("#year").text(year.toString());
}

function show(){
    for(let i = 0; i < arguments.length; i++) arguments[i].setAttribute("class", arguments[i].getAttribute("class").replace(" hidden", '')) || arguments[i].setAttribute("class", arguments[i].getAttribute("class").replace("hidden", ''));
}

function showTaskNameDialogue(){
    taskNameDialogue.style.display = "flex";
}

function stop(){
    if(confirm("Are you sure you want to end this session?")){
        if(timer.isRunning) timer.stop();
        else pauseTimer.stop();
        alarmSound.play();
        endSession();
    }
}

function updateSession(){
    calculateTimeStopped();
    $.post("php/updateSession.php",
        {
            date_stopped: dateStopped,
            task_name: taskName,
            time_stopped: timeStopped,
            time_worked: timeWorked.toString(),
        });
}

function volumeSliderChanged(){
    volume = volumeSlider.val() / 100;
    setVolume();
}

pauseTimer.tick = function(){
    timePaused.addSeconds(1);
    updatePageTitle();
    if(pauseReminder && timePaused.toString() === pauseTimeLimit.times(pauseReminderCount + 1).toString()){
        remind("Your session has been paused for " + pauseTimeLimit.times(pauseReminderCount + 1).inWords());
        pauseReminderCount++;
    }
    updateSession();
    if(document.activeElement.toString() !== "[object HTMLInputElement]") startButton.focus();
}