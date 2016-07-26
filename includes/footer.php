<script>
	jQuery(window).scroll(function(){
		var vscroll = jQuery(this).scrollTop();
		jQuery('#logoText').css({
			"transform" : "translate(0px, "+vscroll/2+"px)"
		});
		var vscroll = jQuery(this).scrollTop();
		jQuery('#back-flower').css({
			"transform" : "translate("+vscroll/10+"px , -"+vscroll/10+"px)"
		});
		var vscroll = jQuery(this).scrollTop();
		jQuery('#for-flower').css({
			"transform" : "translate(0px , -"+vscroll/6+"px)"
		});

	});
	function modalDetails(id){
		var data = {"id":id};
		jQuery.ajax({
			url : <?php echo BASEURL; ?>+'includes/modal.php',
			method : "post",
			data : data,
			success : function(data){
				jQuery('body').append(data);
				jQuery('#details-modal').modal('toggle');
			},
			error : function(){
				alert('Something is wrong!');
			}
		});
		
	}


function update_cart(mode,edit_id,edit_size){
	data = {"mode" : mode, "edit_id" : edit_id, "edit_size" : edit_size};
	jQuery.ajax({
		data : data,
		method : "post",
		url : "/ecommerce/admin/parser/update_cart.php",
		success : function(){location.reload();},
		error : function(){alert("Something went wrong!");},
	});
}


function add_to_cart(){
	jQuery('#modal_error').html("");
	var size = jQuery('#size').val();
	var quantity = jQuery('#quantity').val();
	var available = jQuery('#available').val();
	var error = '';
	var data = jQuery('#add_product_form').serialize();
	if( size == '' || quantity == '' || quantity == 0 ){
		error += '<p class="text-danger text-center">You must be chose quantity and size</p>';
		jQuery('#modal_error').html(error);
		return;
	}else if(quantity>available){
		error += '<p class="text-danger text-center">There are only '+available+' available</p>';
		jQuery('#modal_error').html(error);
		return;
	}else{
		jQuery.ajax({
			url : '/ecommerce/admin/parser/add_cart.php',
			method : 'POST',
			data : data,
			success : function(){location.reload();},
			error : function(){alert('Something went wrong!');}
		});
	}
}
</script>
</body>
</html>