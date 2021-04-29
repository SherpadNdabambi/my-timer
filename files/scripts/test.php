<?php

$sqlConnection = mysqli_connect("127.0.0.1", "root", "root", "my_timer") or die ("Could not connect to databse: " .mysqli_connect_error());
if($sqlConnection) echo "Connection to database successful";
//mysql_close($sqlConnection);

?>