<?php
require_once("../core/init.php");
session_destroy();
$_SESSION = array();
if( !isset($_SESSION['user_id']) ){
	header('Location:login.php');
}