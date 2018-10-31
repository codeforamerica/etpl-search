function preload_images(images) {
	$(images).each(function() {
		$("<img src='"+this+"'/>").appendTo("body").css("display", "none");
	});
}

function path_contains(string) {
	return window.location.pathname.indexOf(string) != -1;
}

function get_search_stats() {
	return {
		search_term: $('input[name="query"]').val(),
		search_results: $('.program').length,
	}
}

function init_mixpanel_events() {
	// Page view events
	path = window.location.pathname;
	if(path_contains("search.php")) {
		// mixpanel.track("homepage_view");
	} else if(path_contains("results-wrapper.php")) {
		mixpanel.track("searchpage_view", get_search_stats());
	} else if(path_contains("program.php")) {
		// mixpanel.track("programpage_view");
	}
}

$(document).ready(function() {
	
	preload_images([
		"../resources/images/checkbox-filled.png",
	]);
	
	if(typeof(mixpanel) == "undefined"){
		init_mixpanel_events();	
	}
		
});