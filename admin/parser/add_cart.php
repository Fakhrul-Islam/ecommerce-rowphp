<?php
	require_once($_SERVER['DOCUMENT_ROOT'].'/ecommerce/core/init.php');
	$product_id = sanitize($_POST['product_id']);
	$quantity = sanitize($_POST['quantity']);
	$available = sanitize($_POST['available']);
	$size = sanitize($_POST['size']);
	$item = array();
	$item[] = array(
		'id'		=> $product_id,
		'quantity' 	=> $quantity,
		'size' 		=> $size
	);
	$domain = ( $_SERVER['HTTP_HOST'] != 'localhost' )?'.'.$_SERVER['HTTP_HOST']:false;
	$query = query('SELECT * FROM products WHERE id = :id',array('id'=>$product_id),$conn);
	$product = $query->fetchAll();
	
	$_SESSION['success_flash'] = $product[0]['title'].' is added to cart';
	if($cart_id != ''){
		$cartQ = query("SELECT * FROM cart WHERE id = :id",array('id'=>$cart_id),$conn);
		$cart = $cartQ->fetchAll();
		$previous_item = json_decode($cart[0]['items'],true);
		$item_match = 0;
		$new_items = array();
		foreach($previous_item as $pitem){
			if( $item[0]['id'] == $pitem['id'] && $item[0]['size'] == $pitem['size'] ){
				$pitem['quantity'] = ($pitem['quantity'] + $item[0]['quantity']);
				if($pitem['quantity'] > $available){
					$pitem['quantity'] = $available;
				}
				$item_match = 1;
			}
			$new_items = $pitem;
		}
		if($item_match != 1){
			$new_items= array_merge($previous_item,$item);
		}
		$item_json = json_encode($new_items);
		$cart_expire = date("Y-m-d H:i:s",strtotime("+30 days"));
		$insert_cart = query("UPDATE cart SET items = :items,expire_date=:expire WHERE id=:id",array('items'=>$item_json ,'expire'=>$cart_expire,'id'=>$cart_id ),$conn);
		setcookie(CART_COOKIE,'',1,'/',$domain,false);
		setcookie(CART_COOKIE,$cart_id,CART_COPKIE_EXPIRE,'/',$domain,false);
	}else{
		//add cart to database and set cookie
		$item_json = json_encode($item);
		$cart_expire = date("Y-m-d H:i:s",strtotime("+30 days"));
		$insert_cart = query("INSERT INTO cart(items,expire_date) VALUES(:item,:expire)",array('item'=>$item_json,'expire'=>$cart_expire),$conn);
		$cart_id = $conn->lastInsertId();
		setcookie(CART_COOKIE,$cart_id,CART_COPKIE_EXPIRE,'/',$domain,false);
	}

?>