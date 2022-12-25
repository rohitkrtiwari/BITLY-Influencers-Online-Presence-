<?php
/*
This file contains database configuration assuming you are running mysql using user "root" and password ""
*/

$db_server = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'users';

// Try connecting to the Database
$conn = mysqli_connect($db_server, $db_username, $db_password, $db_name);

//Check the connection
if($conn == false){
    dir('Error: Cannot connect');
}

?>

