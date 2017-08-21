
    $(function(){
        $('.weui-tabbar__item').on('click', function () {
            $(this).addClass('weui-bar__item_on').siblings('.weui-bar__item_on').removeClass('weui-bar__item_on');
        });
        $('.weui-tab_md').each(function(i){
			$(this).click(function(){
				window.location.href="<?php echo base_url();?>Store/detail/"+i;
			});
        });
    });
    
    