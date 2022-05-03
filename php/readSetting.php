<?php

//start session
session_start();

//create connection to database
$sqlConnection = new mysqli("localhost", "mysql", "mysql", "my_timer") or die("Failed to connect to database: $sqlConnection->connect_error");

//get settings from database
$user_id = $_SESSION["userID"];
$query = "select break_reminder, break_reminder_time, long_break_time, pause_reminder, pause_time_limit, play_tick_sound, short_break_time, timer_mode, volume, work_time from settings where user_id = $user_id;";
$result = $sqlConnection->query($query) or die("Failed to read database: $sqlConnection->error");
$settings = $result->fetch_assoc();

echo '{"break_reminder": ' .$settings['break_reminder']
    .',"break_reminder_time": "' .$settings['break_reminder_time'] .'"'
    .',"long_break_time": "' .$settings['long_break_time'] .'"'
    .',"pause_reminder": ' .$settings['pause_reminder']
    .',"pause_time_limit": "' .$settings['pause_time_limit'] .'"'
    .',"play_tick_sound": ' .$settings['play_tick_sound']
    .',"short_break_time": "' .$settings['short_break_time'] .'"'
    .',"timer_mode": "' .$settings['timer_mode'] .'"'
    .',"volume": ' .$settings['volume']
    .',"work_time": "' .$settings['work_time'] .'"}';

//close database connection
$sqlConnection->close();