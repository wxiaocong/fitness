<?php $this->display('inc/header.html', array (
)); ?>
<div class="container" >
	<!--订单-->
	<div>
		<div class="weui-date">
			<div class="weui-date-rq">
				<?php foreach($this->_vars->date_num as $this->_vars->val ) {  ?>
				<div><?php echo $this->_vars->val ; ?></div>
				<?php } ?>
			</div>
			<div class="weui-date-brq">
				<div><span date="0" class="in_date">今天</span></div>
				<div>明天</div>
				<?php foreach($this->_vars->date_zn as $this->_vars->val ) {  ?>
				<div><?php echo $this->_vars->val ; ?></div>
				<?php } ?>
			</div>
		</div>
		
		<div class="tabBox" course="<?php echo $this->_vars->course_id ; ?>" coach="<?php echo $this->_vars->coach_id ; ?>">
			<?php foreach($this->_vars->ltime as $this->_vars->k=>$this->_vars->v ) {  ?>
			<div class="box-content">
				<div  class="content-item">
					<div class="yy_time"><?php echo $this->_vars->v ; ?></div>
					<div class="yy_cz">
						<?php if(!empty($this->_vars->schedule_order) && in_array($this->_vars->v, $this->_vars->schedule_order) ) {  ?>						
						<a href="javascript:void(0);" class="yy_false">已预约</a>
						<?php } elseif(intval($this->_vars->v) <= date('H') ) {  ?>
						<a href="javascript:void(0);" class="yy_false">已结束</a>
						<?php } elseif(!empty($this->_vars->schedule_time) && in_array($this->_vars->v, $this->_vars->schedule_time) ) {  ?>
						<a href="javascript:void(0);" time="<?php echo $this->_vars->k ; ?>" class="yy_true">可预约</a>
						<?php } else { ?>
						<a href="javascript:void(0);" class="yy_false">未开放</a>
						<?php } ?>
					</div>
				</div>	
			</div>
			<?php } ?>
		</div>
	</div>
	<!--课程结束-->
</div>
<?php $this->display('inc/footer.html', array (
)); ?>
