<?php

$host="localhost";

$username="root";

$password="root";

$db_name="my_timer";

$tbl_name="session";

$conn = mysql_connect("$host", "$username", "$password")or die("cannot connect");

mysql_select_db("$db_name")or die("cannot select DB");

$myusername=$_POST['usr'];

$mypassword=$_POST['pwd'];

?>