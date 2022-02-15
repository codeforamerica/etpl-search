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
		var checkbox = $(this);
		var wrapper = checkbox.parent(".checkbox_wrapper");
		if(checkbox.attr("checked") === "checked") {
			wrapper.removeClass("checked");
			checkbox.removeAttr("checked").removeClass("checked");
		} else {
			wrapper.addClass("checked");
			checkbox.attr("checked", "checked").addClass("checked");
			track_filter_added(wrapper)
		}	
	});
	
	$(".checkbox-wrapper").on("click", function(e) {
		e.preventDefault();
		e.stopPropagation();
		var wrapper = $(this);
		var checkbox = wrapper.children("input[type='checkbox']");

		if(checkbox.attr("checked") === "checked") {
			wrapper.removeClass("checked");
			checkbox.removeAttr("checked");
		} else {
			wrapper.addClass("checked");
			checkbox.attr("checked", "checked");
			track_filter_added(wrapper);
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

$(window).on("load", function() {
	set_scroll_container_width(".checkbox-wrapper", "#checkboxes-container");
});