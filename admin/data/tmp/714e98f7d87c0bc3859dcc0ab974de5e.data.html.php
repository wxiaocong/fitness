	<?php if(!empty($this->_vars->list) ) {  ?>
    <?php foreach($this->_vars->list as $this->_vars->key => $this->_vars->value ) {  ?>
	<div class="weui-form-preview__bd" onClick="javascript:window.location.href='<?php echo base_url() ; ?>sys/param/detail/<?php echo $this->_vars->value['id'] ; ?>'">
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
	</div>
	<?php } ?>
    <?php } ?>