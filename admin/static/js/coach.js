var ue = UE.getEditor('notice',{imagePath:'/upload/ueditor',initialFrameWidth : 800,initialFrameHeight: 200});

$('#store_id').change(function(){
	var store_id = $(this).val();
	$('.checkbox-inline').each(function(){
		if($(this).attr('store_id') == store_id){
			$(this).show().children('input').removeAttr('disabled');
		}else{
			$(this).hide().children('input').attr('disabled','disabled');
		}
	});
});
