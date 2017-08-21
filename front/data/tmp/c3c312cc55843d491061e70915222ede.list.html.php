<?php $this->display('inc/header.html', array (
)); ?>
<div class="container" id="container">
	<!--banner 开始-->
	<?php $this->display('inc/slide.html', array (
)); ?>
	<!--banner 结束-->
	<!--门店开始-->
	<div id="weui-tab">
		<?php foreach($this->_vars->list['data'] as $this->_vars->val ) {  ?>
		<div class="weui-tab_md">
			<a href="<?php echo base_url();?>store/detail/<?php echo $this->_vars->val['store_id'] ; ?>"><img alt="" src="<?php echo $this->_vars->val['img1'] ; ?>"></a>
		</div>
		<?php } ?>
		
		<div style="text-align: center;font-size: 12px;height: 50px;line-height: 40px;color: #999;">
			CUSTOMIZE ONE HOUR HEALTH
		</div>
	</div>
	<!--门店结束-->
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