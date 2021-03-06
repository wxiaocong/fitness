<?php $this->display('inc/header.html', array (
)); ?>
<div class="container" id="container">	
	<div class="weui-cell" style="width: 92%;margin: 0 auto;font-size: 13px;">
        <div class="weui-cell__hd" style="line-height:45px;"><label for="" class="weui-label">日期:</label></div>
        <div class="weui-cell__bd">
            <input class="weui-input" id="fund-month" type="month" value="<?php echo date('Y-m') ; ?>" style="font-size: 13px;">
        </div>
    </div>
	
	<div class="ncon">
		<div class="weui-cells">
			<table class="funds_table">
			<thead>
			<tr>
				<th>时间</th>
				<th>类型</th>
				<th>收入(元)</th>
				<th>支出(元)</th>
				<th>余额(元)</th>
			</tr>
			</thead>
			<tbody  id="funds_table">
			
			</tbody>
            </table>
            
        </div>
        <div class="page__bd">
			<div class="weui-loadmore">
				<i class="weui-loading"></i>
				<span class="weui-loadmore__tips">正在加载</span>
			</div>
			<div class="weui-loadmore weui-loadmore_line">
				<span class="weui-loadmore__tips">没有更多数据</span>
			</div>
		</div>
	</div>
</div>
<?php $this->display('inc/tabbar.html', array (
)); ?>
<?php $this->display('inc/footer.html', array (
)); ?>
<script type="text/javascript">
document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
    WeixinJSBridge.call('hideOptionMenu');
});
/*初始化*/
var counter = 1; /*计数器*/
var isEnd = false;/*结束标志*/
var is_clear = false;//清除内容

/*首次加载*/
getData(counter,false);

/*监听加载更多*/  
$(window).scroll(function(){
    if(isEnd == true){
        return;
    }

    // 当滚动到最底部以上100像素时， 加载新内容
    if ($(document).height() - $(this).scrollTop() - $(this).height()<100){
    	$('.weui-loadmore').not('.weui-loadmore_line').show();
        counter ++;
        isEnd = true;
        getData(counter,false);
    }
});
function getData(page,is_clear){
	$.ajax({
		type:"POST",
		url:$('#host').val()+'card/get_funds_data',
		data:{page:page,date:$('#fund-month').val()},
		success: function(str){
			$('.weui-loadmore').not('.weui-loadmore_line').hide();
			if(str != ''){
				if(is_clear == true){
					$('#funds_table').empty();
				}
				$('#funds_table').append(str);
				isEnd = false;
			}else{
				$('.weui-loadmore_line').show();
			}
		}
	});	
}
$('.weui-input').change(function(){
	getData(1,true);
});
</script>