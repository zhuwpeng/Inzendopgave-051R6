<?php
//connect to database
$host = "localhost";
$username = "user";
$password = "password";
$database = "dbloi";

$connect = mysqli_connect($host, $username, $password, $database)
or die("Failed to connect: dbLOI and required tables probably does not exist. Please run create_table.php.");
