<?php $this->display('inc/header.html', array (
)); ?>
<header class="demos-header">
	<h1 class="demos-title">
		<img src="<?php echo base_url() ; ?>static/images/logo.png"> <span>系统参数列表</span> 
		<img id="masterMenu" src="<?php echo base_url() ; ?>static/images/menu.png">
	</h1>
</header>
<?php $this->display('inc/menu.html', array (
)); ?>
<div class="weui-search-bar" id="searchBar">
  <form class="weui-search-bar__form">
    <div class="weui-search-bar__box">
      <i class="weui-icon-search"></i>
      <input type="search" class="weui-search-bar__input" id="searchInput" placeholder="搜索" required="">
      <a href="javascript:" class="weui-icon-clear" id="searchClear"></a>
    </div>
    <label class="weui-search-bar__label" id="searchText">
      <i class="weui-icon-search"></i>
      <span>搜索</span>
    </label>
  </form>
  <a href="javascript:" class="weui-search-bar__cancel-btn" id="searchCancel">取消</a>
  &nbsp;<img src="<?php echo base_url() ; ?>static/images/add.png" onclick="window.location.href='<?php echo base_url() ; ?>sys/param/detail/'">
</div>
<div class="weui-form-preview">
	<?php if(!empty($this->_vars->list['data']) ) {  ?>
    <?php foreach($this->_vars->list['data'] as $this->_vars->key => $this->_vars->value ) {  ?>
	<div class="weui-form-preview__bd">
		<div class="weui-form-preview__item">
			<label class="weui-form-preview__label">参数</label> <span
				class="weui-form-preview__value"><?php echo $this->_vars->value['s_key'] ; ?></span>
		</div>
		<div class="weui-form-preview__item">
			<label class="weui-form-preview__label">值</label> <span
				class="weui-form-preview__value"><?php echo $this->_vars->value['s_val'] ; ?></span>
		</div>
		<div class="weui-form-preview__item">
			<label class="weui-form-preview__label">参数含义</label> <span
				class="weui-form-preview__value"><?php echo $this->_vars->value['mome'] ; ?></span>
		</div>
	</div>
	<div class="weui-form-preview__ft">
		<a class="weui-form-preview__btn weui-form-preview__btn_primary"
			href="javascript:">操作</a>
	</div>
	<?php } ?>
    <?php } ?>
</div>
<?php $this->display('inc/footer.html', array (
)); ?>