<?php
//start session
session_start();

if(!$_SESSION["userID"]) header("location: login.php");

//create connection to database
$sqlConnection = new mysqli("localhost", "mysql", "mysql", "my_timer") or die("Connection failed: " .$sqlConnection->connect_error);

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

if(isset($_POST["logoutButton"])) logout();

if(isset($_POST["logoutButton"])) logout();

function logout(){
    $_SESSION["userID"] = null;
    header("location: login.php");
}
?>

<!DOCTYPE html>

<html lang="en">

<head>

    <title>Stopwatch - My Timer</title>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="author" content="Sherpad Ndabambi">

    <meta name="description" content="Track your work time using a stopwatch.">

    <meta property="og:image" content="img/my-timer-icon.ico">

    <meta property="og:title" content="Stopwatch - My Timer">

    <meta property="og:description" content="Track your work time using a stopwatch.">

    <meta name="twitter:title" content="Stopwatch - My Timer">

    <link rel="stylesheet" type="text/css" href="css/styles.css">

    <link rel="stylesheet" type="text/css" href="css/stopwatch.css">

</head>

<body>

    <div id="wrapper" class="container">

        <button id="accountButton" onclick="show(accountPanel)" class="top right"> <img id="accountIcon" alt="user icon" src="img/icons8-user-24.png"> </button>

        <div id="accountPanel" class="context-menu hidden top right">

            <button id="accountButton" onclick="hide(accountPanel)"> <img id="accountIcon" alt="user icon" src="img/icons8-user-24.png"> </button>

            <p>
                <?php echo "$username"; ?>
            </p>
            
            <hr>

            <div onclick="setTimerMode('pomodoro');">

                Pomodoro mode

            </div>

            <div class="hidden">

                Countdown mode

            </div>

            <hr>

            <button onclick="logout();"><img id="logout icon" alt="logout button" src="img/logout.png"></button>

        </div>

        <div id="timerDiv" class="container">00:00</div>

        <div id="taskNameDiv" class="container" onclick="showTaskNameDialogue();">Unnamed task</div>

        <div id="buttonContainer" class="container">

            <div id="pauseButtonContainer">

                <button id="pauseButton" disabled onclick="pause()"><img id="pauseIcon" alt="pause button" src="img/pause.png"></button>

            </div>

            <div id="startButtonContainer">

                <button id="startButton" onclick="start();"><img id="startIcon" alt="start button" src="img/play.png"></button>

            </div>

            <div id="stopButtonContainer" class="hidden">

                <button id="stopButton" onclick="stop();"><img id="stopIcon" alt="start button" src="img/stop.png"></button>

            </div>

            <div id="refreshButtonContainer">

                <button id="refreshButton" disabled onclick="refresh();"><img id="refreshIcon" alt="refresh button" src="img/refresh.png"></button>

            </div>

        </div>

        <div id="taskNameDialogue">

            <div>

                <span class="closeButton top right" onclick="closeTaskNameDialogue();">X</span>

                <p>
                    <label for="taskName">Edit task name:</label>
                </p>

                <p>
                    <input id="taskName" autocomplete="off" list="taskList" onblur="this.placeholder = 'Unnamed task'" onfocus="this.placeholder = '';" placeholder="Unnamed task">
                    <datalist id="taskList">
                        <?php foreach(array_reverse($tasklist) as $task) echo "<option value='$task[0]'></option>" ?>
                    </datalist>
                </p>

            </div>
            
            <p>
                <button onclick="updateTaskName();">Done</button>
            </p>

        </div>

        <div>
            <button id="muteButton" onclick="muteButtonClicked()"> <img id="soundIcon" alt="sound icon" width="18px"> </button>
		    <input type="range" id="volumeSlider" min="0" max="100" onchange="volumeSliderChanged()">
        </div>

        <!--Footer-->
        <footer>&copy Sherpad Ndabambi <span id="year"></span></footer>

    </div>

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script type="text/javascript">window.jQuery || document.write("<script type='text/javascript' src='js/vendor/jquery-3.6.0.min.js'><\/script>")</script>
    <script type="text/javascript" src="js/time.js"></script>
    <script type="text/javascript" src="js/timer.js"></script>
    <script type="text/javascript" src="js/scripts.js"></script>
    <script type="text/javascript" src="js/stopwatch.js"></script>

</body>

</html>
