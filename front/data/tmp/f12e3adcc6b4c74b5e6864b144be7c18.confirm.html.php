<?php $this->display('inc/header.html', array (
)); ?>
<form method="post" id="myform" action="<?php echo base_url() ; ?>order/complate">
<div class="container" id="container">
	<!--订单开始-->
	<div class="content">
		<div class="weui-confirm">
			请确认订单信息
		</div>
		<div class="confirm-item">
			<div class="item-left">课程:</div>
			<div class="item-right"><?php echo $this->_vars->course_name ; ?>（<?php echo $this->_vars->coach_name ; ?>）</div>
			<input type="hidden" name="course_id" value="<?php echo $this->_vars->course_id ; ?>" />
			<input type="hidden" name="coach_id" value="<?php echo $this->_vars->coach_id ; ?>" />
		</div>
		<div class="confirm-item">
			<div class="item-left">时间:</div>
			<div class="item-right"><?php echo $this->_vars->str_date ; ?> <?php echo $this->_vars->str_time ; ?></div>
			<input type="hidden" name="date_num" value="<?php echo $this->_vars->date_num ; ?>" />
			<input type="hidden" name="time_num" value="<?php echo $this->_vars->time_num ; ?>" />
		</div>
		<div class="confirm-item">
			<div class="item-left">地点:</div>
			<div class="item-right" style="font-size:0.12rem;"><?php echo $this->_vars->addr ; ?></div>
		</div>
		<div class="confirm-item">
			<div class="item-left">人数:</div>
			<div class="item-right people_num">
				<?php for($this->_vars->i=1;$this->_vars->i<=$this->_vars->limit_num && $this->_vars->i<=3;$this->_vars->i++ ) {  ?>
				<a href="javascript:void(0);"<?php if($this->_vars->i==1 ) {  ?> class="on"<?php } ?>><?php echo $this->_vars->i ; ?>人</a>
				<?php } ?>
			</div>
			<input type="hidden" id="people_num" name="people_num" value="1" />
		</div>
		<?php if($this->_vars->course_type == '2' ) {  ?>
		<?php if(! empty($this->_vars->package_info) ) {  ?>
		<div class="confirm-item">
			<div class="item-left">剩余次数:</div>
			<div class="item-right" style="font-size:0.12rem;"><?php echo $this->_vars->package_info['package_num'] ; ?>次</div>
		</div>
		<?php } ?>
		<div class="confirm-item">
			<div class="item-left">购买套餐:</div>
			<div class="item-right package_num">
				<?php if($this->_vars->package_num > 0 && $this->_vars->package_price > 0 ) {  ?>
				<a href="javascript:void(0);" class="package" style="width:28vw;" ><?php echo $this->_vars->package_price ; ?>元  / <?php echo $this->_vars->package_num ; ?>次</a>
				<?php } ?>
			</div>
			<input type="hidden" id="is_package" name="is_package" value="0" />
		</div>
		<?php } ?>
		<div class="confirm-item count_item">
			<div class="item-left">总价:</div>
			<div class="item-right total_price"><?php echo $this->_vars->price ; ?>元</div>
			<input type="hidden" name="price" id="price" value="<?php echo $this->_vars->price ; ?>" />
			<input type="hidden" name="package_price" id="package_price" value="<?php echo $this->_vars->package_price ; ?>" />
		</div>
		<div class="yh_item">
			<div class="confirm-item">
				<div class="item-left">Fusion VIP折后价:</div>
				<div class="count-item-right item_vip">
					<?php if($this->_vars->is_vip ) {  ?>
						<?php echo $this->_vars->price*$this->_vars->vip_discount ; ?>元
					<?php } else { ?>
					<a href="<?php echo base_url() ; ?>card/open_member">尚未开通 ></a>
					<?php } ?>
				</div>
				<input type="hidden" id="is_vip" value="<?php echo $this->_vars->is_vip ; ?>" />
				<input type="hidden" id="vip_discount" value="<?php echo $this->_vars->vip_discount ; ?>" />
			</div>
			<div class="confirm-item">
				<div class="item-left">代金卷:</div>
				<div class="count-item-right">2张可用,点击选择 ></div>
				<input type="hidden" id="coupon_id" name="coupon_id" value="0" />
			</div>
			<div class="confirm-item item-bottom">
				<div class="item-left">还需支付:</div>
				<div class="count-item-right pay-money" style="font-size:4.76vw;">
					<?php if($this->_vars->is_vip ) {  ?>
						<?php echo $this->_vars->price*$this->_vars->vip_discount ; ?>元
					<?php } else { ?>
						<?php echo $this->_vars->price ; ?>元
					<?php } ?>	
				</div>
			</div>
		</div>
		
		<div class="bottom_ts">
			温馨提示：开始时间前6小时取消预约，支持全额退款;开始时间6小时内不支持退款。
		</div>
	</div>
	<!--订单结束-->
</div>
<div class="weui-tabbar">
	<a href="javascript:void(0);" class="weui-btn weui-btn_primary submit_form" >提交订单</a>
</div>
</form>
<div id="loadingToast" style=" display: none;">
        <div class="weui-mask_transparent"></div>
        <div class="weui-toast">
            <i class="weui-loading" style="display:inline-block;margin-top:25px"></i>
        </div>
</div>
<?php $this->display('inc/footer.html', array (
)); ?>

