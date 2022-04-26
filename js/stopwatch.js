//get DOM elements
const refreshButton = document.querySelector("#refreshButton"), startButtonContainer = document.querySelector("#startButtonContainer"), stopButtonContainer = document.querySelector("#stopButtonContainer"), taskNameDiv = document.querySelector("#taskNameDiv");

//to hide task name dialogue when user clicks outside
/*$(document).click(() => {

    if(taskNameDialogue.style.display !== "none"){
        //declare local variables
        let mousePosition = {
            x: event.clientX,
            y: event.clientY
        }

        if(!isWithin(taskNameDialogue, mousePosition)) closeTaskNameDialogue();
    }
});*/

//page load function
$(document).ready(function(){
    getSettingVariables().then(() => {
        initializeTimer();
        displayTimer();
        setSoundIcon();
        setVolume();
        volumeSlider.val(volume * 100);
    });
    setYear();
});

function displayButtons(){
    hide(stopButtonContainer);
    show(startButtonContainer);
    startButton.focus();
}

function displayTimer(){
    timerDiv.innerHTML = timeWorked.toString("MMSS");
    updatePageTitle();
}

function initializeTimer(){
    timeWorked = new Time();
}

function pause(){
    alarmSound.play();
    pauseButton.disabled = "disabled";
    hide(stopButtonContainer);
    show(startButtonContainer);
    startButton.focus();
    timePaused = new Time();
    timer.stop();
    pauseTimer.start();
}

function start(){
    //check if user is logged in
    //...some code

    timer.start();
    if(pauseTimer.isRunning){
        pauseTimer.stop();
        pauseReminderCount = 0;
    }
    alarmSound.play();
    tickSound.play();
    hide(startButtonContainer);
    show(stopButtonContainer);
    pauseButton.removeAttribute("disabled");
    pauseButton.focus();
    if(refreshButton.disabled) refreshButton.removeAttribute("disabled");
    if(timeWorked.toString() === "00:00:00"){
        calculateTimeStarted();
        saveSession();
    }
}

function updatePageTitle(){
    document.title = "[" + timeWorked.toString("MMSS") + "] " + taskName + " - Stopwatch Timer";
}

function updateTaskName(){
    taskNameDiv.innerText = taskName = $("#taskName").val();
    closeTaskNameDialogue();
}

timer.tick = () => {
    timeWorked.addSeconds(1);
    if(playTickSound) tickSound.play();
    timerDiv.innerText = timeWorked.toString("MMSS");
    updatePageTitle();
    updateSession();
    if(document.activeElement.toString() !== "[object HTMLInputElement]") pauseButton.focus();
}