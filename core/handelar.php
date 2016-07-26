<?php
	
	//html entities
	function sanitize($dirty){
		return htmlentities($dirty, ENT_QUOTES, 'UTF-8');
	}
	//display error
	function deisplyError($error){
		$e = '<ul class="bg-danger">';
		foreach ($error as $error){
			$e .='<li class="text-danger">'.$error.'</li>';
		}
		$e .= '</ul>';
		return $e;
	}
	function money($price){
		return '$'.number_format($price,2);
	}

	function login($user_id){
		$_SESSION['user_id'] = $user_id;
		global $conn;
		query("UPDATE users SET last_login = now() WHERE id = id",array('id'=>$user_id),$conn);
		$_SESSION['success_flash'] = 'You are now logged in';
		header('Location:index.php');
	}
	//Is user login
	function isLogedIn(){
		if(isset($_SESSION['user_id']) && $_SESSION['user_id']>0){
			return true;
		}else{
			return false;
		}
	}

	//Redirect if usere not loged in
	function loged_error_redirect($url='login.php'){
		$_SESSION['error_flash'] = 'You must be loged in to access this page';
		header('Location:'.$url);
	}
	//If user has no permission
	function permission_error_redirect($url){
		$_SESSION['error_flash'] = 'You don\'t have permission to access this page';
		header('Location:'.$url);
	}
	//User Role Or User has Permission
	function has_permission($permission = 'admin'){
		global $user_data;
		$permissions = explode(',', $user_data['permission']);
		if(in_array($permission, $permissions)){
			return true;
		}else{
			return false;
		}
	}

	function time_ready($date){
		return date("M,d Y h:i A",strtotime($date));
	}

	function get_category($child_id){
		global $conn;
		$id = sanitize($child_id);
		$cat = query("SELECT p.id AS 'pid', p.category AS 'parent', c.id AS 'cid',c.category AS 'child' FROM categories c INNER JOIN categories p ON c.parent = p.id WHERE c.id = :id", array('id'=>$id),$conn);
		$cat = $cat->fetchAll();
		return $cat;
	}

?>