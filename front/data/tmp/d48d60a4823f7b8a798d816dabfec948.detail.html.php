<?php $this->display('inc/header.html', array (
)); ?>
<div class="container" id="container">
	<div class="b-card">
		<div class="card">
			<div class="headimg">
				<img src="<?php echo $this->_vars->headimgurl ; ?>" />
			</div>
			<div class="cardno">
				<div>NO：<?php echo $this->_vars->no ; ?></div>
				<div>余额：<?php echo $this->_vars->balance ; ?></div>
			</div>
			<div class="nickname">
				<div><?php echo $this->_vars->nickname ; ?></div>
			</div>
			<div class="open_card">
				<a href="<?php echo base_url() ; ?>card/open_member">
					<?php echo $this->_vars->is_open == '1' ? '充值' : '未开通' ; ?>
				</a>
			</div>
		</div>
	</div>	
	
	<div class="ncon">
		<div class="weui-cells">
			<?php if($this->_vars->has_file ) {  ?>
            <a class="weui-cell weui-cell_access" href="<?php echo base_url() ; ?>card/record">
                <div class="weui-cell__bd">
                    <p>会员评估档案表</p>
                </div>
                <div class="weui-cell__ft">
                </div>
            </a>
            <?php } ?>
			<a class="weui-cell weui-cell_access" href="<?php echo base_url() ; ?>card/user_info">
                <div class="weui-cell__bd">
                    <p>会员信息</p>
                </div>
                <div class="weui-cell__ft">
                </div>
            </a>
			<a class="weui-cell weui-cell_access" href="<?php echo base_url() ; ?>card/package">
                <div class="weui-cell__bd">
                    <p>套餐详情</p>
                </div>
                <div class="weui-cell__ft">
                </div>
            </a>
            <a class="weui-cell weui-cell_access" href="<?php echo base_url() ; ?>card/funds">
                <div class="weui-cell__bd">
                    <p>资金明细</p>
                </div>
                <div class="weui-cell__ft">
                </div>
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
