<?php

//start session
session_start();

if($_SESSION["userID"] !== null) echo "User is logged in.";
else echo "User not logged in.";