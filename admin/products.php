<?php
	require_once("../core/init.php");
	require_once("../core/handelar.php");
	require_once("includes/head.php");
	require_once('includes/nav.php');
	if( !isLogedIn() ){
		loged_error_redirect();
	}
	$product = query("SELECT * FROM products WHERE deleted = :d",array('d'=>0),$conn);
	$product = $product->fetchAll();
	//Delete Product
	if (isset($_GET['del']) && $_GET['del'] != '' ){
		$delId = $_GET['del'];
		$del_query = query("UPDATE products SET deleted = :d WHERE id =:id",array('id'=>$delId,'d'=>1),$conn);
		if($del_query){
			header('Location:products.php');
		}
	}
	//Featured Product
	if(isset($_GET['featured'])){
		$id = (int)$_GET['id'];
		$featured = (int)$_GET['featured'];
		$featured_query = query("UPDATE products SET featured=:f WHERE id=:id",array('f'=>$featured,'id'=>$id),$conn);
		if($featured_query){
			header('Location:products.php');
		}
	}
	$brnad_query = query("SELECT * FROM brands ORDER BY brand",array(),$conn);
	$brnad_query = $brnad_query->fetchAll();
	$parent_query = query("SELECT * FROM categories WHERE parent = 0 ORDER BY category",array(),$conn);
	$parent_query = $parent_query->fetchAll();
	//SizeArray
	$sizeString = array();
	$title = (isset($_POST['title']) && !empty($_POST['title']))?sanitize($_POST['title']):'';	
	$brand = (isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):'';
	$parent = (isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']):'';
	$category = (isset($_POST['child']) && !empty($_POST['child']))?sanitize($_POST['child']):'';
	$price =  (isset($_POST['price']) && !empty($_POST['price']))?sanitize($_POST['price']):'';
	$list_price =  (isset($_POST['list_price']) && !empty($_POST['list_price']))?sanitize($_POST['list_price']):'';
	$sizes = (isset($_POST['sizes']) && !empty($_POST['sizes']))?sanitize($_POST['sizes']):'';
	$sizes = rtrim($sizes,',');
	$description = (isset($_POST['description']) && !empty($_POST['description']))?sanitize($_POST['description']):'';
	$saved_img = '';

	//Edit
	if(isset($_GET['edit'])){
		$editId = $_GET['edit'];
		$editQuery = dbQuery("SELECT * FROM products WHERE id = :id",array('id'=>$editId),$conn);
		$editId = (int)$_GET['edit'];
		if( isset($_GET['image_del'])  && isset($_GET['edit']) ){
			$imageURL = $_SERVER['DOCUMENT_ROOT'].$editQuery['image'];
			unset($imageURL);
			$imgD = query("UPDATE products SET image = :im WHERE id =:id",array('im'=>'','id'=>$editId),$conn);
			if($imgD){
				header('Location:products.php?edit='.$editId);
			}
		}
		$title = (isset($_POST['title']) && !empty($_POST['title']))?sanitize($_POST['title']):$editQuery['title'];
		$brand = (isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):$editQuery['brand'];
		$category = (isset($_POST['brand']) && !empty($_POST['child']))?sanitize($_POST['child']):$editQuery['category'];
		$parentQ = dbQuery("SELECT * FROM categories WHERE id = :id",array('id'=>$editQuery['category']),$conn);
		$parent = (isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']):$parentQ['parent'];
		$price = (isset($_POST['price']) && !empty($_POST['price']))?sanitize($_POST['price']):$editQuery['price'];
		$list_price = (isset($_POST['list_price']) && !empty($_POST['list_price']))?sanitize($_POST['list_price']):$editQuery['list_price'];
		$sizes = (isset($_POST['sizes']) && !empty($_POST['sizes']))?sanitize($_POST['sizes']):$editQuery['sizes'];
		$sizes = rtrim($sizes,',');
		$description = (isset($_POST['description']) && !empty($_POST['description']))?sanitize($_POST['description']):$editQuery['description'];
		$saved_img = $editQuery['image'];
	
	
	}
	if( isset($sizes) && !empty($sizes) ){
			$sizeString = $sizes;
			$sizeString = rtrim($sizeString,',');
			$sizeArray = explode(',', $sizeString);
			$sArray = array();
			$qArray = array();
			foreach($sizeArray as $sss){
				$sizeQ = explode(':', $sss);
				$sArray[] = $sizeQ[0];
				$qArray[] = $sizeQ[1];
			}
		}else{$sizeArray = array();}

	if($_POST){
		
			$error = array();

			if( empty($_POST['title']) || empty($_POST['brand']) || empty($_POST['parent']) || empty($_POST['price']) || empty($_POST['sizes']) || empty($_POST['description']) ){
				$error[] = 'All fieled must be fieled!';
			}
			if( !empty($_FILES) ){
				$photo = $_FILES['photo'];
				$nameArray = $photo['name'];
				$nameArray = explode('.',$nameArray);
				$name = $nameArray[0];
				$nameExt = $nameArray[1];
				$mimeArray = explode('/',$photo['type']);
				$mimeType = $mimeArray[0];
				$mimeExt = $mimeArray[1];
				$fileLoc = $photo['tmp_name'];
				$fileSize = $photo['size'];
				$allowed = array('jpg','jpeg','png','gif');
				$uploadName = md5(microtime()).'.'.$nameExt;
				$uploadLoc = $_SERVER['DOCUMENT_ROOT'].'/ecommerce/images/products/'.$uploadName;
				
				$dbPath = '/ecommerce/images/products/'.$uploadName;
				if( $mimeType != 'image' ){
					$error[] = 'Your upload file must be an image';
				}else if( !in_array($nameExt, $allowed) ){
					$error[] = 'File Extension must be a jpg,jpeg,png,gif';
				}else if( $fileSize>10000000){
					$error[] = 'File must be under 10MB ';
				}else if( $nameExt != $mimeExt && ($mimeExt == 'jpeg' && $nameExt !='jpg') ){
					$error[] = 'File Extension does not match ';
				}
			}
			if(!empty($error)){
				echo deisplyError($error);
			}else{
				//Insert Data
				if( !empty($_FILES) ){
					$upload = move_uploaded_file($fileLoc, $uploadLoc);
				}
				
				if( isset($_GET['add']) ){	
				$insert_product = query("INSERT INTO products(title,price,list_price,brand,category,image,description,sizes) VALUES(:t,:p,:l,:b,:c,:i,:d,:s)",array(
						't' => $title,
						'p' => $price,
						'l' => $list_price,
						'b' => $brand,
						'c' => $category,
						'i' => $dbPath,
						'd' => $description,
						's' => $sizes,
					),$conn);
				}

				if( isset($_GET['edit']) ){
					if( $dbPath == '' ){
						$dbPath = $saved_img;
					}
					$update_product = query("UPDATE products SET title = :t, price = :p,list_price = :list_price,brand =:brand,category=:category,description =:description,sizes =:sizes, image = :image WHERE id = :id",array('t'=>$title,'p'=>$price,'id'=>$editId,'list_price'=>$list_price,'brand'=>$brand,'category'=>$category,'description'=>$description,'sizes'=>$sizes,'image'=>$dbPath),$conn);
				}
				

				if($insert_product || $update_product){
					header('Location:products.php');
				}
			}
	}
?>
<div class="container-fluid">
	<?php if( isset($_GET['add']) && $_GET['add'] == 1 || isset($_GET['edit']) ) :	?>
	<h2 class="text-center"><?=(isset($_GET['edit']))?'Edit':'Add';?> Product</h2>
	<div class="row">
		<form action="products.php?<?=(isset($_GET['edit']))?'edit='.$editId:'add=1'?>" method="POST" enctype="multipart/form-data">
			<div class="form-group col-md-3">
				<label for="title">Title*</label>
				<input type="text" id="title" name="title"class="form-control" value="<?=$title;?>">
			</div>
			<div class="form-group col-md-3">
				<label for="brand">Brand*</label>
				<select name="brand" class="form-control" id="brand">
					<option value=""<?= ($brand=='')?' selected':'' ?>></option>
					<?php foreach($brnad_query as $b) : ?>
					<option value="<?php echo $b['id']; ?>"<?= ($brand == $b['id'] )?'selected':'' ?>><?php echo $b['brand']; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="form-group col-md-3">
				<label for="parent">Parent*</label>
				<select name="parent" id="parent" class="form-control">
					<option value=""<?= ($parent == '' )?' selected':'' ?>></option>
					<?php foreach($parent_query as $p) : ?>
					<option value="<?=$p['id'];?>"<?= ($parent==$p['id'])?' selected':''; ?>><?php echo $p['category']; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="form-group col-md-3">
				<label for="child">Child* :</label>
				<select name="child" id="child" class="form-control"></select>
			</div>
			<div class="form-group col-md-3">
				<label for="price">Price* :</label>
				<input type="text" id="price" name="price" class="form-control" value="<?= $price; ?>">
			</div>
			<div class="form-group col-md-3">
				<label for="list_price">List Price* :</label>
				<input type="text" id="list_price" name="list_price" class="form-control" value="<?= $list_price;?>">
			</div>
			<div class="form-group col-md-3">
				<label>Quantity & Sizes* :</label>
				<button class="btn btn-default form-control" onclick="jQuery('#sizeModal').modal('toggle');return false">Quantity & Sizes :</button>
			</div>
			<div class="form-group col-md-3">
				<label for="sizes">Sizes & Quantity Preview</label>
				<input type="text" class="form-control" name="sizes" id="sizes" value="<?= $sizes; ?>" readonly>
			</div>
			<div class="form-group col-md-6">
				<?php if($saved_img != '') : ?>
					<div class="saved_image">
						<img src="<?= $saved_img; ?>" alt="">
						<a href="products.php?image_del=1&edit=<?=$editId?>" class="text-danger">Delete Image</a>
					</div>
				<?php else: ?>
				<label for="photo">Product Photo</label>
				<input type="file" name="photo" class="form-control" id="photo">
				<?php endif; ?>
			</div>
			<div class="form-group col-md-6">
				<label for="description">Description* :</label>
				<textarea name="description" id="description" class="form-control" row="6"><?=$description; ?></textarea>
			</div>
			<div class="form-group col-md-3 pull-right">
				<a href="products.php" class="btn btn-default">Cancel</a>
				<input type="submit" value="<?=(isset($_GET['edit']))?'Edit':'Add'; ?> Product" name="add_product" class="btn btn-success">
			</div>
		</form>
		<!-- Modal -->
<div class="modal fade" id="sizeModal" tabindex="-1" role="dialog" aria-labelledby="sizeModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Quantity & Sizes</h4>
      </div>
      <div class="modal-body">
      <div class="container-fluid">
       <?php for($i=1; $i<=12; $i++) : ?>
			<div class="form-group col-md-4">
				<label for="size<?=$i;?>">Size :</label>
				<input type="text" name="size<?=$i;?>" id="size<?=$i;?>" class="form-control" value="<?=(!empty($sArray[$i-1]))?$sArray[$i-1]:'';?>">
			</div>
			<div class="form-group col-md-2">
				<label for="qty<?=$i;?>">Quantity :</label>
				<input type="number" name="qty<?=$i;?>" id="qty<?=$i;?>" min="0" class="form-control" value="<?=(!empty($qArray[$i-1]))?$qArray[$i-1]:'';?>">
			</div>
       <?php endfor; ?>
       </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="updateSize();jQuery('#sizeModal').modal('toggle');return false">Save changes</button>
      </div>
    </div>
  </div>
</div>
	</div>

	<?php else: ?>
	<h2 class="text-center">Products</h2>
	<a href="products.php?add=1" class="btn btn-success pull-right" id="add-product-button">Add Product</a>
	<table class="table table-bordered table-condensed table-striped">
		<thead>
			<th class="bg-primary"></th>
			<th class="bg-primary">Products</th>
			<th class="bg-primary">Price</th>
			<th class="bg-primary">Category</th>
			<th class="bg-primary">Featured</th>
			<th class="bg-primary">Sold</th>
		</thead>
		<tbody>
			<?php foreach($product as $product) : 
				$child_id = $product['category'];
				$child_query = query("SELECT * FROM categories WHERE id=:id",array('id'=>$child_id),$conn);
				$child_query = $child_query->fetchAll();
				foreach ($child_query as $child_query) {
					$child = $child_query['category'];
					$parent_id = $child_query['parent'];
				}
				$p_query = $child_query = query("SELECT * FROM categories WHERE id=:id",array('id'=>$parent_id),$conn);
				$p_query = $p_query->fetchAll();
				foreach ($p_query as $p_query) {
					$parent = $p_query['category'];
				}
				$category = $parent.'->'.$child;

			?>
				<tr>
					<td>
						<a href="products.php?edit=<?php echo $product['id']; ?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-pencil"></span></a>
						<a href="products.php?del=<?php echo $product['id']; ?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-remove"></span></a>
					</td>
					<td><?php echo $product['title']; ?></td>
					<td><?php echo money($product['price']); ?></td>
					<td><?php echo $category; ?></td>
					<td><a href="products.php?id=<?php echo $product['id']; ?>&featured=<?php if($product['featured']==0 ){echo 1; }else{echo 0; } ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-<?php if($product['featured']==0){echo 'plus';}else{echo 'minus';}?>"></span>&nbsp; <?php if($product['featured']==1){echo 'Featured Poroduct'; }?></a></td>
					<td>0</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
<?php endif; ?>
</div>

<?php 
	require_once('includes/footer.php');
?>
<script>
	jQuery(document).ready(function(){
		get_child_options('<?php echo $category?>');
	});
</script>