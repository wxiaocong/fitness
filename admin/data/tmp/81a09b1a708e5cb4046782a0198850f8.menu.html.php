<div id="menu-list"  class="weui-popup__container">
<div class="weui-popup__overlay"></div>
	<div class="weui-cells weui-popup__modal">
		<header class="demos-header">
			<h1 class="demos-title">
				<img src="<?php echo base_url() ; ?>static/images/logo.png"> <span>后台管理系统</span> <img
					id="closeMasterMenu" src="<?php echo base_url() ; ?>static/images/close.png">
			</h1>
		</header>		
		<?php foreach($this->_vars->menu as $this->_vars->val ) {  ?>
		<a class="weui-cell weui-cell_access" href="javascript:;">
			<div class="weui-cell__bd">
				<p><?php echo $this->_vars->val['menu_name'] ; ?></p>
			</div>
			<div class="weui-cell__ft"></div>
		</a> 
		<div class="child-menu">
			<?php if(isset($this->_vars->val['child']) ) {  ?>
			<?php foreach($this->_vars->val['child'] as $this->_vars->value ) {  ?>
			<div class="weui-flex">
		      <div class="weui-flex__item">
		      	<div class="placeholder">
		      		<a href="<?php echo base_url() ; ?><?php echo $this->_vars->value['ctrl'] ; ?>/<?php echo $this->_vars->value['act'] ; ?>"><?php echo $this->_vars->value['menu_name'] ; ?></a>
		      	</div>
		      </div>
		    </div>
			<?php } ?>
			<?php } ?>
		</div>
		<?php } ?>
	</div>
</div>