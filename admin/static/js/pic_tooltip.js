
$(function(){
	var x = 10;
	var y = 20;
	$("a.tooltip_pic").mouseover(function(e){
		this.myTitle = this.title;
		this.title = "";	
		var imgTitle = this.myTitle? "<br/>" + this.myTitle : "";
		var tooltip_pic = "<div id='tooltip_pic'><img src='"+ this.href +"' alt='预览图'/>"+imgTitle+"<\/div>"; //创建 div 元素
		$("body").append(tooltip_pic);	//把它追加到文档中	
		var left = e.pageX+x ;	
		if(left > $(window).width()-650){
			left = $(window).width()-650;
		}				 
		$("#tooltip_pic")
			.css({
				"top": (e.pageY+y) + "px",
				"left":  left  + "px"
			}).show("fast");	  //设置x坐标和y坐标，并且显示
    }).mouseout(function(){
		this.title = this.myTitle;	
		$("#tooltip_pic").remove();	 //移除 
    }).mousemove(function(e){
    	var left = e.pageX+x ;	
		if(left > $(window).width()-650){
			left = $(window).width()-650;
		}
		$("#tooltip_pic")
			.css({
				"top": (e.pageY+y) + "px",
				"left":  left  + "px"
			});
	});
}) 