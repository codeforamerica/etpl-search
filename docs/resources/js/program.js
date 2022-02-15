$(document).ready(function() {

	$("p.truncated").click(function() {
		$(this).removeClass("truncated");
		$(".show-more#"+$(this).attr("id")).hide();
	});
	
	$(".show-more").click(function() {
		$(this).hide()
		$("p.truncated#"+$(this).attr("id")).removeClass("truncated");
	});
	
});