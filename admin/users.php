<?php
	require_once("../core/init.php");
	require_once("includes/head.php");
	require_once('includes/nav.php');
	
	if( !isLogedIn() ){
		loged_error_redirect();
	}
	if( !has_permission('admin') ){
		permission_error_redirect('brand.php');
	}
	$users_query = query("SELECT * FROM users ORDER BY full_name",array(),$conn);
	$users_query = $users_query->fetchAll();

	//Delete Users
	if( isset($_GET['del']) && !empty($_GET['del']) ){
		$del_id = $_GET['del'];
		$del_query = query("DELETE FROM users WHERE id = :id",array('id'=>$del_id),$conn);
		if( $del_query ){
			$_SESSION['success_flash'] = 'User has been deleted';
			header('Location:users.php');
		}
	}
	//ADD USER
	if( isset($_GET['add']) ){
		$error = array();
		 $full_name = ( isset($_POST['full_name']) && !empty($_POST['full_name']) )? sanitize($_POST['full_name']):'';
		 $full_name = trim($full_name);
		 $email = ( isset($_POST['email']) && !empty($_POST['email']) )? sanitize($_POST['email']):'';
		 $email = trim($email);
	   	 $password = ( isset($_POST['password']) && !empty($_POST['password']) )? sanitize($_POST['password']):'';
	   	 $password = trim($password);
	     $permission = ( isset($_POST['permission']) && !empty($_POST['permission']) )? sanitize($_POST['permission']):'';
	     $permission = trim($permission);
	     $hash_pass = password_hash($password,PASSWORD_DEFAULT);
	     $email_query = query("SELECT * FROM users WHERE email = :email",array('email'=>$email),$conn);
	     if( isset($_POST) ){
	     	 if( empty($full_name) || empty($email) || empty($password) || empty($permission) ){
	     	 	$error[] = "Please Fill All Field";
	     	 }elseif( !filter_var($email,FILTER_VALIDATE_EMAIL) ){
	     	 	$error[] = "Email is not valid";
	     	 }elseif( $email_query->rowCount()>0 ){
	     	 	$error[] = "Email Already Exists";
	     	 }


	     if( !empty($error) ){
	     	echo deisplyError($error);
	     }else{
	     	//add user to database
	     	$user_insert = query("INSERT INTO users(full_name,email,password,join_date,permission) VALUES(:full_name,:email,:password,now(),:permission)",array('full_name'=>$full_name,'email'=>$email,'password'=>$hash_pass,'permission'=>$permission),$conn);
	     	if( $user_insert ){
	     		$_SESSION['success_flash'] = 'User Added';
	     		header('Location:users.php');
	     	}
	     }
	   }
    }

?>

<div class="container-fluid">
	<?php if( isset($_GET['add']) && $_GET['add'] == 1 ) : ?>
		<h2 class="text-center">Add User</h2>
		<div class="row">
			<form action="users.php?add=1" method="POST">
				<div class="col-md-6 form-group">
					<label for="full_name">Full Name : </label>
					<input type="text" name="full_name" id="full_name" class="form-control" value="<?= $full_name; ?>">
				</div>
				<div class="col-md-6 form-group">
					<label for="email">Email : </label>
					<input type="email" name="email" id="email" class="form-control" value="<?= $email; ?>">
				</div>
				<div class="col-md-6 form-group">
					<label for="full_name">Password : </label>
					<input type="password" name="password" id="password" class="form-control" value="<?= $password; ?>">
				</div>
				<div class="col-md-6 form-group">
					<label for="permission">Permission(seperate with comma) : </label>
					<input type="text" name="permission" id="permission" class="form-control"  value="<?= $permission; ?>">
				</div>
				<div class="col-md-12 form-group text-center">
					<a href="users.php"><span class="btn btn-default">Cancel</span></a>
					<input type="submit" class="btn btn-success" value="Add User" name="add_user">
				</div>
			</form>
		</div>
	<?php else: ?>
	<h2 class="text-center">Users</h2>
	<a href="users.php?add=1" id="add-product-button" class="pull-right"><span class="btn btn-success">Add User</span></a>
	<div class="users">
		<table class="table table-bordered table-striped">
			<thead>
				<th></th>
				<th>Name</th>
				<th>Email</th>
				<th>Join Date</th>
				<th>Last Login</th>
				<th>Permission</th>
			</thead>
			<tbody>
			<?php foreach($users_query as $users ) : ?>
				<tr>
					<td>
						<?php if( $users['id'] != $user_data['id'] ) : ?>
						<a class="btn btn-default btn-sm" href="users.php?del=<?=$users['id']; ?>"><span class="glyphicon glyphicon-remove"></span></a>	
						<?php endif; ?>					
					</td>
					<td><?=$users['full_name']; ?></td>
					<td><?=$users['email']; ?></td>
					<td><?=time_ready($users['join_date']); ?></td>
					<td><?=time_ready($users['last_login']); ?></td>
					<td><?=$users['permission']; ?></td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
<?php endif; ?>
</div>


<?php 
	require_once('includes/footer.php');
?>