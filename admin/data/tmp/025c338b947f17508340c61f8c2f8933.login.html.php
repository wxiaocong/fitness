<!DOCTYPE html>
<html>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>后台管理系统</title>
<meta name="description" content="">
<meta name="viewport" content="width=device-width">
<link rel="stylesheet" href="//cdn.bootcss.com/weui/1.1.1/style/weui.min.css">
<link rel="stylesheet" href="//cdn.bootcss.com/jquery-weui/1.0.1/css/jquery-weui.min.css">
<link rel="stylesheet" href="<?php echo base_url() ; ?>static/css/demo.css">
</head>

<body ontouchstart>
<header class="demos-header">
  <h1 class="demos-title">后台管理系统</h1>
</header>
<div class="weui-cells weui-cells_form">
  <div class="weui-cell">
    <div class="weui-cell__hd"><label class="weui-label">用户名</label></div>
    <div class="weui-cell__bd">
      <input class="weui-input" id="username" type="text" pattern=".{4,}" placeholder="请输入用户名">
    </div>
  </div>
  
  <div class="weui-cell">
    <div class="weui-cell__hd"><label class="weui-label">密码</label></div>
    <div class="weui-cell__bd">
      <input class="weui-input" id="pwd" type="password"   placeholder="请输入密码">
    </div>
  </div>  
  
  <div class="weui-cell weui-cell_vcode">
    <div class="weui-cell__hd"><label class="weui-label">验证码</label></div>
    <div class="weui-cell__bd">
      <input class="weui-input" id="code" type="number" placeholder="请输入验证码">
    </div>
    <div class="weui-cell__ft">
      	<img  class="weui-vcode-img" src="<?php echo base_url() ; ?>login/refresh_code" />
    </div>
  </div>
</div> 
<div class="weui-btn-area">
    <a class="weui-btn weui-btn_primary" href="javascript:" id="showTooltips">确定</a>
</div>
<script src="//cdn.bootcss.com/jquery/1.11.0/jquery.min.js"></script>
<script src="//cdn.bootcss.com/jquery-weui/1.0.1/js/jquery-weui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ; ?>static/js/md5.js"></script>
<script type="text/javascript">
	$('#showTooltips').click(function(){
		var username = $('#username').val();
		var pwd = $('#pwd').val();
		var code = $('#code').val();
		if(username.length < 4){
			$.toptip('用户名错误', 'error');
			return false;
		}
		if(pwd.length < 6){
			$.toptip('密码错误', 'error');
			return false;
		}
		if(code.length < 4){
			$.toptip('验证码错误', 'error');
			return false;
		}
		username = md5(username);
		pwd = md5(pwd);
		$.ajax({
			type:"POST",
			url:"<?php echo base_url() ; ?>login/toLogin",
			data:{username:username,pwd:pwd,code:code},
			success:function(res){
				if(res.status == '1'){
					$.toptip(res.msg, 'success');
					setTimeout(function(){
						window.location.href="<?php echo base_url() ; ?>welcome";
					},1000);
				}else{
					$.toptip(res.msg, 'error');
				}
			},
			dataType:"JSON"
		});
		return false;
	});
	
	$('.weui-vcode-img').click(function(){
		$(this).prop('src',"<?php echo base_url() ; ?>login/refresh_code?rand=" + Math.random());
	});
</script>
</body>
</html>
