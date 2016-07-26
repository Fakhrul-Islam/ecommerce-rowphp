<?php
	require_once("../core/init.php");
	require_once("includes/head.php");
	require_once('includes/nav.php');
	
	if( !isLogedIn() ){
		header('Location:login.php');
	}


	
?>


Adminstrator



<?php 
	require_once('includes/footer.php');
	
?>