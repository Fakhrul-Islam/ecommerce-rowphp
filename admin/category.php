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
	$category = query("SELECT * FROM categories WHERE parent = 0",array(),$conn);
	$category = $category->fetchAll();
	//Edit Category
	if(isset($_GET['edit']) && !empty($_GET['edit'])){
		$edit_id = (int)$_GET['edit'];
		$edit_cat = query("SELECT * FROM categories WHERE id = :id", array('id'=>$edit_id ),$conn);
		$edit_cat = $edit_cat->fetchAll();
		foreach ($edit_cat as $edit_cat) {
			$edit_cat = $edit_cat['category'];						
		}

		if( isset($_POST['add_category'])){
			$edit_cat = sanitize($_POST['category']);
			$edit_parent = $_POST['parent'];
			$error = array();
			if( !isset($edit_cat) || !isset($edit_parent ) ){
				$error[] .= 'one or more field is blank';
			}
			if(!empty($error)){
				echo displayError($error);
			}else{
				$edit_query = query("UPDATE categories SET category=:c,parent =:p WHERE id = :id",array('c'=>$edit_cat,'p'=>$edit_parent,'id'=>$edit_id),$conn);
				if($edit_query){
					header('Location:category.php');
				}
			}
		}


	}
	//Delete Category
	if(isset($_GET['del']) && !empty($_GET['del'])){
		$del_cat = (int)$_GET['del'];
		$del_query = query("DELETE FROM categories WHERE id=:del",array('del'=>$del_cat),$conn);
		if($del_query){
			header('Location:category.php');
		}

	}
	//ADD CATEGORY	
	if( isset($_POST['add_category']) ){
		$error = array();
		$add_category = sanitize($_POST['category']);
		$add_parent = (int)$_POST['parent'];
		$category_check =  query("SELECT * FROM categories WHERE category = :cat AND parent=:p",array('cat'=>$add_category,'p'=>$add_parent),$conn);
		if( empty($add_category) || !isset($add_parent) ){
			$error[] .= 'One or More Field is empty';  
		}elseif( $category_check->rowCount()>0 ){
			$error[] .= 'This category is already added!';  
		}
		if( isset($error) && !empty($error) ){
			echo deisplyError($error);
		}else{

			$add= query("INSERT INTO categories(category,parent) VALUES(:cat,:p)",array('cat'=>$add_category,'p'=>$add_parent),$conn);
			
		}

	}

?>
<div class="container-fluid">
	<h1 class="text-center">Categories</h1>
	<div class="row">
		<div class="col-md-3"></div>
		<div class="col-md-3">
			<form action="category.php<?php if(isset($edit_id)){echo '?edit='.$edit_id;} ?>" method="POST" clsas="form-inline text-center">
				<div class="form-group">
					<label for="category"><?php if(isset($edit_id)){echo 'Edit Category';}else{echo 'Add Category';}; ?></label>
<input type="text" name="category" placeholder="<?php if(isset($edit_id)){echo $edit_cat;}else{echo 'Add Category';}; ?>" class="form-control">
				</div>
				<div class="form-group">
					<label for="parent">Select Parent</label>
					<select name="parent" id="parent" class="form-control">
						<option value="">Select a Parent</option>
						<option value="0">Parent</option>
						<?php foreach($category as $categories) : ?>
							<option value="<?php echo $categories['id']; ?>"><?php echo $categories['category']; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="form-group">
					<?php if(isset($edit_id)) : ?>
						<a href="category.php" class="btn btn-default">Cancel</a>
					<?php endif;	?>
					<input type="submit" class="form-control btn btn-primary" value="<?php if(isset($edit_id)){echo 'Edit Category';}else{echo 'Add Category';}; ?>" name="add_category">
				</div>
			</form>
		</div>
		<div class="col-md-6">
			<table class="table table-bordered table-striped">
				<thead>
					<th>Category</th>
					<th>Parent</th>
					<th>Option</th>
				</thead>
				<tbody>
					<?php foreach($category as $category) : ?>
						<?php
							$parent_id = (int)$category['id'];
							$child = query("SELECT * FROM categories WHERE parent = :parent",array('parent'=>$parent_id),$conn);
							$child = $child->fetchAll();
						?>
					<tr>
						<td class="bg-primary"><?php echo $category['category']; ?></td>
						<td class="bg-primary">Parent</td>
						<td class="bg-primary">
						<a href="category.php?edit=<?php echo $category['id']?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-pencil "></a></span>
						<a href="category.php?del=<?php echo $category['id']?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-remove-sign "></a></span>
						</td>
					</tr>
						<?php foreach($child as $child) : ?>
							<tr class="bg-default">
								<td><?php echo $child['category']; ?></td>
								<td><?php echo $category['category']; ?></td>
								<td>
								<a href="category.php?edit=<?php echo $child['id']?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-pencil "></a></span>
								<a href="category.php?del=<?php echo $child['id']?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-remove-sign "></a></span>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php 
	require_once('includes/footer.php');
?>