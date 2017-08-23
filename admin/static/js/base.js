$('#menu').on('click','.weui-cells',function(){
	$(this).siblings('.weui-grids').each(function(){
		if( $(this).is(":visible")){
			$(this).slideToggle();
		}
	});
	var nextGrid = $(this).next('.weui-grids');
	if(nextGrid.is(":visible")){
		nextGrid.slideUp();
	}else{
		nextGrid.slideDown();
	}
});

$('#masterMenu').click(function(){
	$('#menu-list').popup();
});
$('#closeMasterMenu').click(function(){
	 $.closePopup();
});

$('#menu-list').on('click','.weui-cell_access',function(){
	$(this).siblings('.child-menu').each(function(){
		if( $(this).is(":visible")){
			$(this).slideToggle();
		}
	});
	var nextChildMenu = $(this).next('.child-menu');
	if(nextChildMenu.is(":visible")){
		nextChildMenu.slideUp();
	}else{
		nextChildMenu.slideDown();
	}
});