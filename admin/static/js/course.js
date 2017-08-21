$(function () {
	$('.assign').each(function(){
		$(this).click(function(){
			var role_id = $(this).attr('role');
			var course_id = $(this).attr('data');
			if(role_id == '1'){
				//更换课程教练
				$.ajax({
					url:$('#host').val() + 'course/course/get_course_coach',
					type: "POST",
					data:{course_id:course_id},
					async: false,
					success:function(html){
						$('.modal-body').html(html);
					}
				});
			}
			$('#assign').attr('course_id',course_id);
			var url =  $('#host').val() + 'course/course/get_choose_coach';
			//清除数据
			$('.coach_name').prop('checked',false);
			$.post(url,{course_id:course_id},function(data){
				$.each(data,function(index,value){
					$('.coach_name[value='+value+']').prop('checked',true);
				});
			},"JSON");
			$('#assign').modal('show');	
		});
	});
	
	
	$('#save_assign').click(function(){
		var url = $('#host').val() + 'course/course/save_assign';
		var course_id = $('#assign').attr('course_id');
		var coach = new Array()
		$('.coach_name').each(function(){
			if($(this).is(':checked')){
				coach.push($(this).val());
			}
		});
		$.post(url,{course_id:course_id,coach_id:coach.join(',')},function(msg){
			setTimeout(function(){
				window.location.reload();
			},1000);
			alert('分配成功.');
		});
	});
})