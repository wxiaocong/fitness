<?php $this->display('inc/header.html', array (
)); ?>
<div class="container" id="container">
	<div>
		<img style="height: 49vw;width:100%;" src="<?php echo $this->_vars->img1 ; ?>">
	</div>
	<!--课程开始-->
	<div class="content">
		<!--课程-->
		<div class="panel">
			<div class="panel_jg" style="width:100%;">
				<div class="course_price" style="background:#f2f2f2 url('<?php echo base_url() ; ?>static/image/storeTag.png');background-size: 100%;">
					<div style="width: 94%;margin: 0 auto;color: #fff;">健身课程</div>
				</div>
				<div class="course_jj" style="margin: 0 auto;width: 94%">
					<?php if(! empty($this->_vars->course_list) ) {  ?>
					<table style="width:100%;">
					<?php foreach($this->_vars->course_list as $this->_vars->key=>$this->_vars->val ) {  ?>
					<?php if($this->_vars->key%3==0 ) {  ?><tr><?php } ?>
						<td><?php echo $this->_vars->val ; ?></td>
					<?php if($this->_vars->key%3==2 ) {  ?></tr><?php } ?>
					<?php } ?>
					<?php if($this->_vars->key%3!=2 ) {  ?></tr><?php } ?>
					</table>
					<?php } ?>
				</div>
			</div>
		</div>
		<!--简介-->
		<div class="panel">
			<div class="panel_jg">
				<div class="course_price" style="font-size:3.97vw;">
					门店简介
				</div>
				<div class="course_jj">
					<?php echo $this->_vars->content ; ?>
				</div>
			</div>
		</div>
		<!--地址-->
		<div class="panel">
			<div class="panel_jg">
				<div class="course_price" style="font-size:3.97vw;">
					门店地址
				</div>
				<p class="course_addr">
					<?php echo $this->_vars->addr ; ?>
					<a href="<?php echo $this->_vars->addr_link ; ?>">
						查看地图<img style="float:right;height: 3vw; margin:0 1vw;" src="<?php echo base_url() ; ?>static/image/location.png" />
					</a>
				</p>
			</div>
		</div>
		<!--注意事项-->
		<div class="panel">
			<div class="panel_jg">
				<div class="course_price" style="font-size:3.97vw;">
					注意事项
				</div>
				<div class="course_jj">
					<?php echo $this->_vars->notice ; ?>
				</div>
			</div>
		</div>
		<div class="weui-btn_yuyue">
			<a href="<?php echo base_url() ; ?>course/index/2/<?php echo $this->_vars->store_id ; ?>">预约私教</a>
		</div>
	</div>
	<!--课程结束-->
</div>
<?php $this->display('inc/tabbar.html', array (
)); ?>
<?php $this->display('inc/footer.html', array (
)); ?>