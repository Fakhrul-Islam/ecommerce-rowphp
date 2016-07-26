<?php
	require_once("../core/init.php");
	require_once("../core/handelar.php");
	require_once("includes/head.php");
?>
<?php
	$error = array();
	if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
		$email = sanitize(trim($_POST['email']));
		$password = sanitize(trim($_POST['password']));
		$user_query = dbquery("SELECT * FROM users WHERE email = :email",array('email'=>$email),$conn);

		if( empty($email) || empty($password) ){
			$error[] = 'Your username or password is blank';
		}elseif( empty($user_query) ){
			$error[] = 'Sorry! Your email does\'nt Found in our database';
		}elseif( !empty($user_query) ) {
			$dbpassword = $user_query['password'];
			if( !password_verify($password,$dbpassword) ){
				$error[] = 'Sorry! Your password does\'nt match';
			}
		}

		
		if(!empty($error)){
			echo deisplyError($error);
		}else{
			$user_id = $user_query['id'];
			login($user_id);
		}
	}
?>

	<div class="container-fluid">
		<div class="login-form">
			<h2 class="text-center">Login</h2>
			<form action="" method="POST">
				<div class="form-group">
					<label for="email">Email :</label>
					<input type="email" name="email" class="form-control" id="email" value="<?= ( isset($_POST['email']) && !empty($_POST['email']) )? sanitize($_POST['email']):'';  ?>">
				</div>
				<div class="form-group">
					<label for="password">Password :</label>
					<input type="password" name="password" class="form-control" id="password" value="<?= ( isset($_POST['password']) && !empty($_POST['password']) )? sanitize($_POST['password']):''; ?>" min="6">
				</div>
				<div class="form-group">
					<input type="submit" value="Login" class="text-center btn btn-success">	
					<a href="http://localhost/ecommerce" class="text-success" style="float:right">Visit Our Site</a>	
				</div>
			</form>
		</div>
	</div>

<?php 
	require_once('includes/footer.php');
?>