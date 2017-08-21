$(function(){
	$('#role_id').change(function(){
		if($(this).val() == 1){
			$('#store_id').hide();
		}else{
			$('#store_id').show();
		}
	});
	
});
    
    