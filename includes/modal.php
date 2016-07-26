<?php
	if( isset($_POST['id']) ){
		include_once('../core/init.php');
		$id = (int)$_POST['id'];
		$query = query("SELECT * FROM products WHERE id=:id LIMIT 1",array('id'=>$id),$conn);
		$query = $query->fetchAll();
		foreach( $query as $product ){
			$id = $product['id'];
			$title = $product['title'];
			$price = $product['price'];
			$list_price = $product['list_price'];
			$brand = $product['brand'];
			$image = $product['image'];
			$sizes = $product['sizes'];
			$description = $product['description'];
		}
		$brandquery = query("SELECT * FROM brands WHERE id=:id LIMIT 1",array('id'=>$brand),$conn);
		$brandquery = $brandquery->fetchAll();
		foreach ($brandquery as $brandquery) {
			$brand = $brandquery['brand'];
		}
		
		$size_array = explode(',',$sizes);
	}
	
?>
<?php ob_start(); ?>
<!--- Modal Details -->
<div class="modal fade details-1" id="details-modal" tabindex="-1" role="dialog" aria-labelledby="details-1" aria-hidden="true" >
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" type="button" onclick="modalClose()" aria-label="close">
					<span aria-hidden="true"> &times;</span>
				</button>
				<h4 class="modal-title text-center"><?php echo $title ; ?></h4>
			</div>
			<div class="modal-body">
				<div class="container-fluid">
					<div class="row">
						<span id="modal_error" class="bg-danger"></span>
						<div class="col-sm-6">
							<div class="center-block">
								<img src="<?php echo $image?>" alt="Levis Jeans" class="details img-responsive">
							</div>
						</div>
						<div class="col-sm-6">
							<h4>Details</h4>
							<p><?php echo $description ; ?></p>
							<hr>
							<p>price : $<?php echo $price ; ?></p>
							<p>Brand: <?php echo $brand ; ?></p>
							<form action="add_cart.php" method="post" id="add_product_form">
								<input type="hidden" name="product_id" id="product_id" value="<?=$id; ?>">
								<input type="hidden" name="available" id="available" value="">
								<div class=" form-group">
									<div class="col-xs-3">
										<label for="quantity">Quantity : </label>
										<input type="number" min="0" class="form-control" id="quantity" name="quantity">
									</div>
								</div>
								<br>
								<br>
								<br>
								<div class="form-group">
									<label for="size">Size: </label>
									<select name="size" id="size" class="form-control">
										<option value=""></option>
<?php
	foreach($size_array as $size_array){
		$size_array = explode(':', $size_array);
		$size = $size_array[0];
		$available = $size_array[1];
		echo "<option value=\"$size\" data-available = \"$available\">$size (Stock : $available)</option>";
	}
?>
									</select>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-default" onclick="modalClose()">close</button>
				<button class="btn btn-warning" onclick="add_to_cart();return false;"><span class="glyphicon glyphicon-shopping-cart"></span>Add to cart</button>
			</div>
		</div>
	</div>
</div>
<script>
	jQuery('#size').change(function(){
		var available = jQuery('#size option:selected').data('available');
		jQuery('#available').val(available);
	});

	function modalClose(){
		jQuery('#details-modal').modal('hide');
		setTimeout(function(){
			jQuery('#details-modal').remove();
		},500);
	}
</script>
<?php echo ob_get_clean(); ?>