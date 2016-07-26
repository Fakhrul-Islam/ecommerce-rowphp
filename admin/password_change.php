<?php
	require_once("../core/init.php");
	require_once("../core/handelar.php");
	require_once("includes/head.php");
	if( !isLogedIn() ){
		loged_error_redirect();
	}
?>
<?php
	$error = array();
	if( $_SERVER['REQUEST_METHOD'] == 'POST' ){
		$old_password = sanitize(trim($_POST['old_password']));
		$new_password = sanitize(trim($_POST['new_password']));
		$con_password = sanitize(trim($_POST['con_password']));
		$hased = $user_data['password'];
		$new_hased = password_hash($new_password,PASSWORD_DEFAULT);
		

		if( empty($old_password) || empty($new_password) || empty($con_password) ){
			$error[] = 'All Filed Must Be Fill.';
		}elseif( !password_verify($old_password,$hased) ){
			$error[] = 'Your Password does\'nt match';
		}elseif( $new_password != $con_password ) {
			$error[] = 'Confirm Password not match';
		}

		
		if(!empty($error)){
			echo deisplyError($error);
		}else{
			query("UPDATE users SET password = :pass WHERE id = :id",array('id'=>$user_id,'pass'=>$new_hased),$conn);
			$_SESSION['success_flash'] = 'Your password has been changed';
			header('Location:index.php');
		}
	}
?>

	<div class="container-fluid">
		<div class="login-form">
			<h2 class="text-center">Change Password</h2>
			<form action="" method="POST">
				<div class="form-group">
					<label for="old_password">Old Password :</label>
					<input type="password" name="old_password" class="form-control" id="old_password" value="<?= ( isset($_POST['old_password']) && !empty($_POST['old_password']) )? sanitize($_POST['old_password']):''; ?>" min="6">
				</div>
				<div class="form-group">
					<label for="new_password">New Password :</label>
					<input type="password" name="new_password" class="form-control" id="new_password" value="<?= ( isset($_POST['new_password']) && !empty($_POST['new_password']) )? sanitize($_POST['new_password']):''; ?>" min="6">
				</div>
				<div class="form-group">
					<label for="con_password">Confirm Password :</label>
					<input type="password" name="con_password" class="form-control" id="con_password" value="<?= ( isset($_POST['con_password']) && !empty($_POST['con_password']) )? sanitize($_POST['con_password']):''; ?>" min="6">
				</div>
				<div class="form-group">
					<a href="index.php" class="btn btn-default">Cancel</a>
					<input type="submit" value="Change Password" class="text-center btn btn-success">	
					
				</div>
			</form>
		</div>
	</div>

<?php 
	require_once('includes/footer.php');
?>