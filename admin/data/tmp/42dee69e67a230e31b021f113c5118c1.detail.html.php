<?php $this->display('inc/header.html', array (
)); ?>
<header class="demos-header">
	<h1 class="demos-title">
		<a href="<?php echo base_url() ; ?>sys/param"> 
			<img src="<?php echo base_url() ; ?>static/images/back.png"> <span>返回</span>
		</a> 
		<img id="masterMenu" src="<?php echo base_url() ; ?>static/images/menu.png">
		<p>系统参数详情</p>
	</h1>
</header>
<?php $this->display('inc/menu.html', array (
)); ?>
<form id="dform">
<div class="weui-cells weui-cells_form">
	<div class="weui-cell">
		<div class="weui-cell__hd">
			<label class="weui-label">参数</label>
		</div>
		<div class="weui-cell__bd">
			<input class="weui-input" name="name" type="text" required
				value="<?php echo isset($this->_vars->result) ? $this->_vars->result['s_key'] : '' ; ?>"
				placeholder="参数名">
		</div>
	</div>

	<div class="weui-cells__title">参数值</div>
	<div class="weui-cells">
		<div class="weui-cell">
			<div class="weui-cell__bd">
				<input class="weui-input" name="value" type="text" value="<?php echo isset($this->_vars->result) ? $this->_vars->result['s_val'] : '' ; ?>" >
			</div>
		</div>
	</div>

	<div class="weui-cells__title">含义</div>
	<div class="weui-cells weui-cells_form">
		<div class="weui-cell">
			<div class="weui-cell__bd">
				<textarea class="weui-textarea" name="meaning" placeholder="含义"
					rows="3">
					<?php echo isset($this->_vars->result) ? $this->_vars->result['mome'] : '' ; ?>
				</textarea>
			</div>
		</div>
	</div>
	
	<div class="button_sp_area">
		<a href="javascript:;" class="weui-btn weui-btn_primary">保存</a>
        <?php if(isset($this->_vars->result) ) {  ?> 
        <a href="javascript:;" url="<?php echo base_url() ; ?>sys/param/del/<?php echo $this->_vars->result['id'] ; ?>"  onclick="return confirm('确认删除？');"
        class="weui-btn weui-btn_warn">删除</a>
        <?php } ?>
    </div>
</div>
</form>
<?php $this->display('inc/footer.html', array (
)); ?>
<script>

$('.weui-btn_primary').click(function(){
	var url = "<?php echo base_url() ; ?>sys/param/save/<?php echo isset($this->_vars->result) ? $this->_vars->result['id'] : '' ; ?>";
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