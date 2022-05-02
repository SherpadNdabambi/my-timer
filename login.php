<!DOCTYPE html>

<html lang="en">

<head>

    <!--page title-->
    <title>Login to My Timer</title>

    <!--character encoding-->
    <meta charset="utf-8">

    <!--device width-->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--page info-->
    <meta name="author" content="Sherpad Ndabambi">
    <meta name="description" content="My Timer allows you to track your work time using a pomodoro timer, stopwatch, or countdown timer.">

    <!--og properties-->
    <meta property="og:image" content="img/my-timer-icon.ico">
    <meta property="og:title" content="My Timer">
    <meta property="og:description" content="My Timer allows you to track your work time using a pomodoro timer, stopwatch, or countdown timer.">

    <!--Twitter cards-->
    <meta name="twitter:title" content="My Timer">

    <!--favicon-->
    <link rel="icon" href="img/my-timer-icon.ico">

    <!--stylesheets-->
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <link rel="stylesheet" type="text/css" href="css/login.css">

</head>

<body>

	<main class="container">
		
		<div id="loginForm" class="centered">
		
			<input name="email" placeholder="Email Address"><br><br>
			<input type="password" name="password" placeholder="Password"><br><br>
			<?php
				if(isset($_POST['loginButton'])){
                    //store connection parameters in constants
                    define("DB_HOST", "localhost");
                    define("DB_NAME", "my_timer");
                    define("DB_USER", "mysql");
                    define("DB_PASSWORD", "mysql");

                    try{
                        //establish a connection
                        $connection = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME,DB_USER,DB_PASSWORD);
                    }
                    catch (Exception $ex){
                        echo $ex->getMessage();
                    } finally {
                        $connection = null;
                    }
					//create connection to database
					//$sqlConnection = new mysqli("localhost", "root", "root", "my_timer");// or die("Connection failed: " .$sqlConnection->connect_error);
                    echo "PHP OK";
					/*//get login details from form
					$email = $_POST["email"];
					$password = $_POST["password"];

					//to prevent sql injection
					$email = stripslashes($email);
					$password = stripslashes($password);

					$select_user_query = "select * from user where email = '$email' and password = '$password'";
					$result = $sqlConnection->query($select_user_query);

					if ($result->num_rows == 1){
						$select_userID_query = "select id from user where email = '$email' and password = '$password'";
						$result = $sqlConnection->query($select_userID_query);
						session_start();
						$_SESSION["userID"] = $result->fetch_assoc()["id"];
						header("location: index.php");
					}
					else{
						echo "<span style='color: red'> Invalid email address and/or password. </span><br><br>";
					}

					//close connection to database
					$sqlConnection->close();*/
				}
			?>
			<input type="submit" name="loginButton" value="Login">

		</div>

	</main>

</body>

</html>