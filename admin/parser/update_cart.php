<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/ecommerce/core/init.php';
$mode = $_POST['mode'];
$edit_id = $_POST['edit_id'];
$edit_size = $_POST['edit_size'];
$cartQ = query("SELECT * FROM cart WHERE id = :id",array('id'=>$cart_id),$conn);
$cart = $cartQ->fetchAll();
$items = $cart[0]['items'];
$items = json_decode($items,true);
if($mode == 'removeone'){
	foreach($items as $items){
		if( $items['id'] == $edit_id ){
			$items['quantity'] = $items['quantity'] - 1;
			if($items['quantity']<=0){
				continue;
			}
		}
		$uadate_items[] = $items;
	}
}
if($mode == 'addone'){
	foreach($items as $items){
		if( $items['id'] == $edit_id ){
			$items['quantity'] = $items['quantity'] + 1;
		}
		$uadate_items[] = $items;
	}
}

if( !empty($uadate_items) ){
	$item_json = json_encode($uadate_items,true);
	$update = query("UPDATE cart SET items = :items WHERE id = :id",array('id'=>$cart_id,'items'=>$item_json),$conn);
}

$domain = ( $_SERVER['HTTP_HOST'] != 'localhost' )?'.'.$_SERVER['HTTP_HOST']:false;

if(empty($uadate_items)){
	$del = query("DELETE FROM cart WHERE id = :id",array('id'=>$cart_id),$conn);
	setcookie(CART_COOKIE,$cart_id,'1','/',$domain,false);
}





?>