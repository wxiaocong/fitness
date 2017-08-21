<?php $this->display('inc/header.html', array (
)); ?>
<div class="container" id="container">
	<div class="content">
		<div class="tel_img">
			<a href="tel:<?php echo $this->_vars->tel ; ?>"><img alt="" src="<?php echo base_url() ; ?>static/image/tel.png"></a>
		</div>
		<a class="tel-num" href="tel:<?php echo $this->_vars->tel ; ?>"><?php echo $this->_vars->tel ; ?></a>
		<div class="ts_msg">
			<div>您的订单信息已提交成功</div>
			<div>请致电门店进行最终确认</div>
		</div>
		<div class="weui-cell weui-cell_vcode">
			<div class="weui-cell__bd" style="margin:10% auto;">
	              <input class="weui-input" id="tel" type="tel" placeholder="请输入联系手机号">
	               <button class="weui-submit-btn" id="showToast">提交号码</button>
	        </div>
        </div>
        
        <div>
        	<a href="<?php echo base_url() ; ?>order" style="background-color: #1AAD19;width: 35%;float: left;margin: 0 5% 0 10%;border-radius: 5px;" class="weui-btn weui-btn_plain-primary">订单列表</a>
        	<a href="<?php echo base_url() ; ?>order/detail/<?php echo $this->_vars->order_id ; ?>" style="background-color: #1AAD19;width: 35%;float: left;margin: 0 10% 0 5%;border-radius: 5px;" class="weui-btn weui-btn_plain-primary">查看订单</a>
        </div>
	</div>
</div>
<div id="toast" style="display: none;">
    <div class="weui-mask_transparent"></div>
    <div class="weui-toast">
        <i class="weui-icon-success-no-circle weui-icon_toast"></i>
        <p class="weui-toast__content">提交成功</p>
    </div>
</div>
<script type="text/javascript">
document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
    WeixinJSBridge.call('hideOptionMenu');
});
</script>
<?php $this->display('inc/tabbar.html', array (
)); ?>
<?php $this->display('inc/footer.html', array (
)); ?>