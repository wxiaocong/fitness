 // toast
    $(function(){
        var toast = $('#toast');
        $('#showToast').on('click', function(){
            if (toast.css('display') != 'none') return;
            var tel = $('#tel').val();
            var order_id = $('#order_id').val();
            if(tel.length != 11) 
            { 
                $('.weui-toast__content').html('手机号格式错误');
                $('.weui-icon_toast').removeClass('weui-icon-success-no-circle').addClass('weui-icon-warn');
                toast.fadeIn(100);
                setTimeout(function () {
                    toast.fadeOut(100);
                }, 1000);
                return false; 
            } 
            $.post($('#host').val()+'/order/save_tel',{order_id:order_id,tel:tel},function(){
            	$('.weui-toast__content').html('提交成功');
            	 $('.weui-icon_toast').removeClass('weui-icon-warn').addClass('weui-icon-success-no-circle');
            	toast.fadeIn(100);
                setTimeout(function () {
                    toast.fadeOut(100);
                }, 2000);
            });
        });
    });
