
<!-- Header -->
	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container">
			<a href="/ecommerce/index.php" class="navbar-brand">Silver Butique's Admin</a>
			<ul class="nav navbar-nav">
				<li><a href="brand.php">Brands</a></li>
				<li><a href="category.php">Categories</a></li>
				<li><a href="products.php">Products</a></li>
				<li><a href="archive.php">Archive</a></li>
				<?php if(has_permission('admin')) : ?>
				<li><a href="users.php">Users</a></li>
				<?php endif; ?>
				<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Hello <?php echo $user_data['full_name']; ?> <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="password_change.php">Password Change</a></li>
						<li><a href="logout.php">Log Out</a></li>
					</ul>
				</li>
				<!-- <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"  href=""><?php echo $pquery['category']; ?><span class="caret"></span></a>
					<ul class="dropdown-menu">
					
						<li><a href=""><?php echo $childquery['category']; ?></a></li>
					
					</ul>
				</li> -->
				
			</ul>
		</div>
	</nav>