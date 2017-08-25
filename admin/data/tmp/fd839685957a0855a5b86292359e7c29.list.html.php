<?php $this->display('inc/header.html', array (
)); ?>
<header class="demos-header">
	<h1 class="demos-title">
		<a href="<?php echo base_url() ; ?>welcome"> 
			<img src="<?php echo base_url() ; ?>static/images/back.png"> <span>首页</span>
		</a> 
		<img id="masterMenu" src="<?php echo base_url() ; ?>static/images/menu.png">
		<p>用户管理</p>
	</h1>
</header>
<?php $this->display('inc/menu.html', array (
)); ?>
<div id="searchBar">
  <div class="weui-search-bar__form">
    <label class="weui-search-bar__label" id="searchText">
      <i class="weui-icon-search"></i>
      <span>搜索</span>
    </label>
  </div>
  &nbsp;<img src="<?php echo base_url() ; ?>static/images/add.png" onclick="window.location.href='<?php echo base_url() ; ?>sys/param/detail/'">
</div>
<div class="weui-form-preview"></div>
<div class="weui-loadmore">
  <i class="weui-loading"></i>
  <span class="weui-loadmore__tips">正在加载</span>
</div>
<?php $this->display('inc/footer.html', array (
)); ?>

<div class="weui-cells" id="searchContent" style="display:none;">
	<div class="weui-cell weui-cell_select weui-cell_select-after">
        <div class="weui-cell__hd">
          <label for="" class="weui-label">角色：</label>
        </div>
        <div class="weui-cell__bd">
          <select class="weui-select" id="role_id">
	    	<option value="0"></option>
		      <?php if(!empty($this->_vars->admin_role) ) {  ?>						          
			  <?php foreach($this->_vars->admin_role as $this->_vars->key=>$this->_vars->value ) {  ?>
			  <option value="<?php echo $this->_vars->key ; ?>"><?php echo $this->_vars->value['role_name'] ; ?></option>
			  <?php } ?>
			  <?php } ?>	
	    	</select>
        </div>
    </div>
	
	<div class="weui-cell weui-cell_select weui-cell_select-after">
        <div class="weui-cell__hd">
          <label for="" class="weui-label">分店：</label>
        </div>
        <div class="weui-cell__bd">
          <select class="weui-select" id="store_id">
	         <option value="0"></option>
	         <?php if(!empty($this->_vars->store_list) ) {  ?>						          
	  		  <?php foreach($this->_vars->store_list as $this->_vars->k=>$this->_vars->val ) {  ?>
	  		  <option value="<?php echo $this->_vars->k ; ?>"><?php echo $this->_vars->val ; ?></option>
	  		  <?php } ?>
	 		  <?php } ?>	
	       </select> 
        </div>
    </div>
    
    <div class="weui-cell">
        <div class="weui-cell__hd"><label class="weui-label">用户名：</label></div>
        <div class="weui-cell__bd">
          <input class="weui-input" id="uname" type="text">
        </div>
    </div>
    
</div>
<script>
var searchContent = $('#searchContent').html();
$('#searchContent').empty();
$('#searchText').click(function(){
	$.confirm({
		  title: '用户搜索',
		  text: searchContent,
		  onOK: function () {
			  	$('.weui-form-preview').html('');
				getData(page);
		  },
		  onCancel: function () {
		  }
		});
	return false;
});

var page = 1;
var loading = false;  //状态标记
getData(page);
function getData(pages){
	$.ajax({
		type:"POST",
		data:{role_id:$('#role_id').val(),store_id:$('#store_id').val(),uname:$('#uname').val()},
		url:$('#host').val()+'sys/admin/getData/'+pages,
		success:function(res){
			if(res.length > 10){
				$('.weui-form-preview').append(res);
				if(res.match(/weui-form-preview__bd/g).length < <?php echo $this->_vars->pageSize ; ?>){
					$('.weui-loadmore').html('没有更多了');
				}else{
					loading = false;
				}
			}else{
				$('.weui-loadmore').html('没有更多了');
			}
		}
	});
}
$(document.body).infinite().on("infinite", function() {
  if(loading) return;
  loading = true;
  page = page + 1;
  getData(page);
});
</script>


