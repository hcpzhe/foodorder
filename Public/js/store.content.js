//Store module content page inculde this file
$(document).ready(function() {
	$(document).on("mousemove change",function(){
		$(parent.document).find("#content_frame").height($("body").height());
	});
	
	//RSsunmit 免跳转处理
	$(".bat_a_handle").click(function() {
		if ($(this).hasClass("RSconfirm")) {
			if(confirm("确定要清空数据吗？")) {
				var data = {success_alert:false,reload:true};
				if ($(this).hasClass("RSalert")) data.success_alert = true;
				$(this).RSsubmit(data);
			}
		}else {
			var data = {success_alert:false,reload:true};
			if ($(this).hasClass("RSalert")) data.success_alert = true;
			$(this).RSsubmit(data);
		}
		return false;
	});
});
