<?php
session_start();
require_once('config.php');
$config = array(
		'USERNAME'	=>	'root',
		'PASSWORD'	=>	'',
		'DBNAME'	=>	'ecommerce'
	);
define('BASEURL', '/ecommerce/');
//FUNCTION FOR DATABASE CONNECTION
$conn = connect($config);
function connect($config){
	try{
		$conn = new PDO("mysql:host=localhost;dbname=".$config['DBNAME'], 
						$config['USERNAME'], $config['PASSWORD']);
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $conn ;
	}catch(PDOException $e){
		return false;
	}
}

//Query
function query($query,$bindings,$conn){
	$result = $conn->prepare($query);
	$result->execute($bindings);
	return $result;
}
function dbQuery($query,$bindings,$conn){
	$result = $conn->prepare($query);
	$result->execute($bindings);
	$result = $result->fetchAll();
	foreach ($result as $result) {
		return $result;
	}
}
include_once('handelar.php');

$cart_id = '';
if( isset($_COOKIE[CART_COOKIE]) ){
	$cart_id = sanitize($_COOKIE[CART_COOKIE]);
}

if(isset($_SESSION['user_id'])){
	$user_id = $_SESSION['user_id'];
	 $user_data = dbquery("SELECT * FROM users WHERE id = :id",array('id'=>$user_id),$conn);
}
if(isset($_SESSION['success_flash'])){
		echo '<div class="success-flash"><p class="text-center bg-success">'.$_SESSION['success_flash'].'</p></div>';
		unset($_SESSION['success_flash']);
	}

if(isset($_SESSION['error_flash'])){
	echo '<div class="success-flash"><p class="text-center bg-danger">'.$_SESSION['error_flash'].'</p></div>';
	unset($_SESSION['error_flash']);
}





?>