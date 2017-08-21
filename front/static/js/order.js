var host = $('#host').val();
$('.ycard').each(function(){
	$(this).click(function(){
		var order_id = $(this).attr('data');
		if(order_id){
			window.location.href = host + 'order/detail/' + order_id
		}
	});
});

$(".weui-date-brq div").each(function(i){
	$(this).click(function(){
		$('.in_date').parent('div').html($('.in_date').html());
		$(this).html('<span  date="'+i+'" class="in_date">'+$(this).text()+'</span>');
		//取预约数据
		var course_id = $('.tabBox').attr('course');
		var coach_id = $('.tabBox').attr('coach');
		
		$.get(host+'order/get_schedule_by_date',{course_id:course_id,coach_id:coach_id,data_num:i},function(content){
			$('.tabBox').html(content);
		});
	});
});

$('.tabBox').on('click','.yy_true',function(){
	var time_num = $(this).attr('time');
	var date_num = $('.in_date').attr('date');
	var coach_id = $('.tabBox').attr('coach');
	var course_id = $('.tabBox').attr('course');
	
	var url = host+"order/confirm?course_id="+course_id+"&coach_id="+coach_id+"&date_num="+date_num+"&time_num="+time_num;
	window.location.href=url;
});

$('.people_num a').each(function(i){
	$(this).click(function(){
		$(this).addClass('on').siblings().removeClass('on');
		$('#people_num').val(i+1);
		
		var total_price = 0;
		if($('.package_num .package').hasClass('on')){
			total_price = parseInt($("#package_price").val()*100)/100 * (i+1);
		}else{
			total_price = parseInt($("#price").val()*100)/100 * (i+1);
		}
		
		$('.total_price').html(total_price+'元');
		$('.pay-money').html(total_price+'元');
	});
});

$('.package_num .package').click(function(){
	var total_price = 0;
	if($(this).hasClass('on')){
		$(this).removeClass('on');
		$('#is_package').val(0);
		
		total_price = parseInt($("#price").val()*100)/100 * parseInt($('#people_num').val());
	}else{
		$(this).addClass('on');
		$('#is_package').val(1);
		
		total_price = parseInt($("#package_price").val()*100)/100 * parseInt($('#people_num').val());
	}
	$('.total_price').html(total_price+'元');
	$('.pay-money').html(total_price+'元');
});

var firstSubmit = 1;
function commit_pay(){
	firstSubmit = 0;
	$('.submit_form').click();
}

$('.submit_form').click(function(){
    //检查金额
    if(firstSubmit && $('#is_package').val() == 0){
    	//firstSubmit = 0;
	    $.get($('#host').val()+'order/checkMoney',{course_id:$('#course_id').val(),coach_id:$('#coach_id').val(),people_num:$('#people_num').val()},function(res){
	    	if(res.length > 0){
	    		$('#noticeCont').html(res);
	    		$('#iosDialog').show();
	    	}
	    });
    }else{
	    var loadingToast = $('#loadingToast');	
		if (loadingToast.css('display') != 'none') 
			return;
	    loadingToast.fadeIn(100);
	    $(this).removeClass('submit_form');
		$.post($('#myform').attr('action'),$('#myform').serialize(),function(d){
			loadingToast.fadeOut(100);
			if(d.order_id > 0 && (d.pay_type == 1 || d.pay_type == 3 || d.pay_type == 4)){
				 window.location.replace(host+'order/success/'+d.order_id);
				 return false;
			}
			if(d.return_code == 'SUCCESS'){
				var paySign = '';
				var timeStamp = Math.round(new Date().getTime()/1000).toString();
				//获取签名
				$.ajax({
					type:"POST",
					url:host+'card/get_sign',
					data:{timeStamp:timeStamp,nonceStr:d.nonce_str,prepay_id:d.prepay_id},
					async:false,
					success: function(str){
						paySign = str;
					}
				});
				WeixinJSBridge.invoke(
					'getBrandWCPayRequest',
					{
					   "appId":d.appid,     //公众号名称，由商户传入     
					   "timeStamp":timeStamp,
					   "nonceStr":d.nonce_str, //随机串    
					   "package":"prepay_id="+d.prepay_id,     
					   "signType":"MD5",         //微信签名方式
					   "paySign":paySign
					},
					function(res){
						//WeixinJSBridge.log(res.err_msg);							
						if(res.err_msg == "get_brand_wcpay_request:ok" ) {
				               window.location.replace(host+'order/success/'+d.order_id);
				        }else{
				        	//alert(res.err_code+res.err_desc+res.err_msg);
				        }
					}
				);
			}else{
				alert(d.msg);
			}
		},"JSON");
    }
});

if (typeof WeixinJSBridge == "undefined"){
	   if( document.addEventListener ){
	       document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
	   }else if (document.attachEvent){
	       document.attachEvent('WeixinJSBridgeReady', onBridgeReady); 
	       document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
	   }
	}else{
	   onBridgeReady();
}

function writeObj(obj){ 
	 var description = ""; 
	 for(var i in obj){ 
	 var property=obj[i]; 
	 description+=i+" = "+property+"\n"; 
	 } 
	 alert(description); 
	} 