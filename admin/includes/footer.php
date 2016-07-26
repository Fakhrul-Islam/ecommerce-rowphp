<!--- Footer -->
<footer class="text-center navbar navbar-default" id="footer">
	&copy; copyright 15-16 by Silver Design
</footer>
<script>
	function updateSize(){
		var sizeString = '';
		for(var i=1; i<=12; i++){
			if( jQuery('#size'+i).val() != '' ){
				sizeString += jQuery('#size'+i).val()+':'+jQuery('#qty'+i).val()+',';
			}
		}
		jQuery('#sizes').val(sizeString);
	}
	function get_child_options(selected){
		if(typeof selected == 'undefined'){ 
			var selected = '';
		}
		var parentID = jQuery('#parent').val();
		jQuery.ajax({
			url : 'parser/child_category.php',
			method : "post",
			data : {'parentID':parentID, 'selected':selected},
			success : function(data){
				jQuery('#child').html(data);
			},
			error : function(){
				alert('Something is wrong with child');
			},
		});
	}
	jQuery('select[name="parent"]').change(function(){
		get_child_options();
	});
</script>
</body>
</html>