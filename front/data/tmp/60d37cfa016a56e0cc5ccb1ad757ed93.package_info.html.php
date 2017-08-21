<?php $this->display('inc/header.html', array (
)); ?>
<div class="container" id="container" style="background-color:#fff;">	
	<div class="content">
		<div class="weui-confirm" style="font-size:16px;">
			套餐消费记录
		</div>
	</div>	
	<div class="ncon">
		<div class="weui-cells">
			<?php if(! empty($this->_vars->list) ) {  ?>
			<?php foreach($this->_vars->list as $this->_vars->val ) {  ?>
			<a class="weui-cell weui-cell_access" href="<?php if($this->_vars->val['pay_type']=='1' ) {  ?><?php echo base_url() ; ?>order/detail/<?php echo $this->_vars->val['order_id'] ; ?><?php } else { ?>javascript:;<?php } ?>"  style="font-size:12px;">
                <div class="weui-cell__bd">
                    <p><?php echo $this->_vars->val['course_name'] ; ?>(<?php echo $this->_vars->val['coach_name'] ; ?>)</p>
                </div>
                <div class="weui-cell__ft">
                	<?php echo date('Y/m/d',strtotime($this->_vars->val['dateline'])) ; ?>
                	<?php echo isset($this->_vars->pay_type_cn[$this->_vars->val['pay_type']])?$this->_vars->pay_type_cn[$this->_vars->val['pay_type']]:'未知类型' ; ?>
                	<?php echo $this->_vars->val['pay'] ; ?>,
                	余<?php echo $this->_vars->val['balance_num'] ; ?>
                </div>
            </a>
            <?php } ?>
            <?php } else { ?>
            <div style="text-align:center;"><a href="<?php echo base_url() ; ?>card/open_member">暂无套餐消费</a></div>
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