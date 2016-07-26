<?php
	require_once("../core/init.php");
	require_once("../core/handelar.php");
	require_once("includes/head.php");
	require_once('includes/nav.php');
	if( !isLogedIn() ){
		loged_error_redirect();
	}
	$product = query("SELECT * FROM products WHERE deleted = :d",array('d'=>1),$conn);
	$product = $product->fetchAll();
	
	//Refresh Product
	if (isset($_GET['ref']) && $_GET['ref'] != '' ){
		$refId = $_GET['ref'];
		$ref_query = query("UPDATE products SET deleted = :d WHERE id =:id",array('id'=>$refId,'d'=>0),$conn);
		if($ref_query){
			header('Location:archive.php');
		}
	}

?>
<div class="container-fluid">
	<h2 class="text-center">Archive Products</h2>
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
						<a href="archive.php?ref=<?php echo $product['id']; ?>" class="btn btn-default btn-xs"><span class="glyphicon glyphicon-refresh"></span></a>
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
</div>

<?php 
	require_once('includes/footer.php');
?>
<script>
	jQuery(document).ready(function(){
		get_child_options('<?php echo $category?>');
	});
</script>