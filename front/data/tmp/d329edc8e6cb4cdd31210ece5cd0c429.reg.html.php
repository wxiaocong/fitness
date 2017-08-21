<!DOCTYPE html>
<html>
<head>
<title>FUSION FITNESS</title>
<meta name="viewport" content="user-scalable=no" />
<link rel="stylesheet" href="//cdn.bootcss.com/weui/1.1.1/style/weui.min.css">
<link rel="stylesheet" href="//cdn.bootcss.com/jquery-weui/1.0.1/css/jquery-weui.min.css">
</head>
<body style="margin:0;padding:0;background-color: #f2f2f2;">
<style type="text/css">
.reg-card{
    height: 100px;
    padding-left: 80px;
    box-shadow: 0px 10px 10px #ddd;	
    margin-top: 80px;
}
.reg-card img{
    height: 26px;
    float: left;	
}
.reg-card input{
	height: 50px;
    line-height: 50px;
    border: none;
    width: 65%;
    float: left;
    font-size: 34px;
    background-color: #f2f2f2;
    margin-left: 30px;	
    top: -10px;
    position: relative;
}
.weui-toptips{
	font-size:26px;
}
</style>
<div class="container" id="container">
	<div style="text-align: center;height: 300px;">
		<img style="margin-top:100px;" src="<?php echo base_url() ; ?>static/image/fusion-logo.png">
	</div>
	<div style="padding-top: 100px;">
		<div class="reg-card">
			<img alt="" src="<?php echo base_url() ; ?>static/image/reg-name.png">
			<input type="text" id="realname" name="realname" value="">
		</div>
		
		<div class="reg-card">
			<img alt="" src="<?php echo base_url() ; ?>static/image/reg-phone.png">
			<input type="tel" id="phone" name="phone" value="">
		</div>
		
		<div class="reg-card">
			<img alt="" src="<?php echo base_url() ; ?>static/image/reg-email.png">
			<input type="text" id="email" name="email" value="">
		</div>
		
		<div class="reg-card">
			<img alt="" src="<?php echo base_url() ; ?>static/image/reg-number.png">
			<input type="text" value="<?php echo $this->_vars->no ; ?>" disabled="disabled">
		</div>
	</div>
	<div style="margin-top: 100px;text-align: center;">
		<img id="subImg" src="<?php echo base_url() ; ?>static/image/reg.png">
	</div>
</div>		
	
	
<script src="//cdn.bootcss.com/jquery/1.11.0/jquery.min.js"></script>
<script src="//cdn.bootcss.com/jquery-weui/1.0.1/js/jquery-weui.min.js"></script>
<script type="text/javascript">
	$('#subImg').click(function(){
		var realname = $('#realname').val();
		var phone = $('#phone').val();
		var email = $('#email').val();
		var type = "<?php echo $this->_vars->type ; ?>";
		var pattern = /^1[34578]\d{9}$/;   
		
		if(realname.length < 2){
			$.toptip('NAME必填','error');
			return false;
		}
		if( ! pattern.test(phone)){
			$.toptip('PHONE格式错误', 'error');
			return false;
		} 
		$.ajax({
			type:"POST",
			url:"<?php echo base_url() ; ?>exercise/save",
			data:{realname:realname,phone:phone,email:email,type:type},
			success:function(res){
				if(res.status == '1'){
					$.toptip('提交成功', 1000, 'success');
					setTimeout(function(){
						//window.location.href = "<?php echo base_url() ; ?>exercise/pay"
						$.get("<?php echo base_url() ; ?>exercise/pay",function(cont){
							$('body').css('background-color','#E9351E').html(cont);
						});
					},1000);
				}else{
					$.toast(res.msg, "cancel");
				}
			},
			dataType:"JSON"
		});
	});
</script>
</body>
</html>
