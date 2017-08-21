<!DOCTYPE html>
<html>
<head>
<title>热炼健身</title>
<link rel="stylesheet" href="<?php echo base_url() ; ?>static/css/weui.css" />
<link rel="stylesheet" href="<?php echo base_url() ; ?>static/css/example.css" />
</head>
<body style="background:url(<?php echo base_url() ; ?>/static/image/load.png) no-repeat center center;margin: 0;padding: 0;min-height: 100vh;background-size: cover;">
	<div class="weui-loadmore" >
		<i class="weui-loading"></i>
	</div>
	
<script type="text/javascript" src="<?php echo base_url() ; ?>static/js/jquery.min.js"></script>	
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script type="text/javascript">
	wx.config({
	    debug: false,
	    appId: "<?php echo $this->_vars->signPackage['appId'] ; ?>",
	    timestamp: <?php echo $this->_vars->signPackage['timestamp'] ; ?>,
	    nonceStr: "<?php echo $this->_vars->signPackage['nonceStr'] ; ?>",
	    signature: "<?php echo $this->_vars->signPackage['signature'] ; ?>",
	    jsApiList: [
			'getLocation',
			'checkJsApi'
	    ]
	 });
	wx.ready(function () {
		wx.getLocation({
    		type: 'wgs84',
		    success: function (res) {
		    	if(!res.latitude){
		    		noLocal();
		    	}else{
			        var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
			        var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
			        $.ajax({
			        	type: "POST",
			        	url:"<?php echo base_url() ; ?>load/location",
			        	data:{latitude:latitude,longitude:longitude},
			        	async: false,
			        	success:function(){
			        		window.location.href="<?php echo $this->_vars->referer_url ; ?>";
			        	}
			        });
		    	}
		    },
		    cancel: function (res) {
		    	noLocal();
		    },
		    error: function (res) {
		    	noLocal();
		    }
		});
	});
	document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
	    WeixinJSBridge.call('hideOptionMenu');
	});
	
	//不定位
	function noLocal(){
		window.location.href="<?php echo $this->_vars->referer_url ; ?>";
	}
</script>

</body>
</html>
