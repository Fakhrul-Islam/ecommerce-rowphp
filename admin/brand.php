<?php
	require_once("../core/init.php");
	require_once("../core/handelar.php");
	require_once("includes/head.php");
	require_once('includes/nav.php');
	if( !isLogedIn() ){
		loged_error_redirect();
	}
?>
<?php
	$brand = query("SELECT * FROM brands",array(),$conn);
	$brand = $brand->fetchAll();
	//Delete a Brand
	if( isset($_GET['delete']) && !empty($_GET['delete']) ){
		$del_id = $_GET['delete'];
		$del_brand = query("DELETE FROM brands WHERE id= :id",array('id'=>$del_id),$conn);
		if( $del_brand ){
			header('Location:brand.php');
		}
	}
	//Edit Brand
	if( isset($_GET['edit']) && !empty($_GET['edit']) ){
		$edit_id = (int)$_GET['edit'];
		$edit_brand = query("SELECT brand FROM brands WHERE id=:id LIMIT 1",array('id'=>$edit_id),$conn);
		$edit_brand = $edit_brand->fetchAll();
		foreach($edit_brand as $edit_brand){ $edit_brand = $edit_brand['brand'];}
	}
	//Add Brand
	if( isset($_POST['brand-submit']) ){
		$error = array();
		$brand_add = (string)sanitize($_POST['brand']);
		$brand_check = query("SELECT * FROM brands WHERE brand = :brand",array('brand'=>$brand_add),$conn);
		if ( $brand_add == "" ){
			$error[] .= 'You Must Enter Brand Name';
		}else if( $brand_check->rowCount()>0 ){
			$error[] .= 'This brand is already added';
		}
		//Edit Brand
		if( isset($_GET['edit']) && !empty($_GET['edit']) ){
			$edit_brand = (string)sanitize($_POST['brand']);
			$edit_query = query("UPDATE brands set brand = :brand WHERE id=:id",array('brand'=>$edit_brand,'id'=>$_GET['edit']),$conn);
			if( $edit_query ){
				header('Location:brand.php');
				exit();
			}else{
				$error[] .= 'Edit Problem !';
				exit();
			}
		}

		if( !empty($error) ){
			echo deisplyError($error);
		}else{
			//Added Brand to database
			query("INSERT INTO brands(brand) VALUES(:b)",array('b'=>$brand_add),$conn);
		}
	}
?>
<div class="container-fluid">
	<h1 class="text-center">Brand</h1>
	<form action="brand.php<?php if( isset($edit_id) ){echo '?edit='.$edit_id;} ?>" method="POST" class="form-inline text-center">
		<div class="form-group">
			<label for="brand"><?php if( isset($edit_id) ){echo 'Edit';}else{echo 'Add a';} ?> Brand :</label>
			<input type="text" name="brand" id="brand" class="form-control" placeholder="<?php if( isset($edit_id) ){echo $edit_brand;}else{echo 'Enter Brand';} ?>">
		</div>
		<div class="form-group">
		<?php if( isset($edit_id) ) :?>
			<a href="brand.php" class="btn btn-default">Cancel</a>
		<?php endif; ?>
			<input type="submit" name="brand-submit" value="<?php if( isset($edit_id) ){echo 'Edit';}else{echo 'Add Brand';} ?>" class="btn btn-primary">
		</div>
	</form>
	<table class="table table-bordered table-striped table-auto">
		<thead>
			<th>Edit</th><th>Brand</th><th>Delete</th>
		</thead>
		<tbody>
			<?php foreach($brand as $brand) : ?>
				<tr>
					<td><a class="btn btn-xs btn-default" href="brand.php?edit=<?php echo $brand['id']; ?>"><span class="glyphicon glyphicon-pencil"></span></a></td>
					<td><?php echo $brand['brand']; ?></td>
					<td><a class="btn btn-xs btn-default" href="brand.php?delete=<?php echo $brand['id']; ?>"><span class="glyphicon glyphicon-remove-sign"></span></a></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<?php 
	require_once('includes/footer.php');
?>