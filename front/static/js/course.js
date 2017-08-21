$('.course_type_item').each(function(){
	$(this).click(function(){
		window.location.href=$(this).attr('_url');
	});
});

$('.con').each(function(i){
	$(this).click(function(){
		window.location.href=$(this).attr('_url');
	});
});


//分店切换
$('#course-area').on('click','.course_item',function(){
	var store_id = $(this).attr('key');
	$('.course_type_item').each(function(m){
		if($(this).hasClass('on_course_type')){
			course_type = m + 1;
		}
	});
	window.location.href=$('#host').val()+'course/index/'+course_type+'/'+store_id
});

//标签切换
$('#course-item').on('click','.course_item',function(){
	var tag_id  = $(this).attr('key');
	$('#s_course').html($(this).html());
	if(tag_id > 0){
		$('.bd .t').each(function(){
			$(this).hide();
			var strs = new Array();
			strs = $(this).attr('tag-id').split(',');
			for(i=0;i<strs.length;i++){
				if(strs[i] == tag_id){
					$(this).show();
				}
			}
		});
	}else{
		$('.bd .t').show();
	}
});