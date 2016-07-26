<?php
	require_once('core/init.php');
	include_once('includes/head.php');
	include_once('includes/nav.php');
	if( $cart_id != '' ){
		$cartQ = query("SELECT * FROM cart WHERE id = :id",array('id'=>$cart_id),$conn);
		$cart = $cartQ->fetchAll(); 
		$item = $cart[0]['items'];
		$item = json_decode($item,true);
		$i = 1;
		$sub_total = 0;
		$item_count = 0;
	}
?>

<div class="container-fluid">
	<h2 class="text-center">My Shopping Cart</h2>
	<?php if( $cart_id == '' )  : ?>
		<div class="col-md-12">
			<p class="bg-danger text-center">Your Cart is Empty! </p>
		</div>
	<?php else : ?>
	<table class="table table-bordered table-striped">
		<thead>
			<th>#</th>
			<th>Item</th>
			<th>Price</th>
			<th>Quantity</th>
			<th>Size</th>
			<th>Sub Total</th>
		</thead>
		<tbody>
			<?php foreach($item as $item) : ?>
				<?php
					$productQ = query("SELECT * FROM products WHERE id = :id ",array('id'=>$item['id']),$conn);
					$product = $productQ->fetchAll();

				?>
				<?php foreach($product as $product) : ?>
					<?php
						$sizeString = explode(',', $product['sizes']) ;
						foreach($sizeString as $size){
							$size = explode(':', $size);
							if($size[0] == $item['size']){
								$available = $size[1];
							}
						}
					?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $product['title']; ?></td>
					<td><?php echo $product['price']; ?></td>
					<td>
					<button class="btn btn-xs btn-default" onclick="update_cart('removeone','<?= $product['id']; ?>','<?= $item['size']; ?>');">-</button>
					<?php echo $item['quantity']; ?>
					<?php if($item['quantity'] < $available) : ?>
					<button class="btn btn-xs btn-default" onclick="update_cart('addone','<?= $product['id']; ?>','<?= $item['size']; ?>');">+</button>
					<?php else : ?>
						<span class="text-danger">Max Reached</span>
					<?php endif; ?>
					</td>
					<td><?php echo $item['size']; ?></td>
					<td><?php echo money($product['price']*$item['quantity']); ?></td>
				</tr>
				<?php
					$i++;
				?>
				<?php endforeach; ?>
				<?php
					$item_count += $item['quantity'];
					$sub_total += $product['price']*$item['quantity'];
				?>
			<?php endforeach; ?>
			<?php
				$tax = $sub_total*TAXRATE;
				$tax = number_format($tax,2);
				$grand_total = $sub_total+$tax;
			 ?>				
		</tbody>
	</table>
	<h2>Total</h2>
	<table class="table table-bordered table-striped">
		<thead>
			<th>Total Items</th>
			<th>Sub Total</th>
			<th>Tax</th>
			<th>Grand Total</th>
		</thead>
		<tbody>
			<tr>
				<td><?= $item_count; ?></td>
				<td><?= money($sub_total); ?></td>
				<td><?= money($tax); ?></td>
				<td><?= money($grand_total); ?></td>
			</tr>
		</tbody>
	</table>
<!-- Check Out modal -->
<button type="button" class="btn btn-primary btn-lg pull-right" data-toggle="modal" data-target="#checkoutModal">
  <span class="glyphicon glyphicon-shopping-cart"></span> Checkout>>
</button>

<!-- Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Shipping Address : </h4>
      </div>
      <div class="modal-body">
      	<div id="step-1" >
	      	<form action="thank_you.php" method="POST">
	      		<span class="bg-danger" id="payment_error"></span>
	        	<div class="row">
	        		<div class="form-group col-md-6">
	        			<label for="full_name"> Full Name : </label>
	        			<input type="text" id="full_name" name="full_name" class="form-control">
	        		</div>
	        		<div class="form-group col-md-6">
	        			<label for="email"> Eamil : </label>
	        			<input type="email" id="email" name="email" class="form-control">
	        		</div>
	        		<div class="form-group col-md-6">
	        			<label for="street_address"> Street Address : </label>
	        			<input type="text" id="street_address" name="street_address" class="form-control">
	        		</div>
	        		<div class="form-group col-md-6">
	        			<label for="streee_address2"> Street Address 2 : </label>
	        			<input type="text" id="streee_address2" name="streee_address2" class="form-control">
	        		</div>
	        		<div class="form-group col-md-6">
	        			<label for="city"> City : </label>
	        			<input type="text" id="city" name="city" class="form-control">
	        		</div>
	        		<div class="form-group col-md-6">
	        			<label for="state"> State : </label>
	        			<input type="text" id="state" name="state" class="form-control">
	        		</div>
	        		<div class="form-group col-md-6">
	        			<label for="zip_code"> Zip Code : </label>
	        			<input type="text" id="zip_code" name="zip_code" class="form-control">
	        		</div>
	        		<div class="form-group col-md-6">
	        			<label for="country"> Country : </label>
	        			<input type="text" id="country" name="country" class="form-control">
	        		</div>
	        	</div>
	        
      	</div>
        <div id="step-2" style="display:none;">
        	<div class="form-group col-md-3">
    			<label for="Name_card"> Name On Card : </label>
    			<input type="text" id="Name_card" name="Name_card" class="form-control">
    		</div>
    		<div class="form-group col-md-3">
    			<label for="card_number"> Card Number : </label>
    			<input type="text" id="card_number" name="card_number" class="form-control">
    		</div>
    		<div class="form-group col-md-2">
    			<label for="cvc"> CVC : </label>
    			<input type="text" id="cvc" name="cvc" class="form-control">
    		</div>
    		<div class="form-group col-md-2">
    			<label for="expire_month"> Expire Month : </label>
    			<select name="expire_month" id="expire_month" class="form-control">
    				<option value=""></option>
    				<?php for($i=1;$i<13;$i++) : ?>
						<option value="<?= $i; ?>"><?= $i; ?></option>
    				<?php endfor; ?>
    			</select>
    		</div>
    		<div class="form-group col-md-2">
    			<label for="expire_year"> Expire Year : </label>
    			<select name="expire_year" id="expire_year" class="form-control">
    				<option value=""></option>
    					<?php for($i=1;$i<13;$i++) : ?>
						<option value="<?= date("Y")+$i; ?>"><?=  date("Y")+$i; ?></option>
    				<?php endfor; ?>
    			</select>
    		</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="button_next" onclick="check_address();">Next >></button>
       <button type="button" class="btn btn-primary" id="button_back" style="display:none" onclick="back_address();"> Back 
       </button>
        <button type="submit" class="btn btn-primary" style="display:none" id="button_checkout">Check Out >></button>
        </form>
      </div>
    </div>
  </div>
</div>
	<?php endif; ?>
</div>
<script>
	function back_address(){
			jQuery('#payment_error').html();
			jQuery('#step-1').css("display","block");
			jQuery('#step-2').css("display","none");
			jQuery('#button_next').css("display","inline-block");
			jQuery('#button_back').css("display","none");
			jQuery('#button_checkout').css("display","none");
			jQuery('#myModalLabel').html("Shipping Cart");
	}

	function check_address(){
		data = {
			'full_name' : jQuery('#full_name').val(),
			'email' : jQuery('#email').val(),
			'street_address' : jQuery('#street_address').val(),
			'streee_address2' : jQuery('#streee_address2').val(),
			'city' : jQuery('#city').val(),
			'state' : jQuery('#state').val(),
			'zip_code' : jQuery('#zip_code').val(),
			'country' : jQuery('#country').val(),
		}
		jQuery.ajax({
			url : '/ecommerce/admin/parser/check_address.php',
			method : 'post',
			data : data,
			success : function(data){
				if(data != 'passed'){
					jQuery('#payment_error').html(data);
				}
				if(data == 'passed'){
					jQuery('#payment_error').html();
					jQuery('#step-1').css("display","none");
					jQuery('#step-2').css("display","block");
					jQuery('#button_next').css("display","none");
					jQuery('#button_back').css("display","inline-block");
					jQuery('#button_checkout').css("display","inline-block");
					jQuery('#myModalLabel').html("Card Information");

				}
			},
			error : function(){alert("Something went wrong!");}
		});
	}
</script>
<?php
	include_once('includes/footer_top.php');
	include_once('includes/footer.php');
	
?>