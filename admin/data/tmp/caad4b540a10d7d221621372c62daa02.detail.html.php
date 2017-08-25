<?php $this->display('inc/header.html', array (
)); ?>
<header class="demos-header">
	<h1 class="demos-title">
		<a href="<?php echo base_url() ; ?>sys/admin"> 
			<img src="<?php echo base_url() ; ?>static/images/back.png"> <span>返回</span>
		</a> 
		<img id="masterMenu" src="<?php echo base_url() ; ?>static/images/menu.png">
		<p>用户详情</p>
	</h1>
</header>
<?php $this->display('inc/menu.html', array (
)); ?>
<form id="dform">
<div class="weui-cells weui-cells_form">
	<div class="weui-cell weui-cell_select weui-cell_select-after">
        <div class="weui-cell__hd">
          <label for="" class="weui-label">角色</label>
        </div>
        <div class="weui-cell__bd">
          <select class="weui-select" id="role_id">
          	<option value="0"></option>
       		 <?php if(!empty($this->_vars->admin_role) ) {  ?>						          
             <?php foreach($this->_vars->admin_role as $this->_vars->key => $this->_vars->value ) {  ?>
             <option value="<?php echo $this->_vars->key ; ?>" <?php if(isset($this->_vars->result) && $this->_vars->key == $this->_vars->result['role_id'] ) {  ?>selected<?php } ?>><?php echo $this->_vars->value['role_name'] ; ?></option>
             <?php } ?>
             <?php } ?>	
          </select>
        </div>
     </div>
     
     <?php if(! isset($this->_vars->result) || $this->_vars->result['role_id'] > 1 ) {  ?>
     <div class="weui-cell weui-cell_select weui-cell_select-after" id="storeItem">
        <div class="weui-cell__hd">
          <label for="" class="weui-label">分店</label>
        </div>
        <div class="weui-cell__bd">
          <select class="weui-select" id="store_id">
          <option value="0"></option>
          <?php if(!empty($this->_vars->store_list) ) {  ?>						          
  		  <?php foreach($this->_vars->store_list as $this->_vars->k => $this->_vars->val ) {  ?>
  		  <option value="<?php echo $this->_vars->k ; ?>" <?php if(isset($this->_vars->result) && $this->_vars->k == $this->_vars->result['store_id'] ) {  ?>selected<?php } ?>><?php echo $this->_vars->val ; ?></option>
  		  <?php } ?>
  		  <?php } ?>	
          </select>
        </div>
     </div>
	 <?php } ?>

	<div class="weui-cell">
		<div class="weui-cell__hd">
			<label class="weui-label">用户名</label>
		</div>
		<div class="weui-cell__bd">
			<input class="weui-input" id="uname" type="text" 
				value="<?php echo isset($this->_vars->result) ? $this->_vars->result['uname'] : '' ; ?>">
		</div>
	</div>
	
	<div class="weui-cell">
		<div class="weui-cell__hd">
			<label class="weui-label">真实姓名</label>
		</div>
		<div class="weui-cell__bd">
			<input class="weui-input" id="name" type="text" 
				value="<?php echo isset($this->_vars->result) ? $this->_vars->result['name'] : '' ; ?>">
		</div>
	</div>
	
	<div class="weui-cell">
		<div class="weui-cell__hd">
			<label class="weui-label">密码</label>
		</div>
		<div class="weui-cell__bd">
			<input class="weui-input" id="passwd" type="password" >
		</div>
	</div>
	
	<div class="button_sp_area">
		<a href="javascript:;" class="weui-btn weui-btn_primary">保存</a>
        <?php if(isset($this->_vars->result) ) {  ?> 
        <a href="javascript:;" url="<?php echo base_url() ; ?>sys/admin/status/<?php echo $this->_vars->result['admin_id'] ; ?>/<?php echo $this->_vars->result['disabled'] ; ?>" 
        class="weui-btn weui-btn_warn"><?php if($this->_vars->result['disabled']=='0' ) {  ?>禁用<?php } else { ?>启用<?php } ?></a>
        <?php } ?>
    </div>
</div>
</form>
<?php $this->display('inc/footer.html', array (
)); ?>
<script>
$('#role_id').change(function(){
	if($(this).val() == 1){
		$('#storeItem').hide();
	}else{
		$('#storeItem').show();
	}
});
$('.weui-btn_primary').click(function(){
	var url = "<?php echo base_url() ; ?>sys/admin/save/<?php echo isset($this->_vars->result) ? $this->_vars->result['admin_id'] : '' ; ?>";
	$.ajax({
		type:"POST",
		data:$('#dform').serialize(),
		url:url,
		success:function(res){
			if(res > 0){
				$.toast("保存成功", function() {
					window.location.href="<?php echo base_url() ; ?>sys/param";
				});
			}else{
				$.toptip('保存失败', 'error');
			}
			
		}
	});
});

$('.weui-btn_warn').click(function(){
	var url = $(this).attr('url');
	$.confirm("确定删除该记录吗？", function() {
	  $.get(url,function(){
		  $.toast("删除成功", function() {
			  window.location.href="<?php echo base_url() ; ?>sys/param";
		  });
	  });
  	}, function() {
  
  	});
	return false;
});
</script>
