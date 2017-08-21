<?php $this->display('inc/header.html', array (
)); ?>
<div class="container" id="container">	
	<div class="content">
		<div class="weui-confirm" style="font-size:16px;">
			会员信息
		</div>
	</div>	
	<div class="ncon">
		<div class="weui-cells">
			<a class="weui-cell" style="font-size:12px;">
                <div class="weui-cell__bd">
                    <p>身高（cm）</p>
                </div>
                <div class="weui-cell__ft"><?php echo $this->_vars->height ; ?></div>
            </a>
            <a class="weui-cell" style="font-size:12px;">
                <div class="weui-cell__bd">
                    <p>体重（kg）</p>
                </div>
                <div class="weui-cell__ft"><?php echo $this->_vars->weight ; ?></div>
            </a>
            <a class="weui-cell" style="font-size:12px;">
                <div class="weui-cell__bd">
                    <p>体脂百分比</p>
                </div>
                <div class="weui-cell__ft"><?php echo $this->_vars->fat ; ?></div>
            </a>
            <a class="weui-cell" style="font-size:12px;">
                <div class="weui-cell__bd">
                    <p>骨骼肌含量</p>
                </div>
                <div class="weui-cell__ft"><?php echo $this->_vars->bones ; ?></div>
            </a>
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