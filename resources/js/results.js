function update_results(options) {
	var input = $("#wrapper.results .header-wrapper form input");
	var query = input.val();
	options = $("#wrapper.results .header-wrapper form").serialize();
	
	$.get("results.php?"+options, function(results_data) {
		$("#results-wrapper").html(results_data);
	});
	
    window.history.pushState("", "", "results-wrapper.php?"+options);
	
	$("#results-wrapper").scrollTop(0);
	$("body").scrollTop(0);
}

$(document).ready(function() { 
	
	var search = location.search.substring(1);
	var json_search = JSON.parse('{"' + decodeURI(search).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}');
		
	var options = json_search;
	$("input[type='checkbox']").each(function() {
		options[$(this).attr("name")] = $(this).val();
	});
	
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
			update_results(options);
		}
	});
	
	$("#wrapper.results .header-wrapper form input").on("keyup click", update_results);
	
	$(".checkbox-wrapper").on("touchstart click", function() {
		$(this).children(".checkbox").addClass("animated");	
	});
}).on("click", ".program", function () {
	// $("body").scrollTop($(this).offset().top - $(".header-wrapper").height() - 20);
	$(this).children(".debug").slideToggle();
});