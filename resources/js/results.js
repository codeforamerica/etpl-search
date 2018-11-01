function update_results(filter_options) {
	var input = $("#wrapper.results .header-wrapper form input");
	var query = input.val();
	filter_options = $("#wrapper.results .header-wrapper form").serialize();
	
	$.get("results.php?"+filter_options, function(results_data) {
		$("#results-wrapper").html(results_data);
	});
	
    window.history.pushState("", "", "results-wrapper.php?"+filter_options);
	
	$("#results-wrapper").scrollTop(0);
	$("body").scrollTop(0);
}

$(document).ready(function() { 
	
	var search_query = location.search.substring(1);
	if(search_query) {
		var json_search_query = JSON.parse('{"' + decodeURI(search_query).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}');
		
		var filter_options = json_search_query;
		$("input[type='checkbox']").each(function() {
			filter_options[$(this).attr("name")] = $(this).val();
		});
	}
	
	$("#results-wrapper").scrollTop(0);
	$("body").scrollTop(0);
	
	$("input[type='checkbox']").on("click", function(e) {
		e.preventDefault();
		e.stopPropagation();
		if($(this).attr("checked") == "checked") {
			$(this).parent(".checkbox-wrapper").removeClass("checked");
			$(this).removeAttr("checked").removeClass("checked");
		} else {
			$(this).parent(".checkbox-wrapper").addClass("checked");
			$(this).attr("checked", "checked").addClass("checked");
		}	
	});
	
	$(".checkbox-wrapper").on("click", function(e) {
		e.preventDefault();
		e.stopPropagation();
		if($(this).children("input[type='checkbox']").attr("checked") == "checked") {
			$(this).removeClass("checked");
			$(this).children("input[type='checkbox']").removeAttr("checked");
		} else {
			$(this).addClass("checked");
			$(this).children("input[type='checkbox']").attr("checked", "checked")
		}
		
		if($("#wrapper").hasClass("results")) {
			update_results(filter_options);
		}
    track_apply_filters();
	});
	
	$("#wrapper.results .header-wrapper form input").on("keyup click", update_results);
	
	$(".checkbox-wrapper").on("touchstart click", function() {
		$(this).children(".checkbox").addClass("animated");	
	});
}).on("click", ".program", function () {
	// $("body").scrollTop($(this).offset().top - $(".header-wrapper").height() - 20);
	// $(this).children(".debug").slideToggle();
});
