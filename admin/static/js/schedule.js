//更改分店
$('#store').change(function(){
	var store_id = $(this).val();
	var course_url = $('#course').attr('url')+store_id;
	
	if(store_id){
		$('#course').attr("disabled","disabled");
		$.getJSON(course_url,function(data){
			var html = '<option></option>';
			if($.isEmptyObject(data)){
				alert("未获取到门店课程列表.");
			}else{
				$.each(data,function(index,value){
					html = html + "<option value='"+index+"'>"+value+"</option>";
				});
			}
			$('#course').html(html).removeAttr("disabled");
			$('#coach').empty();
		});
	}else{
		$('#course').empty();
		$('#coach').empty();
	}
	$('.panel-default').find('input').prop("checked", false);
});

//更改课程
$('#course').change(function(){
	var course_id = $(this).val();
	var coach_url = $('#coach').attr('url')+course_id;
	if(course_id){
		$('#coach').attr("disabled","disabled");
		$.getJSON(coach_url,function(data){
			var html = '<option></option>';
			if($.isEmptyObject(data)){
				alert("未获取到课程分配教练列表.");
			}else{
				$.each(data,function(index,value){
					html = html + "<option value='"+value.coach_id+"'>"+value.coach_name+"</option>";
				});
			}
			$('#coach').html(html).removeAttr("disabled");
			
			$('.panel-default').find('input').prop("checked", false);
		});
	}else{
		$('#coach').empty();
	}
});

//更改教练，更新数据
$('#coach').change(function(){
	var d = new Date();
	var newdate = d.getFullYear()+"/" + (d.getMonth()+1) + "/" + d.getDate();
	$('#start_date').val(d.getFullYear()+"-" + (d.getMonth()+1) + "-" + d.getDate());
	
	$('.frq').each(function(i){
		var c = new Date(newdate);
		c.setTime(c.getTime()+24*60*60*1000*i);
		$(this).val(c.getFullYear()+"-" + (c.getMonth()+1) + "-" + c.getDate());
	});
	$('.panel-default').find('input').prop("checked", false);
	change_schedule(newdate);
})

//保存后显示已保存数据
var start = $('#start_date').attr('start');
if(start){
	change_schedule(start);
}

//默认当天开始
$('.frq').each(function(i){
	var d = new Date();
	d.setTime(d.getTime()+24*60*60*1000*i);
	$(this).val(d.getFullYear()+"-" + (d.getMonth()+1) + "-" + d.getDate());
});
//更改开始日期,更新数据
function change_start_date(dp){
	var olddate = dp.cal.getDateStr();
	var newdate = dp.cal.getNewDateStr();
	var date = new Date();
	if(olddate != newdate){
		$('.panel-default').find('input').prop("checked", false);
		$('.frq').each(function(i){
			var d = new Date(newdate);
			d.setTime(d.getTime()+24*60*60*1000*i);
			//不可设置当天以前的课程
			if(date.getTime() - 24*60*60*1000 >= d.getTime()){
				$(this).parent().parent().find("input[type='checkbox']").prop("disabled", true);
			}else{
				$(this).parent().parent().find("input[type='checkbox']").prop("disabled", false);
			}
			$(this).val(d.getFullYear()+"-" + (d.getMonth()+1) + "-" + d.getDate());
		});
		change_schedule(newdate);
	}
}
//更新排课数据
function change_schedule(newdate){
//	var store_id = $('#store').val();
	var course_id = $('#course').val();
	var coach_id = $('#coach').val();
	var date_url = $('#start_date').attr('url');

	$.getJSON(date_url,{course_id:course_id,coach_id:coach_id,start_date:newdate},function(data){
		$(".sep7 input[type='checkbox']").each(function(){
			$(this).prop('checked',false);
		});
		if( ! $.isEmptyObject(data)){
			$.each(data,function(index,value){
				$.each(value,function(i,v){
					var j = parseInt(index)+1;
					$("input[name='k"+j+"time[]']").each(function(){
						if($(this).val() == v.ktime){
							$(this).prop('checked',true);
						}
					});
				})
			});
		}
	});	
}
//全选
$('.select_all').click(function(){
	if($(this).is(':checked')) {
		$(this).next('.checkbox').find('input').prop("checked", true);
	}else{
		$(this).next('.checkbox').find('input').prop("checked", false);
	}
});