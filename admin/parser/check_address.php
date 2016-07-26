<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce/core/init.php';
	$full_name = sanitize($_POST['full_name']);
	$email = sanitize($_POST['email']);
	$street_address = sanitize($_POST['street_address']);
	$street_address2 = sanitize($_POST['streee_address2']);
	$city = sanitize($_POST['city']);
	$state = sanitize($_POST['state']);
	$zip_code = sanitize($_POST['zip_code']);
	$country = sanitize($_POST['full_name']);
	$require = array(
			'full_name' 		=> $full_name,
			'email' 			=> $email,
			'street_address' 	=> $street_address,
			'street_address2' 	=> $street_address2,
			'city' 				=> $city,
			'state' 			=> $state,
			'zip_code' 			=> $zip_code,
			'country' 			=> $country

		);
	$error = array();

	foreach($require as $k=>$v){
		if(empty($v)||$v==''){
			$error[] = $k . ' is empty';
		}
	}
	if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
		$error[] = 'Email is not validate';
	}

if(!empty($error)){
	echo deisplyError($error);
}else{
	echo 'passed';
}












?>