<input type="hidden" id="host" value="<?php echo base_url() ; ?>">
<script type="text/javascript" src="<?php echo base_url() ; ?>static/js/jquery.min.js"></script>
<?php if(isset($this->_vars->footerJs) && !empty($this->_vars->footerJs) ) {  ?>
<?php foreach($this->_vars->footerJs as $this->_vars->key => $this->_vars->value ) {  ?>
<script type="text/javascript" src="<?php echo base_url() ; ?>static/js/<?php echo $this->_vars->value ; ?>?v=2.3"></script>
<?php } ?>
<?php } ?>

<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<?php if(! empty($this->_vars->share) ) {  ?>
<script type="text/javascript">
	wx.config({
	    debug: false,
	    appId: "<?php echo $this->_vars->signPackage['appId'] ; ?>",
	    timestamp: <?php echo $this->_vars->signPackage['timestamp'] ; ?>,
	    nonceStr: "<?php echo $this->_vars->signPackage['nonceStr'] ; ?>",
	    signature: "<?php echo $this->_vars->signPackage['signature'] ; ?>",
	    jsApiList: [
			'checkJsApi',
			'onMenuShareTimeline',
			'onMenuShareAppMessage',
			'onMenuShareQQ',
			'onMenuShareWeibo',
			'onMenuShareQZone',
	    ]
	 });
	wx.ready(function () {
		wx.checkJsApi({
		    jsApiList: ['onMenuShareTimeline','onMenuShareAppMessage'],
		    success: function(res) {
		    }
		});
        	//分享到朋友圈
        	wx.onMenuShareTimeline({
        	    title: "<?php echo $this->_vars->share['title'] ; ?>", 
        	    link: "<?php echo base_url() ; ?><?php echo $this->_vars->uri_string ; ?>",
        	    imgUrl: "<?php echo $this->_vars->share['imgUrl'] ; ?>",
        	    success: function () { 
        	    },
        	    cancel: function () { 
        	    }
        	});
        	//发送给朋友
        	wx.onMenuShareAppMessage({
        	    title: "<?php echo $this->_vars->share['title'] ; ?>",
        	    desc: "<?php echo $this->_vars->share['desc'] ; ?>", 
        	    link: "<?php echo base_url() ; ?><?php echo $this->_vars->uri_string ; ?>",
        	    imgUrl:  "<?php echo $this->_vars->share['imgUrl'] ; ?>", 
        	    success: function () { 
        	    },
        	    cancel: function () { 
        	    }
        	});
        	//分享到QQ
        	wx.onMenuShareQQ({
        	    title: "<?php echo $this->_vars->share['title'] ; ?>", 
        	    desc: "<?php echo $this->_vars->share['desc'] ; ?>", 
        	    link: "<?php echo base_url() ; ?><?php echo $this->_vars->uri_string ; ?>",
        	    imgUrl:  "<?php echo $this->_vars->share['imgUrl'] ; ?>", 
        	    success: function () { 
        	    },
        	    cancel: function () { 
        	    }
        	});
        	//分享到微博
        	wx.onMenuShareWeibo({
        		title: "<?php echo $this->_vars->share['title'] ; ?>", 
        	    desc: "<?php echo $this->_vars->share['desc'] ; ?>", 
        	    link: "<?php echo base_url() ; ?><?php echo $this->_vars->uri_string ; ?>",
        	    imgUrl:  "<?php echo $this->_vars->share['imgUrl'] ; ?>", 
        	    success: function () { 
        	    },
        	    cancel: function () { 
        	    }
        	});
        	//分享到QQ空间
        	wx.onMenuShareQZone({
        		title: "<?php echo $this->_vars->share['title'] ; ?>", 
        	    desc: "<?php echo $this->_vars->share['desc'] ; ?>", 
        	    link: "<?php echo base_url() ; ?><?php echo $this->_vars->uri_string ; ?>",
        	    imgUrl:  "<?php echo $this->_vars->share['imgUrl'] ; ?>", 
        	    success: function () { 
        	    },
        	    cancel: function () { 
        	    }
        	});
	});
</script>	
<?php } ?>
</body>
</html>