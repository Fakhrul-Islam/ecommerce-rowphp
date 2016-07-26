<?php
	require_once('core/init.php');
	include_once('includes/head.php');
	include_once('includes/nav.php');
	include_once('includes/header_top.php');
	include_once('includes/left_sidebar.php');
	$product = query("SELECT * FROM products WHERE featured = :f ",array('f'=>1),$conn);
	$product = $product->fetchAll(); 

?>
<div class="col-md-8">
<!--- Main Content -->
	<div class="row">
		<h2 class="text-center">Feature Products</h2>
		<?php foreach($product as $product) : ?>
		<div class="col-md-3">
			<h4><?php echo $product['title']; ?></h4>
			<img src="<?php echo $product['image']; ?>" alt="Levis Jeans"  class="product-thumb">
			<p class="list-price text-danger">List Price : <s>$<?php echo $product['list_price']; ?></s></p>
			<p class="price">Our Price : $<?php echo $product['price']; ?></p>
			<button type="button" class="btn btn-sm btn-success" onclick="modalDetails(<?php echo $product['id']; ?>)">Details</button>
		</div>
		<?php endforeach; ?>
	</div>
</div>
<?php
	include_once('includes/right_sidebar.php');
	include_once('includes/footer_top.php');
	include_once('includes/footer.php');
?>



	
