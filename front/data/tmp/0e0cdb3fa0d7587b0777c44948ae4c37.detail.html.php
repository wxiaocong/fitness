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
		<!--教练-->
		<div class="panel course_detai_width">
			<div class="panel_jj">
				<div class="tec_name"><?php echo $this->_vars->coach_name ; ?></div>
				<div class="tec_profile"><?php echo $this->_vars->profile ; ?></div>
			</div>
			<div class="pic"><img src="<?php echo $this->_vars->pic_persion ; ?>" /></div>
		</div>
		<!--标签-->
		<div class="panel course_detai_width">
			<div class="panel_jg">
				<div class="course_price" style="font-size:4vw;">
					<img style="float:left;" src="<?php echo base_url() ; ?>static/image/course.png" /> 
					<span style="margin-top: 2px;display: inline-block;"><?php echo implode(' ',$this->_vars->tag) ; ?></span>
				</div>
			</div>
		</div>	
		<!--费用-->
		<div class="panel course_detai_width">
			<div class="panel_jg">
				<div class="course_price" style="font-size:4vw;">
					<img style="float:left;" src="<?php echo base_url() ; ?>static/image/money.png" /> 
					<span style="margin-top: 2px;display: inline-block;font-size:3vw;">￥<?php echo $this->_vars->price ; ?> 元 / 节</span>
				</div>
			</div>
		</div>
		<!--地址-->
		<div class="panel course_detai_width"  style="margin-top: 0.5vw">
			<div class="panel_jg">
				<div class="course_addr" onClick="javascript:window.location.href='<?php echo $this->_vars->addr_link ; ?>'">
					<div style="width: 90%;display: inline-block;font-size:3vw;"><?php echo $this->_vars->addr ; ?></div>
					<div style="float:left;"><img src="<?php echo base_url() ; ?>static/image/addr.png" /></div>
				</div>
			</div>
		</div>
		<!--简介-->
		<div class="panel course_detai_width">
			<div class="panel_jg">
				<div class="course_price" style="font-size:4vw;">
					<img style="float:left;" src="<?php echo base_url() ; ?>static/image/course.png" /> 
					<span style="margin-top: 2px;display: inline-block;">课程简介</span>
				</div>
			</div>
		</div>
		<!--简介-->
		<div class="panel course_detai_width"  style="margin-top:0.5vw">
			<div class="panel_jg">
				<div class="course_jj">
				<?php echo $this->_vars->introduce ; ?>
				</div>
			</div>
		</div>
		<!--注意事项-->
		<div class="panel course_detai_width">
			<div class="panel_jg">
				<div class="course_price" style="font-size:4vw;">
					<img style="float:left;" src="<?php echo base_url() ; ?>static/image/notice.png" /> 
					<span style="margin-top: 2px;display: inline-block;">注意事项</span>
				</div>
				<div class="course_jj">
					<?php echo $this->_vars->notice ; ?>
				</div>
			</div>
		</div>
	</div>
	<!--课程结束-->
</div>
<?php $this->display('inc/sharebar.html', array (
)); ?>
<?php $this->display('inc/footer.html', array (
)); ?>
