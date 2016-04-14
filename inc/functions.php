<?php
//Check of email format klopt
function test_email($email)
{
	$match_pattern = '/^[a-z]*[A-Z]*@[a-z]*[A-Z]*.nl$/';
	$match = preg_match($match_pattern, $email);

	if($match){
		$explode_result = explode("@", $email);
		$explode_result_domain = explode(".", $explode_result[1]);
		$name = $explode_result[0];
		$domain =$explode_result_domain[0];
		
		if(strlen($name) < 2){
			return "naam";
		}elseif( strlen($domain) < 2){
			return "domein";
		}else{
			return "goed";
		}
	}else{
		return "ongeldig";
	}
}

//Check of postcode klopt
function test_postcode($postcode)
{
	$match_pattern = '/^(\d\d\d\d)[a-zA-Z][a-zA-Z]$/';
	$match = preg_match($match_pattern, $postcode);

	if($match){
		return true;
	}
}

//Check of huisnummer klopt
function test_huisnr($huisnr)
{
	$match_pattern = '/^[1-9][0-9]+[a-z]*$/';
	$match = preg_match($match_pattern, $huisnr);

	if($match){
		return true;
	}
}

//Check of woonplaats alleen letter bevat
function test_woonplaats($woonplaats)
{
	$match_pattern = '/^[a-zA-Z]+$/';
	$match = preg_match($match_pattern, $woonplaats);

	if($match){
		return true;
	}
}

//Check of datum klopt
function date_validation($inputdate){
	$matches="";

	$pattern = '/(\d{2})\-(\d{2})\-(\d{4})/';

	if(preg_match($pattern, $inputdate)){
		$inputdate = explode("-", $inputdate);
	}else{
		return false;
	}

	if(checkdate($inputdate[1],$inputdate[0], $inputdate[2])){
		$reorder = array($inputdate[2],$inputdate[1],$inputdate[0]);

		$combine = implode('-', $reorder);

		return $combine;
	}else{
		return false;
	}
}

//Eigen error handler
function error_msg($err_type, $err_msg, $err_file, $err_line){
	echo"<div class = 'errorMsg'>
			<b>Error:</b>
			<p>Oeps er is iets fout gegaan op deze pagina!</p>
			</div>";
}

/**
 * @desc: Maakt een select optiebox
 * @param: array['waardes'], naam, id
 * @returns: html optie veld
 */
function create_select_option($options, $selectName, $selectId) {
	if(is_array($options)) {
		echo '<select name="'. $selectName . '" id="' . $selectId . '">';
		foreach($options as $option) {
			echo '<option value="' . strtolower($option) . '" ' . ($_POST[$selectName] == strtolower($option) ?' selected = "selected"':'' ) . '>' . ucfirst(strtolower($option)) . '</option>';
		}
		echo '<select>';
	}
}