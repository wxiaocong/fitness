<?php $this->display('inc/header.html', array (
)); ?>
<div class="container" id="container">
	<!--banner 开始-->
	<?php $this->display('inc/slide.html', array (
)); ?>
	<!--banner 结束-->
	<!--课程开始-->
	<div class="content">
		<div class="weui-navbar">
			<div class="weui-navbar__item"  onclick="$('.share_area').show().addClass('fadeIn');">
				<span><?php echo $this->_vars->store_info['name'] ; ?></span>
				<img class="unfold" src="<?php echo base_url() ; ?>static/image/unfold.png" />
			</div>
			<div class="weui-navbar__item" onclick="$('.share_course_item').show().addClass('fadeIn');">
				<span id="s_course">全部课程</span>
				<img class="unfold" src="<?php echo base_url() ; ?>static/image/unfold.png" />
			</div>
		</div>
		
		<div class="weui-navbar" style="height:8vw;">
			<div style="line-height:8vw;" class="course_type_item weui-navbar__item <?php echo $this->_vars->course_type == '2' ? 'on_course_type':'course_type' ; ?>" _url="<?php echo base_url() ; ?>course/index/2/<?php echo $this->_vars->store_info['store_id'] ; ?>">私教</div>
			<div style="line-height:8vw;" class="course_type_item weui-navbar__item <?php echo $this->_vars->course_type > '1' ? 'course_type':'on_course_type' ; ?>" _url="<?php echo base_url() ; ?>course/index/1/<?php echo $this->_vars->store_info['store_id'] ; ?>">团课</div>
			
			<div style="line-height:8vw;" class="course_type_item weui-navbar__item <?php echo $this->_vars->course_type == '3' ? 'on_course_type':'course_type' ; ?>" _url="<?php echo base_url() ; ?>course/index/3/<?php echo $this->_vars->store_info['store_id'] ; ?>">特色课</div>
		</div>
		<div id="leftTabBox" class="tabBox">
				<div class="bd">
						<ul>
							<?php if(! empty($this->_vars->coach) ) {  ?>
							<?php foreach($this->_vars->coach as $this->_vars->v ) {  ?>
							<li class="t" tag-id="<?php echo $this->_vars->v['tag_id'] ; ?>">
								<div class="pic"><img src="<?php echo $this->_vars->v['pic_persion'] ; ?>" /></div>
								<a class="yy" href="<?php echo base_url() ; ?>order/date/<?php echo $this->_vars->v['course_id'] ; ?>/<?php echo $this->_vars->v['coach_id'] ; ?>">预约</a>
								<div class="con" _url="<?php echo base_url() ; ?>course/detail/<?php echo $this->_vars->v['course_id'] ; ?>/<?php echo $this->_vars->v['coach_id'] ; ?>">
									<div class="tit">
									 	<?php echo $this->_vars->v['course_name'] ; ?> (<?php echo $this->_vars->v['coach_name'] ; ?>)
									 </div>
									<div class="ncon">
										<div>
											<span><?php echo $this->_vars->v['summary'] ; ?></span>
										</div>
										<div>￥<?php echo $this->_vars->v['price'] ; ?>元 / 次<?php if($this->_vars->course_type == '2' && $this->_vars->v['package_price'] > 0 && $this->_vars->v['package_num'] > 0 ) {  ?> OR ￥<?php echo $this->_vars->v['package_price'] ; ?>元  / <?php echo $this->_vars->v['package_num'] ; ?>次<?php } ?></div>
									</div>
								</div>
							</li>
							<?php } ?>
							<?php } ?>
						</ul>
				</div>
			</div>
 	</div>
	<!--课程结束-->
</div>
<div class="page-bd-15">
	<div class="weui-share share_area" onclick="$(this).fadeOut();$(this).removeClass('fadeOut')">
		<div id="course-area">
				<?php foreach($this->_vars->area as $this->_vars->key=>$this->_vars->value ) {  ?>
				<div class="item_title">
					<span><?php echo $this->_vars->value ; ?>市</span>
					<hr />
				</div>
				<?php foreach($this->_vars->store_list[$this->_vars->key] as $this->_vars->k=>$this->_vars->v ) {  ?>
				<?php if($this->_vars->k%2==0 ) {  ?><div><?php } ?>
				<span class="course_item" style="width:40%;" key="<?php echo $this->_vars->v['store_id'] ; ?>"><?php echo $this->_vars->v['name'] ; ?></span>
				<?php if($this->_vars->k%2==1 ) {  ?></div><?php } ?>
				<?php } ?>
				<?php } ?>
		</div>		
	</div>
</div>	
<div class="page-bd-15">
	<div class="weui-share share_course_item" onclick="$(this).fadeOut();$(this).removeClass('fadeOut')">
		<div id="course-item">
			<div class="item_title">
				<span>深圳全城-本周热门课程</span>
				<hr />
			</div>
			<div>
				<span class="course_item" key='0'>全部课程</span>
				<?php foreach($this->_vars->tag as $this->_vars->key=>$this->_vars->value ) {  ?>
				<?php if($this->_vars->key%3==2 ) {  ?><div><?php } ?>
				<span class="course_item" key="<?php echo $this->_vars->value['tag_id'] ; ?>"><?php echo $this->_vars->value['tag_name'] ; ?></span>
				<?php if($this->_vars->key%3==1 ) {  ?></div><?php } ?>
				<?php } ?>
				<?php if($this->_vars->key%3!=1 ) {  ?></div><?php } ?>
			</div>		
		</div> 
	</div>
</div>
<?php $this->display('inc/tabbar.html', array (
)); ?>
<?php $this->display('inc/footer.html', array (
)); ?>
