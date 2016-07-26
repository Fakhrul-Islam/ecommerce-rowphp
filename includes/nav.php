<?php
	$pquery = query("SELECT * FROM categories WHERE parent = :p",array('p'=>0),$conn);
	$pquery = $pquery->fetchAll();
?>
<!-- Header -->
	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container">
			<a href="index.php" class="navbar-brand">Silver Butique's</a>
			<ul class="nav navbar-nav">
				<?php foreach($pquery as $pquery): ?>
					<?php 
						$pid = $pquery['id'];
						$childquery = query("SELECT * FROM categories WHERE parent = :p",array('p'=>$pid),$conn);
						$childquery = $childquery->fetchAll();
					?>
				<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"  href=""><?php echo $pquery['category']; ?><span class="caret"></span></a>
					<ul class="dropdown-menu">
						<?php foreach($childquery as $childquery) : ?>
						<li><a href="category.php?cat=<?= $childquery['id'];?>"><?php echo $childquery['category']; ?></a></li>
						<?php endforeach; ?>
					</ul>
				</li>
				<?php endforeach; ?>
				<li><a href="cart.php"><span class="glyphicon glyphicon-shopping-cart"></span> Cart</a></li>
			</ul>
		</div>
	</nav>