	<?php if(!empty($this->_vars->list) ) {  ?>
    <?php foreach($this->_vars->list as $this->_vars->key => $this->_vars->value ) {  ?>
	<div class="weui-form-preview__bd" onClick="javascript:window.location.href='<?php echo base_url() ; ?>sys/admin/detail/<?php echo $this->_vars->value['admin_id'] ; ?>'">
		<div class="weui-form-preview__item">
			<label class="weui-form-preview__label">角色</label> <span
				class="weui-form-preview__value"><?php echo $this->_vars->value['role_name'] ; ?></span>
		</div>
		<div class="weui-form-preview__item">
			<label class="weui-form-preview__label">用户名</label> <span
				class="weui-form-preview__value"><?php echo $this->_vars->value['uname'] ; ?></span>
		</div>
		<div class="weui-form-preview__item">
			<label class="weui-form-preview__label">真实姓名</label> <span
				class="weui-form-preview__value"><?php echo $this->_vars->value['name'] ; ?></span>
		</div>
		<div class="weui-form-preview__item">
			<label class="weui-form-preview__label">分店</label> <span
				class="weui-form-preview__value"><?php echo $this->_vars->value['store_name'] ; ?></span>
		</div>
		<div class="weui-form-preview__item">
			<label class="weui-form-preview__label">状态</label> <span
				class="weui-form-preview__value"><?php if($this->_vars->value['disabled']=='0' ) {  ?>启用<?php } else { ?>禁用<?php } ?></span>
		</div>
	</div>
	<div class="weui-form-preview__ft">
	</div>
	<?php } ?>
    <?php } ?>