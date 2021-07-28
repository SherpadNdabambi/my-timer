<!DOCTYPE html>

<html>

<head>

	<title>Login to My Timer</title>

	<link rel="icon" href="files/images/my-timer-icon.ico">
	<link rel="stylesheet" type="text/css" href="files/css/styles.css">

</head>

<body class="container">

	<form class="centered" method="post">
		
		<input name="email" placeholder="Email Address"><br><br>
		<input type="password" name="password" placeholder="Password"><br><br>
		<?php
			if(isset($_POST["submitButton"])){
				//create connection to database
				$sqlConnection = new mysqli("localhost", "root", "root", "my_timer") or die("Connection failed: " .$sqlConnection->connect_error);

				//get login details from form
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
				$sqlConnection->close();
			}
		?>
		<input type="submit" name="submitButton" value="Login">

	</form>

</body>

</html>