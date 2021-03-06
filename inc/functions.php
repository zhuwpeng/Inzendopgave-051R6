<?php
//Check of email format klopt
function test_email($email)
{
	$match_pattern = '/^[a-zA-Z]*[a-zA-Z]*@[a-zA-z]*[a-zA-Z]*.nl$/';
	$match = preg_match($match_pattern, $email);

	if($match){
		$explode_result = explode("@", $email);
		$explode_result_domain = explode(".", $explode_result[1]);
		$name = $explode_result[0];
		$domain =$explode_result_domain[0];
		
		if(strlen($name) < 2 || strlen($domain) < 2){
			return "ongeldig";
		}
	}else{
		return "ongeldig";
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
	echo "<div class = 'errorMsg'>";
	echo "<p><b>Error:</b> Oeps er is iets fout gegaan op deze pagina! Probeer later nog eens aan te melden.";
	echo "Error type: $err_type: $err_msg in $err_file at line $err_line";
	echo "</div>";
	
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
			echo '<option value="' . strtolower($option) . '" ' . (isset($_POST[$selectName])?$_POST[$selectName] == strtolower($option) ?' selected = "selected"':'':'') . '>' . ucfirst(strtolower($option)) . '</option>';
		}
	} else {
		echo '<select name="'. $selectName . '" id="' . $selectId . '">';
			echo '<option value="' . strtolower($option) . '" ' . (isset($_POST[$selectName])?$_POST[$selectName] == strtolower($option) ?' selected = "selected"':'':'') . '>' . ucfirst(strtolower($option)) . '</option>';
	}
	echo '<select>';

}