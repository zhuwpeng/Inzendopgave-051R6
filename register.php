<?php
include "db_connect.php";
include "functions.php";

$naamError = "";
$emailError = "";
$message = "";
$errorMsg = "";
$errors = array('form'=>array('geennaam'=>"U heeft geen naam ingevuld.",
							'geenachterNm'=>"U heeft geen achternaam ingevuld.",
							'geenstraat'=>"U heeft geen straat ingevuld.",
							'geenpostcode'=>"U heeft geen postcode ingevuld",
							'geenwoonplts'=>"U heeft geen woonplaats ingevuld",
							'geenemail'=>"U heeft geen e-mail ingevuld",
							'ongeldigmail'=>"Geen geldig e-mail adress")
				);
$error = false;

$navbar = get_navbar();

//check if submit isset
if(isset($_POST['submit']) && $_POST['submit'] == 'Verstuur'){
			
	$naam = trim_stripslash($_POST['naam']);
	$tussenvoegsel = trim_stripslash($_POST['tussenvoegsel']);
	$achternaam = trim_stripslash($_POST['achternaam']);
	$straat = trim_stripslash($_POST['straat']);
	$huisnummer = trim_stripslash($_POST['huisnummer']);
	$woonplaats = trim_stripslash($_POST['woonplaats']);
	$email = trim_stripslash($_POST['email']);
	
	//Validate naam input
	if(empty($naam)){
		$naamError = "*Geen naam ingevuld!";
		$error = true;
	}
	
	//Validate achternaam
	if(empty($achternaam)){
		$achternmError = "*Geen naam ingevuld!";
		$error = true;
	}
	//Validate straat
	if(empty($straat)){
		$straatError = "*Geen naam ingevuld!";
		$error = true;
	}
	
	//Validate huisnummer
	if(empty($huisnummer)){
		$huisnrError = "*Geen naam ingevuld!";
		$error = true;
	}
	
	//Validate woonplaats
	if(empty($woonplaats)){
		$woonpltsError = "*Geen naam ingevuld!";
		$error = true;
	}
	
	//Validate email
	if(empty($email)){
		$emailError = "*Geen email ingevuld!";
		$error = true;
	}else{
		$check = test_email($email);
		if(!$check){
			$emailError = "*Ongeldige e-mail adres";
			$error = true;
		}
	}
	
	//Insert data into database
	if($error == false){
		$naamEsc = mysqli_real_escape_string($connect, $naam);
		$emailEsc = mysqli_real_escape_string($connect, $email);
		
		$insertQuery = "INSERT INTO users (userID, username, password, email) VALUES (NULL, '$usernameEsc', '$emailEsc')";
		$insertResult = mysqli_query($connect, $insertQuery) or die("Er is iets mis gegeaan tijdens het invoeren van gegevens."). error_log(mysqli_error($connect),3,"error_log.txt");;
		
		if($insertResult){
			if(mysqli_affected_rows($connect) == 1){
				header('Location: register_success.php');
				exit();
			}else{
				echo "Er is iets fout gegaan tijdens het registreren.";
				error_log(mysqli_error($connect),3,"error_log.txt");
			}
		}
	}
}

?>
<!DOCTYPE html>
<html>
<head>
<title>Inleveropgave 051R6</title>
	<link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<body>
<header>
<h1>Omnisportvereniging</h1>
</header>
<div id="container">
<span><?php echo $message;?></span>
<span class=error><?php echo $errorMsg;?></span>
	<div class="register-form">
		<h2>Aanmeld formulier</h2>
		<form method="POST" action="register.php">	
			<span class = error><?php echo $naamError;?></span>
			<label for="form-voornaam">Naam:</label>
			<input type="text" id="form-voornaam" name="voornaam" value="<?php if($error){echo htmlentities($_POST['voornaam']);}else{ echo "";}?>">

			<label for="form-tussenvoegsel">Tussenvoegsel:</label>
			<input type="text" id="form-tussenvoegsel" name="tussenvoegsel" value="<?php if($error){echo htmlentities($_POST['tussenvoegsel']);}else{ echo "";}?>">

			<label for="form-achternaam">Achternaam:</label>
			<input type="text" id="form-achternaam" name="achternaam" value="<?php if($error){echo htmlentities($_POST['achternaam']);}else{ echo "";}?>">

			<label for="form-straat">Straat:</label>
			<input type="text" id="form-straat" name="straat" value="<?php if($error){echo htmlentities($_POST['straat']);}else{ echo "";}?>">

			<label for="form-postcode">Postcode:</label>
			<input type="text" id="form-postcode" name="postcode" value="<?php if($error){echo htmlentities($_POST['postcode']);}else{ echo "";}?>">
			
			<label for="form-huisnummer">Huisnummer:</label>
			<input type="text" id="form-huisnummer" name="huisnummer" value="<?php if($error){echo htmlentities($_POST['huisnummer']);}else{ echo "";}?>">

			<label for="form-woonplaats">Woonplaats:</label>
			<input type="text" id="form-woonplaats" name="woonplaats" value="<?php if($error){echo htmlentities($_POST['woonplaats']);}else{ echo "";}?>">

			<span class = error><?php echo $emailError;?></span>
			<label for="form-email">E-mail:</label>
			<input type="text" id="form-email" name="email" value="<?php if($error){echo htmlentities($_POST['email']);}else{ echo "";}?>">
			
			<div class="geslacht">
				<label id = "1">Geslacht</label><br>
				<input type="radio" id="form-man" name="gender" value="m"><label for="form-man">Man</label>
				<input type="radio" id="form-vrouw" name="gender" value="v"><label for="form-vrouw">Vrouw</label>
			</div>
			
			<label for="form-sport">Sportonderdeel</label>
			<select name="sportonderdeel" id="form-sport">
				<option value="tennis">Tennis</option>
				<option value="voetbal">Voetbal</option>
				<option value="tafeltennis">Tafeltennis</option>
				<option value="Biljart">Biljart</option>
			</select>

			<label for="form-dag">Lesdag</label>
			<select name="lesdag" id="form-dag">
				<option value="maandag">Maandag</option>
				<option value="dinsdag">Dinsdag</option>
				<option value="woensdag">Woensdag</option>
				<option value="donderdag">Donderdag</option>
				<option value="vrijdag">Vrijdag</option>
			</select>
			
			<input type="submit" name="submit" value="Verstuur">
		</form>
	</div>
</div><!--end #container -->
</body>
</html>