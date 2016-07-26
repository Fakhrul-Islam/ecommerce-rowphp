<?php
	require_once($_SERVER['DOCUMENT_ROOT']."/ecommerce/core/init.php");
	$parentID = (int)$_POST['parentID'];
	$selected = (int)$_POST['selected'];
	$child_query = query("SELECT * FROM categories WHERE parent = :p",array('p'=>$parentID),$conn);
	if($child_query->rowCount()<1){
		$child_query = query("SELECT * FROM categories WHERE id = :id",array('id'=>$parentID),$conn);
	}
	$child_query = $child_query->fetchAll();
	ob_start();
?>
<option value=""></option>
<?php foreach ($child_query as $child) : ?>
	<option value="<?php echo $child['id']; ?>"<?=($selected==$child['id'])?'selected':''; ?>><?php echo $child['category']; ?></option>
<?php endforeach; ?>


<?php echo ob_get_clean(); ?>