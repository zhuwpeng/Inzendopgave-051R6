<?php
include "inc/db_connect.php";
include "inc/functions.php";
//set_error_handler("error_msg");

$naamError = "";
$anaamError = "";
$straatError = "";
$postcodeError = "";
$huisnrError = "";
$toevgingError = "";
$woonpltsError = "";
$emailError = "";
$ingdateError = "";
$gebdateError = "";
$geslachtError = "";

$message = "";
$errorMsg = "";
$errors = array('geen'=>array('naam'=>"U heeft geen naam ingevuld",
							'achternaam'=>"U heeft geen achternaam ingevuld",
							'email'=>"U heeft geen e-mail ingevuld",
							'datum'=>"U heeft geen datum opgegeven",
							'geslacht'=>"Kies een geslacht"),
			'ongeldig'=>array('straat'=>"Straatnaam mag alleen bestaan uit letters",
							'email'=>"E-mail moet 2 op de volgende manier '2 letters'@'2 letters' en eindigen op '.nl'(vb. ab@cd.nl)",
							'postcode'=>"Postcode moet bestaan uit 4 cijfers en 2 letters (vb. 1234ab)",
							'huisnummer'=>"Huisnummer moet bestaan uit alleen nummers",
							'toevoeging'=>"Toevoeging mag alleen bestaan uit letters",
							'woonplaats'=>"Woonplaats mag alleen bestaan uit letters",
							'datum'=>"Datum format moet dd-mm-yyyy zijn (vb. 23-01-2000)",
							'gebdatum'=>"Datum ligt in de toekomst")
				);

$error = false;

//check of submit isset
if(isset($_POST['submit']) && $_POST['submit'] == 'Verstuur'){
	
	foreach ($_POST as $key => $input) {
		if ($key != "submit") {
			$stripTrim[$key]= stripslashes(trim($input));
			
			if (empty($input)) {
				if ($key == "tussenvoegsel" || 
					$key == "gebdatum" || 
					$key == "straat" ||
					$key == "postcode" ||
					$key == "huisnummer" ||
					$key == "toevoeging" ||
					$key == "woonplaats" ||
					$key == "ingdatum") {
					$stripTrim[$key] = NULL;
				} else {
					$errorMsges[$key] = $errors['geen'][$key];
				}
			} else {
				$errorMsges[$key] = "";
			}
		}
	}
	
	$naam = $stripTrim['naam'];
	$tussenvoegsel = $stripTrim['tussenvoegsel'];
	$achternaam = $stripTrim['achternaam'];
	$gebDatum = $stripTrim['gebdatum'];
	$straat = $stripTrim['straat'];
	$postcode = $stripTrim['postcode'];
	$huisnummer = $stripTrim['huisnummer'];
	$toevoeging = $stripTrim['toevoeging'];
	$woonplaats = $stripTrim['woonplaats'];
	$email = $stripTrim['email'];
	$ingDatum = $stripTrim['ingdatum'];
	$geslacht = $stripTrim['geslacht'];
	
// 	$naam = stripslashes(trim($_POST['naam']));
// 	$tussenvoegsel = stripslashes(trim(isset($_POST['tussenvoegsel'])?$_POST['tussenvoegsel']:NULL));
// 	$achternaam = stripslashes(trim($_POST['achternaam']));
// 	$gebDatum = stripslashes(trim(isset($_POST['gebdatum'])?$_POST['gebdatum']:NULL));
// 	$straat = stripslashes(trim(isset($_POST['straat'])?$_POST['straat']:NULL));
// 	$postcode = stripslashes(trim(isset($_POST['postcode'])?$_POST['postcode']:NULL));
// 	$huisnummer = stripslashes(trim(isset($_POST['huisnummer'])?$_POST['huisnummer']:NULL));
// 	$toevoeging = stripslashes(trim(isset($_POST['toevoeging'])?$_POST['toevoeging']:NULL));
// 	$woonplaats = stripslashes(trim(isset($_POST['woonplaats'])?$_POST['woonplaats']:NULL));
// 	$email = stripslashes(trim(isset($_POST['email'])?$_POST['email']:NULL));
// 	$ingDatum = stripslashes(trim($_POST['ingdatum']));
// 	$geslacht = stripslashes(trim((isset($_POST['geslacht'])?$_POST['geslacht']:NULL)));
	
	//Valideer naam
// 	if(empty($naam)){
// 		$naamError = $errors['geen']['naam'];
// 		$error = true;
// 	}
	
	//Valideer achternaam
// 	if(empty($achternaam)){
// 		$anaamError = $errors['geen']['achternaam'];
// 		$error = true;
// 	}
	
	//Valideer geboorte datum
	if(!empty($gebDatum)){
		$valgebDatum = date_validation($gebDatum);
	
		if($valgebDatum == false){
			$errorMsges = $errors['ongeldig']['datum'];
		}
	
		if(strtotime($valgebDatum)>strtotime(date("Y-m-d"))){
			$errorMsges = $errors['ongeldig']['gebdatum'];
			$error = true;
		}
	}else{
		$valgebDatum = NULL;
	}
	
	//Valideer straat
	if(!empty($straat)){
		if(!preg_match('/^[a-zA-Z]*$/', $straat)){
			$errorMsges = $errors['ongeldig']['straat'];
			$error = true;
		}
	}

	//Valideer huisnummer
	if(!empty($huisnummer)){
		if(!preg_match('/^[0-9]*$/', $huisnummer)){
			$errorMsges = $errors['ongeldig']['huisnummer'];
			$error = true;
		}
	}
	
	//Valideer toevoeging
	if(!empty($toevoeging)) {
		if(!preg_match('/^[a-zA-Z]*$/', $toevoeging)) {
			$errorMsges = $errors['ongeldig']['toevoeging'];
			$error = true;
		}
	}
	
	//Valideer postcode
	if(!empty($postcode)){
		if(!preg_match('/^(\d\d\d\d)[a-zA-Z][a-zA-Z]$/', $postcode)){
			$errorMsges = $errors['ongeldig']['postcode'];
			$error = true;
		}
	}
	
	//Valideer woonplaats
	if(!empty($woonplaats)){
		if(!preg_match('/^[a-zA-Z]+$/', $woonplaats)){
		$errorMsges = $errors['ongeldig']['woonplaats'];
		$error = true;
		}
	}
	
	//Valideer email
	if(empty($email)){
		$errorMsges = $errors['geen']['email'];
		$error = true;
	}else{
		$checkEmail = test_email($email);
		if($checkEmail=="ongeldig"){
			$errorMsges = $errors['ongeldig']['email'];
			$error = true;
		}
	}
	
	//Valideer ingangsdatum
	if(empty($ingDatum)){
		$errorMsges = $errors['geen']['datum'];
		$error = true;
	}else{
		$valIngDatum = date_validation($ingDatum);
		
		if($valIngDatum == false){
			$errorMsges = $errors['ongeldig']['datum'];
			$error = true;
		}
	}
	
	//Valideer geslacht
	if(empty($geslacht)){
		$errorMsges = $errors['geen']['geslacht'];
		$error = true;
	}
	
	//Insert data in database
	if($error == false){
		$naamEsc = mysqli_real_escape_string($connect, ucfirst(strtolower($naam)));
		$tussenvoegsel = mysqli_real_escape_string($connect, $tussenvoegsel);
		$achternaamEsc = mysqli_real_escape_string($connect, ucfirst(strtolower($achternaam)));
		$straatEsc = mysqli_real_escape_string($connect, ucfirst(strtolower($straat)));
		$postcodeEsc = mysqli_real_escape_string($connect, $postcode);
		$huisnummerEsc = mysqli_real_escape_string($connect, $huisnummer);
		$toevoegingEsc = mysqli_real_escape_string($connect, strtoupper($toevoeging));
		$woonplaatsEsc = mysqli_real_escape_string($connect, ucfirst(strtolower($woonplaats)));
		$geslachtEsc = mysqli_real_escape_string($connect, $geslacht);
		$gebdatEsc = mysqli_real_escape_string($connect, $valgebDatum);
		$emailEsc = mysqli_real_escape_string($connect, strtolower($email));
		$sportonderdeelEsc = mysqli_real_escape_string($connect, ucfirst($_POST['sportonderdeel'])); 
		$lesdagEsc = mysqli_real_escape_string($connect, ucfirst($_POST['lesdag']));
		$ingdatEsc = mysqli_real_escape_string($connect, $valIngDatum);
		
		$huisToev = $huisnummerEsc.$toevoegingEsc;
		
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
											'$naamEsc',"
											. ($tussenvoegsel==NULL ? "NULL" : "'$tussen'") .",
											'$achternaamEsc',"
											. ($straatEsc==NULL ? "NULL" : "'$straatEsc'") .","
											. ($huisnummerEsc==NULL ? "NULL" : "'$huisnummerEsc'") .","
											. ($postcodeEsc==NULL ? "NULL" : "'$postcodeEsc'") .","
											. ($woonplaatsEsc==NULL ? "NULL" : "'$woonplaatsEsc'").",
											'$emailEsc',"
											. ($gebdatEsc==NULL ? "NULL" : "'$gebdatEsc'") . ",
											'$geslachtEsc');";
		
		//Retrieve latest person id
		$ledenResult = mysqli_query($connect, $ledenQuery) or die( "Er is iets mis gegeaan tijdens het invoeren van gegevens." . " " . mysqli_error($connect).".");
		$ledenId = mysqli_insert_id($connect);
			
			if($ledenId != NULL || $ledenId > 0){
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
						$volNaam = $naam . " " . stripslashes($tussenvoegsel) . " " . $achternaam;
					}
					
					
						$bevestiging = mail($email, "Registratie",
								"Welkom bij Omnisport vereniging!\r\n
								Dit is een bevestigings e-mail van uw registratie.\r\n
								Hieronder nog de registratie data:\r\n
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
							
					//voeg controle toe of mail verstuurd is
					if(!$bevestiging || !$administratie) {
						$message="Er is iets fout gegaan tijdens uw registratie. Een bericht is naar uw e-mail verstuurd.";
						$lastError = error_get_last();
						error_log($lastError['message'],3, "error_log.txt");
					} else {

						$message="Uw registratie is met success ontvangen. U ontvangt binnenkort een e-mail met bevestiging.";
					}
					
				}else{
					$registratiefout = mail($email, "Registratie-fout",
					"U ontvangt dit e-mail, omdat er iets mis is gegaan tijdens\r\n
					uw registratie op " . date("d-m-Y \o\m H:i") . " uur.\r\n
					Probeer het later nog eens.",
					"Van: info@omnisport.com");
					
					$message="Er is iets fout gegaan tijdens uw registratie. Een bericht is naar uw e-mail verstuurd.";
				}
			}else{
				$message = "Er is iets fout gegaan tijdens het registreren van gegevens in de ledentabel.";
			}
	}
}

if(isset($_POST['reset']) && $_POST['reset'] == "Reset"){
	unset($_POST);
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
		<h2 class="message"><?php echo $message;?></h2>
		<span class=error><?php echo $errorMsg;?></span>
		<div class="imgtext">
		    <img alt="sport" src="img/sports.jpg">
		    <p>Welkom bij Omnisportvereniging! Wilt u mee doen
		    aan een van onze sporten? Registreer dan via onze registratie formulier 
		    hieronder.</p>
		</div>
	    <div class="register-form">
	        <h2>Aanmeld formulier</h2>
	        <form method="POST" action="index.php">
				<div class="left-side">
		            <span class = error><?php if(isset($errorMsges['naam'])){echo $errorMsges['naam'];}?></span>
		            <label for="form-voornaam">*Naam:</label>
		            <input type="text" id="form-voornaam" name="naam" value="<?php if(isset($_POST['naam'])){ echo htmlentities($_POST['naam']);}else{ echo "";}?>">
		
		            <label for="form-tussenvoegsel">Tussenvoegsel:</label>
		            <input type="text" id="form-tussenvoegsel" name="tussenvoegsel" value="<?php if(isset($_POST['tussenvoegsel'])){ echo htmlentities($_POST['tussenvoegsel']);}else{ echo "";}?>">
		
		            <span class = error><?php if(isset($errorMsges['achternaam'])){echo $errorMsges['achternaam'];}?></span>
		            <label for="form-achternaam">*Achternaam:</label>
		            <input type="text" id="form-achternaam" name="achternaam" value="<?php if(isset($_POST['achternaam'])){ echo htmlentities($_POST['achternaam']);}else{ echo "";}?>">
					
					<span class = error><?php if(isset($errorMsges['gebdatum'])){echo $errorMsges['gebdatum'];}?></span>
		            <label for="form-gebdatum">Geboortedatum</label>
		            <input type="text" id="form-gebdatum" name="gebdatum" value="<?php if(isset($_POST['gebdatum'])){ echo htmlentities($_POST['gebdatum']);}else{ echo "";}?>">
					
		            <div id = "straat-huisnr">
				            <span class = error><?php if(isset($errorMsges['straat'])){echo $errorMsges['straat'];}?></span>
				            <span class = error><?php if(isset($errorMsges['huisnummer'])){echo $errorMsges['huisnummer'];}?></span>
				            <span class = error><?php if(isset($errorMsges['toevoeging'])){echo $errorMsges['toevoeging'];}?></span>
		                    <div class = "straat">
	                            <label for="form-straat">Straat:</label>
	                            <input type="text" id="form-straat" name="straat" value="<?php if(isset($_POST['straat'])){ echo htmlentities($_POST['straat']);}else{ echo "";}?>">
		                    </div>
		                    <div class = "huisnr">
	                            <label for="form-huisnummer">nr.:</label>
	                            <input type="number" max="9999" id="form-huisnummer" name="huisnummer" value="<?php if(isset($_POST['huisnummer'])){ echo htmlentities($_POST['huisnummer']);}else{ echo "";}?>">
		                    </div>
		                    <div class = "toevoeging">
		                    	<label for="form-toevoeging">Toev.</label>
		                    	<input type="text" id="form-toevoeging" maxlength="3" name="toevoeging" value="<?php if(isset($_POST['toevoeging'])){ echo htmlentities($_POST['toevoeging']);}else{ echo "";}?>">
		                    </div>
		            </div>
		            
                    <span class = error><?php if(isset($errorMsges['postcode'])){echo $errorMsges['postcode'];}?></span>
                    <label for="form-postcode">Postcode:</label>
                    <input type="text" id="form-postcode" name="postcode" value="<?php if(isset($_POST['postcode'])){ echo htmlentities($_POST['postcode']);}else{ echo "";}?>">
				</div>
				
				<div class="right-side">
		            <span class = error><?php if(isset($errorMsges['woonplaats'])){echo $errorMsges['woonplaats'];}?></span>
		            <label for="form-woonplaats">Woonplaats:</label>
		            <input type="text" id="form-woonplaats" name="woonplaats" value="<?php if(isset($_POST['woonplaats'])){ echo htmlentities($_POST['woonplaats']);}else{ echo "";}?>">
		
		            <span class = error><?php if(isset($errorMsges['email'])){echo $errorMsges['email'];}?></span>
		            <label for="form-email">*E-mail:</label>
		            <input type="email" id="form-email" name="email" value="<?php if(isset($_POST['email'])){ echo htmlentities($_POST['email']);}else{ echo "";}?>">
		
		            <span class = error><?php if(isset($errorMsges['ingdatum'])){echo $errorMsges['ingdatum'];}?></span>
		            <label for="form-ingdatum">*Ingangsdatum</label>
		            <input type="text" id="form-ingdatum" name="ingdatum" value="<?php if(isset($_POST['ingdatum'])){ echo htmlentities($_POST['ingdatum']);}else{ echo "";}?>">
		
		            <div class="geslacht">
		                    <label class="geslacht-title">Geslacht</label><span class = error><?php echo $geslachtError;?></span>
		                    <input type="radio" id="form-man" name="geslacht" checked="checked" <?php if(isset($_POST['geslacht']) && $_POST['geslacht']=='m'){ echo ' checked="checked" ';}?> value="m"><label for="form-man">Man</label>
		                    <input type="radio" id="form-vrouw" name="geslacht" <?php if(isset($_POST['geslacht']) && $_POST['geslacht']=='v'){ echo ' checked="checked" ';}?> value="v"><label for="form-vrouw">Vrouw</label>
		            </div>
		
		            <label for="form-sport">*Sportonderdeel</label>
						<?php 
						$sports = array('tennis', 'voetbal', 'tafeltennis', 'biljart');
						$sportSelectName = 'sportonderdeel';
						$sportSelectId = 'form-sport';
						
						create_select_option($sports, $sportSelectName, $sportSelectId);
						?>
		
		            <label for="form-dag">Lesdag</label>
						<?php 
						$dagen = array('maandag', 'dinsdag', 'woensdag', 'donderdag', 'vrijdag');
						$dagenSelectName = 'lesdag';
						$dagenSelectId = 'form-dag';
						
						create_select_option($dagen, $dagenSelectName, $dagenSelectId);
						?>
						
				</div>
				
	            <p>(*)Verplichte velden.</p>
				
	            <div class="submit_reset">
	                    <input class="btn" type="submit" name="submit" value="Verstuur">
	                    <input class="btn" type="submit" name="reset" value="Reset">
	            </div>
	        </form>
    </div>
</div><!--end #container -->
</body>
</html>