<div class="weui-footer weui-footer_fixed-bottom">
  <p class="weui-footer__text">Copyright © 2017 小葱</p>
</div>
<input type="hidden" id="host" value="<?php echo base_url() ; ?>">
<script src="//cdn.bootcss.com/jquery/1.11.0/jquery.min.js"></script>
<script src="//cdn.bootcss.com/jquery-weui/1.0.1/js/jquery-weui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ; ?>static/js/base.js"></script>
<?php if(isset($this->_vars->footerJs) && !empty($this->_vars->footerJs) ) {  ?>
<?php foreach($this->_vars->footerJs as $this->_vars->key => $this->_vars->value ) {  ?>
<script type="text/javascript" src="<?php echo base_url() ; ?>static/js/<?php echo $this->_vars->value ; ?>?v=2.0"></script>
<?php } ?>
<?php } ?>
</body>
</html>