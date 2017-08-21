$(function () {
	//一级：全选、取消
	$('#addForm>div>input').click(function(){
		$(this).next('.table').find('input').prop('checked',this.checked);
	});
	
	//二级：
//	$('.priv>input').click(function(){
//		if(this.checked)
//			$(this).parent('div').prev('input').prop('checked',this.checked).parents('.table').prev('input').prop('checked',this.checked);
//	});
	//
	$('.table').on('click','input',function(){
		if($(this).is(':checked')) {
			$(this).parents('.table').prev('input').attr("checked", true);
		}
	});
})