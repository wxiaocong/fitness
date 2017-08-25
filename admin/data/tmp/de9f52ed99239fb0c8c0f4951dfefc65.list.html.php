<?php $this->display('inc/header.html', array (
)); ?>
<header class="demos-header">
	<h1 class="demos-title">
		<a href="<?php echo base_url() ; ?>welcome"> 
			<img src="<?php echo base_url() ; ?>static/images/back.png"> <span>首页</span>
		</a> 
		<img id="masterMenu" src="<?php echo base_url() ; ?>static/images/menu.png">
		<p>系统参数列表</p>
	</h1>
</header>
<?php $this->display('inc/menu.html', array (
)); ?>
<div class="weui-search-bar" id="searchBar">
  <div class="weui-search-bar__form">
    <div class="weui-search-bar__box">
      <i class="weui-icon-search"></i>
      <input type="search" class="weui-search-bar__input" id="searchInput" onsearch="search()" placeholder="参数搜索" required="">
      <a href="javascript:" class="weui-icon-clear" id="searchClear"></a>
    </div>
    <label class="weui-search-bar__label" id="searchText">
      <i class="weui-icon-search"></i>
      <span>参数搜索</span>
    </label>
  </div>
  <a href="javascript:" class="weui-search-bar__cancel-btn" id="searchCancel">取消</a>
  &nbsp;<img src="<?php echo base_url() ; ?>static/images/add.png" onclick="window.location.href='<?php echo base_url() ; ?>sys/param/detail/'">
</div>
<div class="weui-form-preview"></div>
<div class="weui-loadmore">
  <i class="weui-loading"></i>
  <span class="weui-loadmore__tips">正在加载</span>
</div>
<?php $this->display('inc/footer.html', array (
)); ?>
<script>
var page = 1;
var loading = false;  //状态标记
getData(page);
function getData(pages){
	$.ajax({
		type:"POST",
		data:{search:$('#searchInput').val()},
		url:$('#host').val()+'sys/param/getData/'+pages,
		success:function(res){
			if(res.length > 10){
				$('.weui-form-preview').append(res);
				loading = false;
			}else{
				$('.weui-loadmore').html('没有更多了');
			}
		}
	});
}
function search(){
	$('.weui-form-preview').html('');
	getData(page);
}
$(document.body).infinite().on("infinite", function() {
  if(loading) return;
  loading = true;
  page = page + 1;
  getData(page);
});
</script>
