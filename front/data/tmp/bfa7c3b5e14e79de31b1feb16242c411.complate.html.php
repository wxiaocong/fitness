<?php $this->display('inc/header.html', array (
)); ?>
<div class="container" id="container">
	<div class="content">
		<div class="nothing"></div>
		<div class="tel_img">
			<img alt="" src="<?php echo base_url() ; ?>static/image/tel.png">
		</div>
		<div class="tel-num">
			0755-83926657
		</div>
		<div class="ts_msg">
			<div>您的订单信息已提交成功</div>
			<div>请致电门店进行最终确认</div>
		</div>
		<div class="weui-cell weui-cell_vcode">
			<div class="weui-cell__bd" style="margin:18vw auto;">
	              <input class="weui-input" id="tel" type="tel" placeholder="请输入联系手机号">
	               <button class="weui-submit-btn" id="showToast">提交号码</button>
	        </div>
        </div>
	</div>
</div>
<div class="weui-tabbar">
	<a href="tel:075584034980" class="weui-tabbar__item weui-bar__item_on" style="webkit-flex:7;flex:7;" >
		<span class="weui-tabbar__label">拨号</span>
	</a>
	<a href="<?php echo base_url() ; ?>" class="weui-tabbar__item" style="webkit-flex:3;flex:3;background-color: #333;">
		<span class="weui-tabbar__label" style="color:#fff;">返回首页</span>
	</a>
</div>
<div id="toast" style="display: none;">
    <div class="weui-mask_transparent"></div>
    <div class="weui-toast">
        <i class="weui-icon-success-no-circle weui-icon_toast"></i>
        <p class="weui-toast__content">提交成功</p>
    </div>
</div>
<input type="hidden" id="order_id" value="<?php echo $this->_vars->order_id ; ?>">
<input type="hidden" id="host" value="<?php echo base_url() ; ?>">
<script type="text/javascript" src="<?php echo base_url() ; ?>static/js/jquery.min.js"></script>
<?php if(isset($this->_vars->footerJs) && !empty($this->_vars->footerJs) ) {  ?>
<?php foreach($this->_vars->footerJs as $this->_vars->key => $this->_vars->value ) {  ?>
<script type="text/javascript" src="<?php echo base_url() ; ?>static/js/<?php echo $this->_vars->value ; ?>?v=2.3"></script>
<?php } ?>
<?php } ?>
</body>
</html>