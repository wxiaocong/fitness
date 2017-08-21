var w = new Array("日", "一", "二", "三", "四", "五", "六"); 
//默认当天开始
$('.week_item th').each(function(i){
	var d = new Date();
	if(i > 0){
		d.setTime(d.getTime()+24*60*60*1000*(i-1));
		$(this).html('周'+w[d.getDay()]);
	}
});
$('.date_item th').each(function(i){
	var d = new Date();
	if(i > 0){
		d.setTime(d.getTime()+24*60*60*1000*(i-1));
		$(this).html(d.getFullYear()+"-" + (d.getMonth()+1) + "-" + d.getDate());
	}
});



//更改分店
$('#store').change(function(){
	var store_id = $(this).val();
	var start_date = $('#start_date').val();
	var url = $('#start_date').attr('url')+store_id;
	
	if(store_id){
		change_schedule(start_date);
	}	
});

//更改开始日期,更新数据
function change_start_date(dp){
	var olddate = dp.cal.getDateStr();
	var newdate = dp.cal.getNewDateStr();
	var date = new Date();
	if(olddate != newdate){
		$('.week_item th').each(function(i){
			var d = new Date(newdate);
			if(i > 0){
				d.setTime(d.getTime()+24*60*60*1000*(i-1));
				$(this).html('周'+w[d.getDay()]);
			}
		});
		$('.date_item th').each(function(i){
			var d = new Date(newdate);
			if(i > 0){
				d.setTime(d.getTime()+24*60*60*1000*(i-1));
				$(this).html(d.getFullYear()+"-" + (d.getMonth()+1) + "-" + d.getDate());
			}
		});
		change_schedule(newdate);
	}
}
//更新数据
function change_schedule(newdate){
	var store_id = $('#store').val();
	var date_url = $('#start_date').attr('url');
	$.getJSON(date_url,{store_id:store_id,start_date:newdate},function(data){
		if( ! $.isEmptyObject(data)){
			var m = n = 0;
			$.each(data,function(index,value){
				n = 0;
				$.each(value,function(i,v){
					var num = m+n*7;
					$('.tbody td:eq('+num+')').html(v);
					if(v.indexOf('<br>') > 0){
						$('.tbody td:eq('+num+')').addClass('more_data');
					}else{
						$('.tbody td:eq('+num+')').removeClass('more_data');
					}
					n += 1;
				})
				m += 1;
			});
		}
	});	
}
