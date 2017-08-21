<!DOCTYPE html>
<html>
<head>
<title>FUSION FITNESS</title>
</head>
<body style="margin:0;padding:0;">
<div class="container" id="container">
	<div id="red-card" style="background:url('<?php echo base_url() ; ?>static/image/exe-red-back.png')">
		<div style="margin: 0 auto;width: 695px;padding-top: 40px;padding-bottom: 60px;">
			<img alt="" src="<?php echo base_url() ; ?>static/image/exe-red.png">
		</div>
	</div>
	<div style="display:none;">
		<div style="border-bottom: 1px solid #999;">
			<a href="<?php echo base_url() ; ?>exercise/reg"><img alt="" src="<?php echo base_url() ; ?>static/image/red-24-co.png"></a>
		</div>
		<div>
			<a href="#"><img alt="" src="<?php echo base_url() ; ?>static/image/red-12-co.png"></a>
		</div>
	</div>
	
	<div id="black-card" style="background:url('<?php echo base_url() ; ?>static/image/exe-black-back.png')">
		<div style="margin: 0 auto;width: 695px;padding-top: 50px;padding-bottom: 50px;">
			<img alt="" src="<?php echo base_url() ; ?>static/image/exe-black.png">
		</div>
	</div>
	<div id="black-pack" style="display:none;">
		<img alt="" src="<?php echo base_url() ; ?>static/image/fusion-black.png">
	</div>
		
	<div style="border-bottom: 1px solid #999;">
		<a href="#"><img alt="" src="<?php echo base_url() ; ?>static/image/exe-80-co.png"></a>
	</div>
	<div>
		<a href="#"><img alt="" src="<?php echo base_url() ; ?>static/image/exe-35-co.png"></a>
	</div>
</div>		
	
	
<script type="text/javascript" src="<?php echo base_url() ; ?>static/js/jquery.min.js"></script>	
<script type="text/javascript">
	$('#red-card').click(function(){
		$(this).next('div').slideToggle();
	});
	$('#black-card').click(function(){
		$(this).next('div').slideToggle();
	});
</script>
</body>
</html>
