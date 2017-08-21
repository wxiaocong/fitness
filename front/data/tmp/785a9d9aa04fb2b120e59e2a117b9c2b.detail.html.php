<?php $this->display('inc/header.html', array (
)); ?>
<div class="container" id="container">
	<div>
		<?php if(! empty($this->_vars->video) ) {  ?>
		<video style="height:50vw;width:100%;" src="<?php echo $this->_vars->video ; ?>" autoplay="autoplay"></video>
		<?php } else { ?>
		<img style="height: 50vw;width:100%;" src="<?php echo $this->_vars->pic ; ?>">
		<?php } ?>
	</div>
	<!--课程开始-->
	<div class="content">
		<!--标签-->
			<?php if(! empty($this->_vars->tag) ) {  ?>
			<table class="tag_table">
				<tr>
				<?php foreach($this->_vars->tag as $this->_vars->v ) {  ?>
				<td><?php echo $this->_vars->v ; ?></td>
				<?php } ?>
				</tr>
			</table>
			<?php } ?>
		<!--教练-->
		<div class="panel course_detai_width">
			<div class="panel_jj">
				<div class="tec_name"><?php echo $this->_vars->coach_name ; ?></div>
				<div class="tec_profile"><?php echo $this->_vars->profile ; ?></div>
			</div>
			<div class="pic"><img src="<?php echo $this->_vars->pic_persion ; ?>" /></div>
		</div>
		<?php if($this->_vars->course_type=='2' ) {  ?>
	     <div class="panel course_detai_width">
			<div class="panel_jg">
				<div class="course_price" style="font-size:4vw;"  onClick="javascript:window.location.href='<?php echo base_url() ; ?>order/train/<?php echo $this->_vars->order_id ; ?>'">
					<img style="float:left;" src="<?php echo base_url() ; ?>static/image/log.png" /> 
					<span style="margin-top: 2px;display: inline-block;">训练日志</span>
					<div class="weui-cell__ft"></div>
				</div>
			</div>
		</div>
		<?php } ?>
		<!--课程-->
		<div class="panel course_detai_width">
			<div class="panel_jg">
				<div class="course_price" style="font-size:4vw;">
					<img style="float:left;" src="<?php echo base_url() ; ?>static/image/course.png" /> 
					<span style="margin-top: 2px;display: inline-block;"><?php echo $this->_vars->course_name ; ?></span>
				</div>
			</div>
		</div>
		<!--时间-->
		<div class="panel course_detai_width" style="margin-top: 0.5vw">
			<div class="panel_jg">
				<div class="course_price" style="font-size:4vw;">
					<img style="float:left;" src="<?php echo base_url() ; ?>static/image/time.png" /> 
					<span style="margin-top: 2px;display: inline-block;"><?php echo $this->_vars->orderTime ; ?></span>
				</div>
			</div>
		</div>
		<!--费用地址-->
		<div class="panel course_detai_width"  style="margin-top: 0.5vw">
			<div class="panel_jg">
				<div class="course_addr" onClick="javascript:window.location.href='<?php echo $this->_vars->addr_link ; ?>'">
					<div style="width: 90%;display: inline-block;font-size:3vw;"><?php echo $this->_vars->addr ; ?></div>
					<div style="float:left;"><img src="<?php echo base_url() ; ?>static/image/addr.png" /></div>
				</div>
			</div>
		</div>
		
		<!--注意事项-->
		<div class="panel course_detai_width">
			<div class="panel_jg">
				<div class="course_price" style="font-size:4vw;">
					<img style="float:left;" src="<?php echo base_url() ; ?>static/image/notice.png" /> 
					<span style="margin-top: 1px;display: inline-block;">注意事项</span>
				</div>
			</div>
		</div>
		<div class="panel course_detai_width" style="margin-top: 0.5vw">
			<div class="panel_jg" style="padding: 8px;">
				<div class="course_jj">
					<?php echo $this->_vars->notice ; ?>
				</div>
			</div>
		</div>
	</div>
	<!--课程结束-->
</div>
<div class="weui-tabbar">
	<?php if(strtotime($this->_vars->dateTime)-3600*6 > time() ) {  ?>
	<a href="<?php echo base_url() ; ?>order/cancel/<?php echo $this->_vars->order_id ; ?>" class="weui-tabbar__item" 
		style="webkit-flex:7;flex:7;background-color:#da3720;padding-top:0;height:12vw;line-height:12vw;">
		<span class="weui-tabbar__label" style="color: #fff;">取消预约</span>
	</a>
	<?php } elseif(strtotime($this->_vars->dateTime)-3600*6 <= time() && strtotime($this->_vars->dateTime)-3600*3 > time() ) {  ?>
	<a href="javascript:;" id="laterCancel" class="weui-tabbar__item" 
		style="webkit-flex:7;flex:7;background-color:#da3720;padding-top:0;height:12vw;line-height:12vw;">
		<span class="weui-tabbar__label" style="color: #fff;">取消预约</span>
	</a>
	<?php } elseif(strtotime($this->_vars->dateTime)-3600*3 <= time() && strtotime($this->_vars->dateTime)-1800 > time() ) {  ?>
	<a href="javascript:;" class="weui-tabbar__item" 
		style="webkit-flex:7;flex:7;background-color:#gray;padding-top:0;height:12vw;line-height:12vw;">
		<span class="weui-tabbar__label" style="color: #fff;">签到</span>
	</a>
	<?php } elseif($this->_vars->is_confirm=='0' && strtotime($this->_vars->dateTime)-1800 <= time() && strtotime($this->_vars->dateTime) + 3600*7 > time() ) {  ?>
	<a href="<?php echo base_url() ; ?>tasks/confirm_class/<?php echo $this->_vars->order_id ; ?>" class="weui-tabbar__item" 
		style="webkit-flex:7;flex:7;background-color:#da3720;padding-top:0;height:12vw;line-height:12vw;">
		<span class="weui-tabbar__label" style="color: #fff;">签到</span>
	</a>
	<?php } elseif($this->_vars->is_confirm=='1' && strtotime($this->_vars->dateTime)-1800 <= time() && strtotime($this->_vars->dateTime) + 3600*7 > time() ) {  ?>
	<a href="javascript:;" class="weui-tabbar__item" 
		style="webkit-flex:7;flex:7;background-color:#da3720;padding-top:0;height:12vw;line-height:12vw;">
		<span class="weui-tabbar__label" style="color: #fff;">已签到</span>
	</a>
	<?php } else { ?>
	<a href="javascript:;" class="weui-tabbar__item" 
		style="webkit-flex:7;flex:7;background-color:#da3720;padding-top:0;height:12vw;line-height:12vw;">
		<span class="weui-tabbar__label" style="color: #fff;">
			<?php if($this->_vars->is_confirm=='1' ) {  ?>已完成<?php } else { ?>已结束<?php } ?>
		</span>
	</a>
	<?php } ?>
	
</div>
<div id="toast" style="display: none;">
    <div class="weui-mask_transparent"></div>
    <div class="weui-toast">
        <i class="weui-icon-success-no-circle weui-icon_toast"></i>
        <p class="weui-toast__content"></p>
    </div>
</div>
<?php $this->display('inc/footer.html', array (
)); ?>

<script type="text/javascript">
if ("<?php echo $this->_vars->firstConfirm ; ?>" == '1') {
	$('#toast .weui-toast__content').html('签到成功');
	var toast = $('#toast');
	toast.fadeIn(100);
	setTimeout(function () {
	    toast.fadeOut(100);
	}, 2000);
}else if ("<?php echo $this->_vars->firstConfirm ; ?>" == 2){
	$('#toast .weui-toast__content').html('已超时,不能签到');
	toast.fadeIn(100);
	setTimeout(function () {
	    toast.fadeOut(100);
	}, 2000);
}
$('#laterCancel').click(function(){
	$('#toast .weui-toast__content').html('请联系您的会籍顾问或教练，协助您取消预约');
	toast.fadeIn(100);
	setTimeout(function () {
	    toast.fadeOut(100);
	}, 3000);	
});
</script>