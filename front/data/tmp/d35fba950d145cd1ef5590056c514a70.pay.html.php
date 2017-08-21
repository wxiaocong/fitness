<div class="container" id="container">
	<div id="red-card">
		<div style="width: 80%;margin: 0 auto;margin-top: 10vh;background-color: #fff;height: 600px;text-align: center;">
			<img style="margin-top: 160px;" src="<?php echo base_url() ; ?>static/image/exe-ewm.png">
		</div>
	</div>
</div>		
<div id="kfp" style="bottom: 10px;position: absolute;font-size: 26px;color: #fff;margin-left: 20vw; letter-spacing: 6px;">
	<span>是否需要电子凭证？</span>
	<span id="invoice">
		<a href="javascript:;" data="1" style="color:#fff;margin-left: 50px;text-decoration: underline;">是</a>
		<a href="javascript:;" data="0" style="color:#fff;margin-left: 50px;text-decoration: underline;">否</a>
	</span>
</div>
<script src="//cdn.bootcss.com/jquery/1.11.0/jquery.min.js"></script>
<script type="text/javascript">
	$('invoice a').on('click','a',function(){
		var invoice = $(this).attr('data');
		$.ajax({
			type:"POST"
			url:"<?php echo base_url() ; ?>exercise/invoice",
			data:{invoice:invoice},
			success:function(){
				$('#kfp').hide();
			}
		});
	});
</script>