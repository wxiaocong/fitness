<div id="w_banner">
		<script type="text/javascript"
			src="<?php echo base_url() ; ?>static/js/TouchSlide.1.1.js"></script>
			
		<div id="focus" class="focus">
			<div class="hd">
				<ul></ul>
			</div>
			<div class="bd">
				<ul>
					<?php if(! empty($this->_vars->activity) ) {  ?>
					<?php foreach($this->_vars->activity as $this->_vars->val ) {  ?>
						<li>
							<a href="<?php echo base_url();?>activity/index/<?php echo $this->_vars->val['activity_id'] ; ?>">
								<img _src="<?php echo $this->_vars->val['slide_img'] ; ?>" src="<?php echo base_url() ; ?>static/image/blank.png" />
							</a>
						</li>
					<?php } ?>	
					<?php } else { ?>
						<li>
							<a href="javascript:void(0);">
								<img _src="<?php echo base_url() ; ?>static/image/blank.png" src="<?php echo base_url() ; ?>static/image/blank.png" />
							</a>
						</li>
					<?php } ?>
				</ul>
			</div>
		</div>
		<script type="text/javascript">
			TouchSlide({ 
				slideCell:"#focus",
				titCell:".hd ul", //开启自动分页 autoPage:true ，此时设置 titCell 为导航元素包裹层
				mainCell:".bd ul", 
				effect:"left", 
				autoPlay:true,//自动播放
				autoPage:true, //自动分页
				switchLoad:"_src" //切换加载，真实图片路径为"_src" 
			});
		</script>	
</div>