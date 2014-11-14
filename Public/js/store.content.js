//Store module content page inculde this file
$(document).ready(function() {
	$(document).on("mousemove change",function(){
		$(parent.document).find("#content_frame").height($(document).height());
	})
});
