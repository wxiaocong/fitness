<?php $this->display('inc/header.html', array (
)); ?>
<div class="container" id="container">
	<div class="weui-confirm">请选择充值额度</div>
	<div class="confirm-item">
		<div class="item-left">充值账户：</div>
		<div class="item-right"><?php echo $this->_vars->nickname ; ?></div>
	</div>
	<div class="confirm-item">
		<div class="item-left">余额：</div>
		<div class="item-right"><?php echo $this->_vars->is_open?$this->_vars->balance:'尚未开通' ; ?></div>
	</div>
	<?php if($this->_vars->bind_coach_id ) {  ?>
	<div class="confirm-item">
		<div class="item-left">绑定教练：</div>
		<div class="item-right"><?php echo $this->_vars->coach_name ; ?></div>
	</div>
	<div class="confirm-item weui-cell weui-cell_switch">
		<div class="item-left weui-cell__bd">充值套餐：</div>
           <div class="weui-cell__ft">
               <input type="checkbox" class="weui-switch">
               <input type="hidden" id="pay_type" name="pay_type" type="radio" value="off" checked="checked" />
           </div>
	</div>
	<div class="confirm-item weui-cell weui-cell_select weui-cell_select-after" style="display:none;">
       <div class="item-left">教练课程：</div>
       <div class="weui-cell__bd">
           <select class="weui-select" name="package">
           		<option></option>
           		<?php if(! empty($this->_vars->course_list) ) {  ?>
           	   <?php foreach($this->_vars->course_list as $this->_vars->val ) {  ?>
               <option value="<?php echo $this->_vars->val['course_id'] ; ?>"><?php echo $this->_vars->val['course_name'] ; ?>(<?php echo $this->_vars->val['package_price'] ; ?>/<?php echo $this->_vars->val['package_num'] ; ?>次)</option>
               <?php } ?>
               <?php } ?>
           </select>
       </div>
    </div>
	<?php } ?>
	<div class="confirm-item recharge_amount">
		<div class="item-left">充值额度：</div>
		<div class="item-right people_num">
				<a href="javascript:void(0);" class="on">500</a>
				<a href="javascript:void(0);" class="on">2000</a>
				<a href="javascript:void(0);" class="on">5000</a>
			</div>
	</div>	
	<div class="bottom_ts">
			温馨提示：充值余额，可与代金卷同时使用。
	</div>	
</div>
<div id="loadingToast" style=" display: none;">
        <div class="weui-mask_transparent"></div>
        <div class="weui-toast">
            <i class="weui-loading" style="display:inline-block;margin-top:25px"></i>
        </div>
</div>
<input type="hidden" id="host" value="<?php echo base_url() ; ?>">
<script type="text/javascript" src="<?php echo base_url() ; ?>static/js/jquery.min.js"></script>
<?php if(isset($this->_vars->footerJs) && !empty($this->_vars->footerJs) ) {  ?>
<?php foreach($this->_vars->footerJs as $this->_vars->key => $this->_vars->value ) {  ?>
<script type="text/javascript" src="<?php echo base_url() ; ?>static/js/<?php echo $this->_vars->value ; ?>?v=2.3"></script>
<?php } ?>
<?php } ?>
<script type="text/javascript">
//开关
$('.weui-switch').click(function(){
	if($('#pay_type').val() == 'off'){
		$("#pay_type").val("on");
		$('.weui-cell_select').show();
		$('.recharge_amount').hide();
	}else{
		$("#pay_type").val("off");
		$('.weui-cell_select').hide();
		$('.recharge_amount').show();
	}
});

var host = $('#host').val(); 
var loadingToast = $('#loadingToast');	//进度
	
//套餐支付
$('.weui-select').change(function(){
	var course_id = $(this).val();
	if(course_id > 0){
		if (loadingToast.css('display') != 'none') 
			return;
	    loadingToast.fadeIn(100);
	    $.ajax({
			type:"POST",
			url: host + "card/pay_package",
			data:{course_id:course_id},
			dataType:"JSON",
			success:function(d){
				loadingToast.fadeOut(100);
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
					               window.location.replace(host+'card');
					        }else{
					        	//alert(res.err_code+res.err_desc+res.err_msg);
					        }
						}
					);
				}else{
					alert(d.msg);
				}
			}
	    });
	}
});
//金额支付	
$('.people_num').on('click','a',function(){
	
	if (loadingToast.css('display') != 'none') return;
    loadingToast.fadeIn(100);
	
	var money = $(this).text();
	$.ajax({
		type:"POST",
		url: host + "card/pre_pay",
		data:{money:money},
		dataType:"JSON",
		success:function(d){
			loadingToast.fadeOut(100);
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
				               window.location.replace(host+'card');
				        }else{
				        	//alert(res.err_code+res.err_desc+res.err_msg);
				        }
					}
				);
			}
		}
	});
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
	
document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
    WeixinJSBridge.call('hideOptionMenu');
});
</script>
</body>
</html>