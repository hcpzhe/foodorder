//Store module content page inculde this file
var org_height , new_height;
$(document).ready(function() {
	$(document).on("mousemove change",function(){
		$(parent.document).find("#content_frame").height($("body").height());
	});

	$('.modal').on('shown.bs.modal', function (e) {
		org_height = document.body.scrollHeight;
		new_height = $('.modal-backdrop').height();
		if (org_height < new_height) {
			$("body").height(new_height);
			$(parent.document).find("#content_frame").height(new_height);
		}
	});
	$('.modal').on('hidden.bs.modal', function (e) {
		if (org_height < new_height) {
			$("body").height(org_height);
			$(parent.document).find("#content_frame").height(org_height);
		}
	});
	
	//RSsunmit 免跳转处理
	$(".bat_a_handle").click(function() {
		if ($(this).hasClass("RSconfirm")) {
			if(confirm("确定要进行此项操作吗？")) {
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
