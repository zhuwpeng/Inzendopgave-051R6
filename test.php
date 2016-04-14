<?php
include "inc/db_connect.php";
error_reporting(E_ALL & E_STRICT);
ini_set('display_errors', '1');
ini_set('log_errors', '0');
ini_set('error_log', './');

$tussen = "'t";
$addslash = addslashes($tussen);

$esc = mysqli_real_escape_string($connect, $tussen);


echo $tussen . " " . $esc;



?>

<!DOCTYPE html>
<html>
<head></head>
<body>
<?php 
$sports = array('tennis', 'voetbal', 'tafeltennis', 'biljart');
$sportSelectName = "sportonderdeel";
$sportSelectId = "form-sport";

echo '<select name="'. $sportSelectName . '" id="' . $sportSelectId . '">';
foreach($sports as $option) {
	echo '<option value="' . strtolower($option) . '">' . ucfirst(strtolower($option)) . '</option>';
}
echo '</select>';

?>
</body>
</html>
