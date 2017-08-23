<?php $this->display('inc/header.html', array (
)); ?>
<header class="demos-header">
	<h1 class="demos-title">
		<img src="./static/images/logo.png"> <span>后台管理系统</span> <img
			id="masterMenu" src="./static/images/menu.png">
	</h1>
</header>
<?php $this->display('inc/menu.html', array (
)); ?>
<div id="menu">
	<?php foreach($this->_vars->menu as $this->_vars->val ) {  ?>
	<div class="weui-cells">
		<a class="weui-cell weui-cell_access" href="javascript:;">
			<div class="weui-cell__hd">
				<img src="./static/images/menu/<?php echo $this->_vars->val['icon'] ; ?>.png" alt=""
					style="width: 20px; margin-right: 5px; display: block">
			</div>
			<div class="weui-cell__bd">
				<p>
					<?php echo $this->_vars->val['menu_name'] ; ?>
				</p>
			</div>
			<div class="weui-cell__ft"></div>
		</a>
	</div>

	<div class="weui-grids">
		<?php if(isset($this->_vars->val['child']) ) {  ?>
		<?php foreach($this->_vars->val['child'] as $this->_vars->value ) {  ?>
		<a
			href="<?php echo base_url() ; ?><?php echo $this->_vars->value['ctrl'] ; ?>/<?php echo $this->_vars->value['act'] ; ?>"
			class="weui-grid js_grid">
			<div class="weui-grid__icon">
				<img src="./static/images/menu/<?php echo $this->_vars->value['icon'] ; ?>.png" alt="">
			</div>
			<p class="weui-grid__label">
				<?php echo $this->_vars->value['menu_name'] ; ?>
			</p>
		</a>
		<?php } ?>
		<?php } ?>
	</div>
	<?php } ?>
</div>
<?php $this->display('inc/footer.html', array (
)); ?>