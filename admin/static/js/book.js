var host = $('#host').val();
//更改分店取课程分类
$('#store').change(function(){
	var store_id = $(this).val();
	var tag_url = host + 'order/book/get_store_tag/' + store_id
	
	if(store_id){
		$('#tag').attr("disabled","disabled");
		$("#course_type").val("1"); //普通课程
		$.getJSON(tag_url,function(data){
			var html = '<option></option>';
			if($.isEmptyObject(data)){
				alert("未获取到课程分类列表.");
			}else{
				$.each(data,function(index,value){
					html = html + "<option value='"+value.tag_id+"'>"+value.tag_name+"</option>";
				});
			}
			$('#tag').html(html).removeAttr('disabled');
			$('#course').empty();
		});
	}	
});

//更改课程类别/分类取课程信息
$('#course_type,#tag').change(function(){
	get_course();
});

//获取课程列表
function get_course(){
	var store_id = $('#store').val();
	var course_type = $('#course_type').val();
	var tag_id = $('#tag').val();
	
	if(store_id && tag_id && course_type){
		$('#course_type,#tag,#course,#coach').attr("disabled","disabled");
		$.ajax({
			type:"POST",
			url:host+'order/book/get_course',
			data:{store_id:store_id,course_type:course_type,tag_id:tag_id},
			success:function(data){
				var html = '<option></option>';
				if($.isEmptyObject(data)){
					alert("未获取到课程列表.");
				}else{
					$.each(data,function(index,value){
						html = html + "<option value='"+value.course_id+"'>"+value.course_name+"</option>";
					});
				}
				$('#course').html(html);
				$('#course_type,#tag,#course,#coach').removeAttr('disabled');
			},
			dataType:"JSON"
		});
	}	
}

//更改课程,获取教练列表,修改价格
$('#course').change(function(){
	var course_id = $(this).val();
	if(course_id){
		$('#course_type,#tag,#course,#coach').attr("disabled","disabled");
		$.getJSON(host+'order/book/get_course_price_coach/'+course_id,function(data){
			if($.isEmptyObject(data)){
				alert("未找到课程.");
			}else{
				if(data.course_type == '1'){
					$("#course_price").html(data.price);
				}else{
					var optionHtml = "<select name='payType' style='width:80px;'><option value='0'>"+data.price+"</option><option value='1'>1次</option></selected>";
					$("#course_price").html(optionHtml);
				}
				$('#course_max_order').html(data.num);
				
				var html = '<option></option>';
				$.each(data.coach,function(index,value){
					html = html + "<option value='"+value.coach_id+"'>"+value.coach_name+"</option>";
				});
				$('#coach').html(html);
				$('#course_type,#tag,#course,#coach').removeAttr('disabled');
				
				get_date_schedule();
			}
		});
	}
});

$('#coach').change(function(){
	get_date_schedule();
});
//更改时间
function change_start_date(dp){
	get_date_schedule();
}

//获取各时间点课程预约情况
function get_date_schedule(){
	var course_id = $('#course').val();
	var coach_id = $('#coach').val();
	var date = $('#date').val();	
	if(course_id && coach_id && date ){
		$.ajax({
			type:"POST",
			url:host+'order/book/get_date_schedule',
			data:{course_id:course_id,coach_id:coach_id,date:date},
			success:function(res){
				if( ! $.isEmptyObject(res)){
					var html = '<option></option>';
					$.each(res,function(index,value){
						html = html + "<option value='"+index+"'>"+value.time+'['+value.data+']'+"</option>";
					});
					$('#time').html(html);
				}  
			},
			dataType:"JSON"
		});
	}
}

//查询课程信息-作废
$('#pre_order').click(function(){
	var course_id = $('#course').val();
	var coach_id = $('#coach').val();
	var date = $('#date').val();
	var time = $('#time').val();
	if(course_id != '' && date != '' && time != ''){
		$.ajax({
			type:"POST",
			url:host+'order/book/get_order_num',
			data:{course_id:course_id,coach_id:coach_id,date:date,time:time},
			success:function(data){
				if(data.status == '1'){
					$('#have_order_num').html(data.num);
					$('#order_num').show();
					
					var num = parseInt($('#num').val());
					var max_order = parseInt($('#course_max_order').html());
					
					if(max_order < num + data.num){
						$('#num').focus();
						var d = dialog({
							content: '预约人数超过课程可预约人数',
							padding:20
						}).show();
					}else{
						var d = dialog({
							content: '可预约,请选择用户',
							padding:20
						}).show();
					}
				}else{
					var d = dialog({
						content: data.msg,
						padding:20
					}).show();
				}
				setTimeout(function () {
					d.close().remove();
				}, 2000);
			},
			dataType:"JSON"
		});
	}
});

//查找用户
$('#search_user').click(function(){
	var no = $('#no').val().trim();
	var phone = $('#phone').val().trim();
	var nickname = $('#nickname').val().trim();
	
	if(no != '' || phone != '' || nickname != ''){
		$.ajax({
			type:"POST",
			url:host+'order/book/search_user_info',
			data:{no:no,phone:phone,nickname:nickname},
			success:function(html){
				if(html != ''){
					$('#userinfo').html(html).show();
					$('#confirm_order').removeAttr('disabled');
				}else{
					var d = dialog({
						content: '未找到该用户',
						padding:20
					}).show();
					setTimeout(function () {
						d.close().remove();
					}, 2000);
				}
			}
		});
	}
});

//确认预约
$('#confirm_order').click(function(){
	if($('#course').val() != '' && $('#coach').val() != '' && $('#time').val() !== '' && $('#user_id').val() > 0){
		$.ajax({
			type:"post",
			url:host+'order/book/submit_order',
			data:$('#order_form').serialize(),
			success:function(res){
				if( ! $.isEmptyObject(res)){
					var d = dialog({
						content: res.msg,
						padding:20
					}).show();
					setTimeout(function () {
						d.close().remove();
						if(res.status == '1'){
							window.location.href=host+'order/order/detail/'+res.order_id;
						}
					}, 1000);
				}
			},dataType:"JSON"
		});
	}
});
