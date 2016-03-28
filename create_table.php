<?php
$host = "localhost";
$username = "user";
$password = "password";


$connect = mysqli_connect($host, $username, $password)
or die ("Could not connect to database.");

function make_table($connect, $tabelkolommen, $tabelname){
	$testQuery = "SELECT 1 FROM $tabelname";
	//$testResult = mysqli_query($connect, $testQuery);

	if(!mysqli_query($connect, $testQuery))
	{
		$query = "CREATE TABLE $tabelname ($tabelkolommen);";
		$result = mysqli_query($connect, $query);

		if(mysqli_query($connect, "SELECT 1 FROM $tabelname"))
		{
			return "Table '$tabelname' has successfully been created in database 'dbloi'.<br>";
		}else{
			return "Please check you query for any mistakes.<br>";
		}
	}
	else{
		return "The table $tabelname already exists. <br>";
	}
}


$ledenTabel = "leden";
$ledenQuery = "ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
				Voornaam CHAR(20) NOT NULL,
				Tussenvoegsels CHAR(15),
				Achternaam CHAR (30) NOT NULL,
				Straat CHAR (50),
				Huisnummer CHAR  (10),
				Postcode CHAR  (6),
				Woonplaats CHAR (30),
				Email CHAR (50),
				Geboortedatum DATE,
				Geslacht CHAR (1)";

$lidmaatschapTabel = "lidmaatschap";
$lidmaatschapQuery = "ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
					LedenID INT NOT NULL,
					Datumingang DATE NOT NULL,
					Datumeinde DATE,
					Sportonderdeel CHAR (30) NOT NULL,
					Lesdag CHAR (15)";

if(!mysqli_select_db($connect, 'dbloi'))
{
	mysqli_query($connect, "CREATE DATABASE dbloi;");
	
	echo "Database dbloi has been created. <br>";
	
	$create_ledentabel = make_table($connect, $ledenQuery, $ledenTabel);
	echo $create_ledentabel;
	
	$create_lidmaatschaptabel = make_table($connect, $lidmaatschapQuery, $lidmaatschapTabel);
	echo $create_lidmaatschaptabel;
		
}else{
	echo "The database dbloi already exists. <br>";
	
	$create_ledentabel = make_table($connect, $ledenQuery, $ledenTabel);
	echo $create_ledentabel;
	
	$create_lidmaatschaptabel = make_table($connect, $lidmaatschapQuery, $lidmaatschapTabel);
	echo $create_lidmaatschaptabel;

}
