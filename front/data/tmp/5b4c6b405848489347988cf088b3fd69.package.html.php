<?php $this->display('inc/header.html', array (
)); ?>
<div class="container" id="container">	
	<div class="content">
		<div class="weui-confirm" style="font-size:16px;">
			套餐信息
		</div>
	</div>	
	<div class="ncon">
		<div class="weui-cells">
			<?php if(! empty($this->_vars->list) ) {  ?>
			<?php foreach($this->_vars->list as $this->_vars->val ) {  ?>
			<a class="weui-cell weui-cell_access" href="<?php echo base_url() ; ?>card/package_info/<?php echo $this->_vars->val['package_id'] ; ?>"  style="font-size:12px;">
                <div class="weui-cell__bd">
                    <p><?php echo $this->_vars->val['course_name'] ; ?>(<?php echo $this->_vars->val['coach_name'] ; ?>)</p>
                </div>
                <div class="weui-cell__ft"><?php echo $this->_vars->val['num'] ; ?>次</div>
            </a>
            <?php } ?>
            <?php } else { ?>
            <div style="text-align:center;"><a href="<?php echo base_url() ; ?>card/open_member" style="color:#555;">暂无套餐,点击购买</a></div>
            <?php } ?>
            
        </div>
	</div>	
</div>

<script type="text/javascript">
document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
    WeixinJSBridge.call('hideOptionMenu');
});
</script>
<?php $this->display('inc/tabbar.html', array (
)); ?>
<?php $this->display('inc/footer.html', array (
)); ?>