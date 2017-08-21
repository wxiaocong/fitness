<div class="weui-tabbar">
	<a href="<?php echo base_url() ; ?>store" class="weui-tabbar__item<?php echo $this->_vars->uri_string == '' || stristr($this->_vars->uri_string,'store') ? ' item_on' : '' ; ?>">
        <div class="weui-tabbar__label">门店</div>
    </a>
    <a href="<?php echo base_url() ; ?>course" class="weui-tabbar__item<?php echo stristr($this->_vars->uri_string,'course') ? ' item_on' : '' ; ?>">
        <div class="weui-tabbar__label">课程</div>
    </a>
    <a href="<?php echo base_url() ; ?>order" class="weui-tabbar__item<?php echo stristr($this->_vars->uri_string,'order') ? ' item_on' : '' ; ?>">
        <div class="weui-tabbar__label">日志</div>
    </a>
    <a href="<?php echo base_url() ; ?>card" class="weui-tabbar__item<?php echo stristr($this->_vars->uri_string,'card') ? ' item_on' : '' ; ?>">
        <div class="weui-tabbar__label">会员</div>
    </a>
</div>