<?php
include "db_connect.php";
include "functions.php";
//set_error_handler("error_msg");

$naamError = "";
$anaamError = "";
$straatError = "";
$postcodeError = "";
$huisnrError = "";
$woonpltsError = "";
$emailError = "";
$ingdateError = "";
$gebdateError = "";
$geslachtError = "";

$message = "";
$errorMsg = "";
$errors = array('form'=>array('gNaam'=>"U heeft geen naam ingevuld.",
							'gAnaam'=>"U heeft geen achternaam ingevuld.",
							'gStraat'=>"U heeft geen straat ingevuld.",
							'gHuisnr'=>"U heeft geen huisnummer ingevuld.",
							'gEmail'=>"U heeft geen e-mail ingevuld.",
							'gDatum'=>"U heeft geen datum opgegeven.",
							'gGeslacht'=>"Kies een geslacht.",
							'ongEmail'=>"Geen geldig e-mail adress.",
							'ongPostcode'=>"Geen geldig postcode.",
							'ongHuisnr'=>"Geen geldig huisnummer",
							'ongWoonplts'=>"Geen geldig woonplaats",
							'ongDatum'=>"Datum format moet dd-mm-yyyy (23-01-2000) zijn.",
							'toekDatum'=>"Datum ligt te ver in de toekomst",
							'naamEmail'=>"'naam' gedeelte van uw e-mail moet minimaal 2 letters bevatten ('naam'@'domein'.nl)",
							'domEmail'=>"'domein' gedeelte van uw e-mail moet minimaal 2 letters bevatten ('naam'@'domein'.nl)")
				);
$error = false;

//check of submit isset
if(isset($_POST['submit']) && $_POST['submit'] == 'Verstuur'){
			
	$naam = stripslashes(trim($_POST['naam']));
	$tussenvoegsel = stripslashes(trim(isset($_POST['tussenvoegsel'])?$_POST['tussenvoegsel']:NULL));
	$achternaam = stripslashes(trim($_POST['achternaam']));
	$gebDatum = stripslashes(trim(isset($_POST['gebdatum'])?$_POST['gebdatum']:NULL));
	$straat = stripslashes(trim(isset($_POST['straat'])?$_POST['straat']:NULL));
	$postcode = stripslashes(trim(isset($_POST['postcode'])?$_POST['postcode']:NULL));
	$huisnummer = stripslashes(trim(isset($_POST['huisnummer'])?$_POST['huisnummer']:NULL));
	$woonplaats = stripslashes(trim(isset($_POST['woonplaats'])?$_POST['woonplaats']:NULL));
	$email = stripslashes(trim(isset($_POST['email'])?$_POST['email']:NULL));
	$ingDatum = stripslashes(trim($_POST['ingdatum']));
	$geslacht = stripslashes(trim((isset($_POST['geslacht'])?$_POST['geslacht']:NULL)));
	
	//Valideer naam
	if(empty($naam)){
		$naamError = $errors['form']['gNaam'];
		$error = true;
	}
	
	//Valideer achternaam
	if(empty($achternaam)){
		$anaamError = $errors['form']['gAnaam'];
		$error = true;
	}
	
	//Valideer geboorte datum
	if(!empty($gebDatum)){
		$valgebDatum = date_validation($gebDatum);
	
		if($valgebDatum == false){
			$gebdateError = $errors['form']['ongDatum'];
			$error = true;
		}
	
		if($valgebDatum == "Toekomst"){
			$gebdateError = $errors['form']['toekDatum'];
			$error = true;
		}
	}else{
		$valgebDatum = NULL;
	}
	
	//Valideer postcode
	if(!empty($postcode)){
		$checkPostcode = test_postcode($postcode);
		if(!$checkPostcode){
			$postcodeError = $errors['form']['ongPostcode'];
			$error = true;
		}
	}

	//Valideer huisnummer
	if(!empty($huisnummer)){
		$checkHuisnr = test_huisnr($huisnummer);
		if(!$checkHuisnr){
			$huisnrError = $errors['form']['ongHuisnr'];
			$error = true;
		}
	}
	
	//Valideer woonplaats
	if(!empty($woonplaats)){
		$checkWoonplts = test_woonplaats($woonplaats);
		if(!$checkWoonplts){
		$woonpltsError = $errors['form']['ongWoonplts'];
		$error = true;
		}
	}
	
	//Valideer email
	if(empty($email)){
		$emailError = $errors['form']['gEmail'];
		$error = true;
	}else{
		$checkEmail = test_email($email);
		if($checkEmail=="ongeldig"){
			$emailError = $errors['form']['ongEmail'];
			$error = true;
		}elseif($checkEmail=="naam"){
			$emailError = $errors['form']['naamEmail'];
			$error = true;
		}elseif($checkEmail=="domein"){
			$emailError = $errors['form']['Email'];
			$error = true;
		}
	}
	
	//Valideer ingangsdatum
	if(empty($ingDatum)){
		$ingdateError = $errors['form']['gDatum'];
		$error = true;
	}else{
		$valIngDatum = date_validation($ingDatum);
		
		if($valIngDatum == false){
			$ingdateError = $errors['form']['ongDatum'];
			$error = true;
		}
		
		if($valIngDatum == "Toekomst"){
			$ingdateError = $errors['form']['toekDatum'];
			$error = true;
		}
	}
	
	//Valideer geslacht
	if(empty($geslacht)){
		$geslachtError = $errors['form']['gGeslacht'];
		$error = true;
	}
	
	//Insert data in database
	if($error == false){
		$naamEsc = mysqli_real_escape_string($connect, $naam);
		$tussenvoegsel = mysqli_real_escape_string($connect, $tussenvoegsel);
		$achternaamEsc = mysqli_real_escape_string($connect, $achternaam);
		$straatEsc = mysqli_real_escape_string($connect, $straat);
		$postcodeEsc = mysqli_real_escape_string($connect, $postcode);
		$huisnummerEsc = mysqli_real_escape_string($connect, $huisnummer);
		$woonplaatsEsc = mysqli_real_escape_string($connect, $woonplaats);
		$geslachtEsc = mysqli_real_escape_string($connect, $geslacht);
		$gebdatEsc = mysqli_real_escape_string($connect, $valgebDatum);
		$emailEsc = mysqli_real_escape_string($connect, $email);
		$sportonderdeelEsc = mysqli_real_escape_string($connect, $_POST['sportonderdeel']); 
		$lesdagEsc = mysqli_real_escape_string($connect, ucfirst($_POST['lesdag']));
		$ingdatEsc = mysqli_real_escape_string($connect, $valIngDatum);
		
		$ledenQuery = "INSERT INTO leden (ID, 
											Voornaam, 
											Tussenvoegsels, 
											Achternaam, 
											Straat, 
											Huisnummer, 
											Postcode, 
											Woonplaats,
											Email, 
											Geboortedatum, 
											Geslacht)
									VALUES (NULL, 
											'$naamEsc',
											'$tussenvoegsel',
											'$achternaamEsc',
											'$straatEsc',
											'$huisnummerEsc',
											'$postcodeEsc',
											'$woonplaatsEsc',
											'$emailEsc',
											'$valgebDatum',
											'$geslachtEsc')";
		
		$ledenResult = mysqli_query($connect, $ledenQuery) or die("Er is iets mis gegeaan tijdens het invoeren van gegevens.");
		
		if(mysqli_affected_rows($connect) == 1){
			//Informatie ophalen voor invoer lidmaatschap data
			$idQuery = "SELECT ID FROM leden WHERE Voornaam = '$naamEsc'";
			$idResult = mysqli_query($connect, $idQuery) or die("Kan gegevens niet ophalen uit database");
			
			if(mysqli_num_rows($idResult) > 0){
				$idData = mysqli_fetch_assoc($idResult);
				$ledenId = $idData['ID'];
				
				$lidmaatschapQuery = "INSERT INTO lidmaatschap (ID, 
																LedenID,
																Datumingang,
																Datumeinde,
																Sportonderdeel,
																Lesdag) VALUES 
																(NULL, 
																'$ledenId', 
																'$ingdatEsc', 
																NULL, 
																'$sportonderdeelEsc', 
																'$lesdagEsc');";
				$lidmaatschapResult = mysqli_query($connect, $lidmaatschapQuery);
				
				if(mysqli_affected_rows($connect) == 1){
					
					if(empty($tussenvoegsel)){
						$volNaam = $naam . " " . $achternaam;
					}else{
						$volNaam = $naam . " " . $tussenvoegsel . " " . $achternaam;
					}
					
					$bevestiging = mail($email, "Registratie",
					"Welkom bij Omnisport vereniging!\r\n
					Dit is een bevestigings e-mail van uw registratie.\r\n
					Hieronder nog het de registratie data:\r\n
					Naam: ". $volNaam . "\r\n
					Lidnummer:". $ledenId ."\r\n
					Sport: " . $sportonderdeelEsc . "\r\n
					Lesdag: " . $lesdagEsc .".\r\n
					Ingangsdatum: " . $ingDatum ."\r\n
					Heel erg bedankt voor uw registratie en tot dan!",
					"Van: info@omnisport.com");
					
					$administratie = mail("admin@hotmail.com", "Nieuwe lid",
					"De volgende gebruiker heeft zich geregistreerd bij Omnisport:\r\n
					Naam: ". $volNaam . "\r\n
					Lidnummer:". $ledenId ."\r\n
					Sport: " . $sportonderdeelEsc . "\r\n
					Lesdag: " . $lesdagEsc .".\r\n
					Ingangsdatum: " . $ingDatum ."\r\n",
					"Van: info@omnisport.com");
					
					$message="Uw registratie is met success ontvangen. U ontvangt binnenkort een e-mail met bevestiging.";
					
				}else{
					$registratiefout = mail($email, "Registratie-fout",
					"U ontvangt dit e-mail, omdat er iets mis is gegaan tijdens\r\n
					uw registratie op " . date("d-m-Y om H:i uur") . ".\r\n
					Probeer het later nog eens.",
					"Van: info@omnisport.com");
				}
			}else{
				$message = "Er is iets fout gegaan tijdens het registreren van gegevens in de ledentabel.";
			}
			
		}else{
			echo "Er is iets fout gegaan tijdens het registreren van gegevens in de ledentabel.";
			error_log(mysqli_error($connect),3,"error_log.txt");
		
		}
	}
}

if(isset($_POST['reset']) && $_POST['reset'] == "Reset"){
	reset($_POST);
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
	<div class="wrap">
		<span><?php echo $message;?></span>
		<span class=error><?php echo $errorMsg;?></span>
		<div class="imgtext">
		    <img alt="sport" src="images/sports.jpg">
		    <p>Welkom bij Omnisportvereniging! Wilt u mee doen
		    aan een van onze sporten? Registreer dan via onze registratie formulier 
		    hiernaast.</p>
		</div>
	    <div class="register-form">
	        <h2>Aanmeld formulier</h2>
	        <form method="POST" action="index.php">
	
	            <span class = error><?php echo $naamError;?></span>
	            <label for="form-voornaam">*Naam:</label>
	            <input type="text" id="form-voornaam" name="naam" value="<?php if($error){echo htmlentities($_POST['naam']);}else{ echo "";}?>">
	
	            <label for="form-tussenvoegsel">Tussenvoegsel:</label>
	            <input type="text" id="form-tussenvoegsel" name="tussenvoegsel" value="<?php if($error){echo htmlentities($_POST['tussenvoegsel']);}else{ echo "";}?>">
	
	            <span class = error><?php echo $anaamError;?></span>
	            <label for="form-achternaam">*Achternaam:</label>
	            <input type="text" id="form-achternaam" name="achternaam" value="<?php if($error){echo htmlentities($_POST['achternaam']);}else{ echo "";}?>">
				
				<span class = error><?php echo $gebdateError;?></span>
	            <label for="form-gebdatum">Geboortedatum</label>
	            <input type="text" id="form-gebdatum" name="gebdatum" value="<?php if($error){echo htmlentities($_POST['gebdatum']);}else{ echo "";}?>">
				
	            <div id = "straat-huisnr">
	            <span class = error><?php echo $straatError;?></span>
	            <span class = error><?php echo $huisnrError;?></span>
	                    <div class = "straat">
	                            <label for="form-straat">Straat:</label>
	                            <input type="text" id="form-straat" name="straat" value="<?php if($error){echo htmlentities($_POST['straat']);}else{ echo "";}?>">
	                    </div>
	                    <div class = "huisnr">
	                            <label for="form-huisnummer">nr.:</label>
	                            <input type="text" id="form-huisnummer" name="huisnummer" value="<?php if($error){echo htmlentities($_POST['huisnummer']);}else{ echo "";}?>">
	                    </div>
	            </div>
	            <div id="postcode">
	                    <span class = error><?php echo $postcodeError;?></span>
	                    <label for="form-postcode">Postcode:</label>
	                    <input type="text" id="form-postcode" name="postcode" value="<?php if($error){echo htmlentities($_POST['postcode']);}else{ echo "";}?>">
	            </div>
	
	            <span class = error><?php echo $woonpltsError;?></span>
	            <label for="form-woonplaats">Woonplaats:</label>
	            <input type="text" id="form-woonplaats" name="woonplaats" value="<?php if($error){echo htmlentities($_POST['woonplaats']);}else{ echo "";}?>">
	
	            <span class = error><?php echo $emailError;?></span>
	            <label for="form-email">*E-mail:</label>
	            <input type="text" id="form-email" name="email" value="<?php if($error){echo htmlentities($_POST['email']);}else{ echo "";}?>">
	
	            <span class = error><?php echo $ingdateError;?></span>
	            <label for="form-ingdatum">*Ingangsdatum</label>
	            <input type="text" id="form-ingdatum" name="ingdatum" value="<?php if($error){echo htmlentities($_POST['ingdatum']);}else{ echo "";}?>">
	
	            <div class="geslacht">
	                    <label id = "1">Geslacht</label><span class = error><?php echo $geslachtError;?></span>
	                    <input type="radio" id="form-man" name="geslacht" value="m"><label for="form-man">Man</label>
	                    <input type="radio" id="form-vrouw" name="geslacht" value="v"><label for="form-vrouw">Vrouw</label>
	            </div>
	
	            <label for="form-sport">*Sportonderdeel</label>
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
	
	            <p>(*)Verplichte velden.</p>
	
	            <div id="submit_reset">
	                    <input class="btn" type="submit" name="submit" value="Verstuur">
	                    <input class="btn" type="submit" name="reset" value="Reset">
	            </div>
	
	        </form>
	    </div>
    </div>
</div><!--end #container -->
</body>
</html>