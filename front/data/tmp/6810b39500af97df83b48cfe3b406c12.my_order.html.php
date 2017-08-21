<?php $this->display('inc/header.html', array (
)); ?>
<div class="container" id="container">
	<div>
		<div class="order_rq numimg">
    		<div>
    			<div class="rq_num" style="width:28%;">
    				<div class="rq_cnt"><?php echo $this->_vars->train_cnt ; ?></div>
    				<div class="rq-text">累计训练 / 次</div>
    			</div>
    			<div class="rq_num" style="width:40%;">
    				<div class="rq_cnt"><?php echo $this->_vars->train_minute ; ?></div>
    				<div class="rq-text">累计时长 / 分</div>
    			</div>
    			<div class="rq_num" style="width:28%;">
    				<div><?php echo $this->_vars->train_day ; ?></div>
    				<div class="rq-text">累计天数 / 分</div>
    			</div>
    		</div>
		</div>
		<?php if(empty($this->_vars->order) ) {  ?>
		<div class="weui-btn_yuyue">
			<a href="<?php echo base_url() ; ?>course">预约课程</a>
		</div>
		<?php } else { ?>
			<div class="loadmore">
			<?php foreach($this->_vars->order as $this->_vars->val ) {  ?>
			<div class="ycard" data="<?php echo $this->_vars->val['order_id'] ; ?>">
				<div>
					<img src="<?php echo $this->_vars->val['pic_persion'] ; ?>" />
				</div>
				<div class="order-info <?php echo $this->_vars->val['status']=='2'?'no_img':'yes_img' ; ?>">
					<div style="line-height: 6vw;padding-top: 1vw;"><?php echo $this->_vars->val['store_name'] ; ?></div>
					<div style="line-height: 6vw;">
						<?php echo $this->_vars->val['time'] ; ?>:00-<?php echo intval($this->_vars->val['time'])+1 ; ?>:00
						<?php echo date('m月d日',strtotime($this->_vars->val['date'])) ; ?> 
						| <?php echo $this->_vars->val['num'] ; ?>人
					</div>
					<div  style="line-height: 6vw;padding-bottom: 1vw;"><?php echo $this->_vars->val['course_name'] ; ?>(<?php echo $this->_vars->val['coach_name'] ; ?>)</div>
					<div>
						<?php if($this->_vars->val['can_sign'] ) {  ?>
						<a class="cc" href="<?php echo base_url() ; ?>tasks/confirm_class/<?php echo $this->_vars->val['order_id'] ; ?>">签到</a>
						<?php } elseif( $this->_vars->val['status'] == '1' && $this->_vars->val['is_confirm'] == '1' ) {  ?>
						<a class="cc" href="javascript:void(0);">已签到</a>
						<?php } else { ?>
						<a class="cc" href="javascript:void(0);"><?php echo $this->_vars->status_arr[$this->_vars->val['status']] ; ?></a>
						<?php } ?>
					</div>
				</div>
			</div>	
			<?php } ?>
			</div>
		<?php } ?>
	</div>
</div>
<?php $this->display('inc/tabbar.html', array (
)); ?>
<?php $this->display('inc/footer.html', array (
)); ?>