<?php
include "db_connect.php";

$tussen = "'t";
$addslash = addslashes($tussen);

$esc = mysqli_real_escape_string($connect, $tussen);


echo $tussen . " " . $esc;