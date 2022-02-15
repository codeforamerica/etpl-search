function preload_images(images) {
	$(images).each(function() {
		$("<img src='"+this+"'/>").appendTo("body").css("display", "none");
	});
}

function get_path_location(){
	// https://regex101.com/r/rWmuZ5/1
	return window.location.pathname.match(/views\/(.*)\.php/)[1];
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

function get_programpage_stats() {
	return {
		training_id: $('body').data('program-id'),
		card_rating: parseFloat($('.rating').text()),
		has_program_data: $('.data .no-data').length != 2,
	}
}

function get_active_filters(){
	var checked = $("#checkboxes-wrapper .checkbox-wrapper.checked");
	var labels = [];
	var columns = [];
	checked.each(function(){
		var label = $(this).find("label");
		labels.push(label.text());
		columns.push(label.attr("for"));
	});
	return labels;
}

function track_apply_filters(){
	mixpanel.track("apply_filter", {
		active_filters: get_active_filters(),
	});
}

function track_filter_added(checkbox_wrapper){
	var label = checkbox_wrapper.find("label");
	mixpanel.track("filter_select", {
		filter_id: label.attr("for"),
		filter: label.text(),
		filter_select_location: get_path_location(),
	});
}

function init_mixpanel_events() {
	// Page view events
	if(path_contains("search.php")) {
		mixpanel.track("homepage_view");
	} else if(path_contains("results-wrapper.php")) {
		mixpanel.track("searchpage_view", get_search_stats());
		track_apply_filters();
	} else if(path_contains("program.php")) {
		mixpanel.track("programpage_view", get_programpage_stats());
	}

	// UI action events.
	// ----------------

	// training_apply
	$(".contact-info").on("click", "a", function(){
		var button = $(this);
		mixpanel.track("training_apply", {
			call_to_action: button.text().toLowerCase(),
			training_id: $('body').data('program-id'),
			card_rating: parseFloat($('.rating').text()),
			has_program_data: $('.data .no-data').length != 2,
		});
	});

	// signup_clicked
	$("body").on("click", "a#research-prompt", function(){
		var position = $(this).data("research-prompt-position");
		mixpanel.track("signup_clicked", {
			location: get_path_location(),
			prompt_position: position === true ? 0 : position,
		})
	});

	// events triggered by adding filters are covered in search.js and results.js
}

function set_cookie(name, value, days) {
	var date, expires;
	if (days) {
		date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		expires = "; expires="+date.toGMTString();
	} else {
		expires = "";
	}
	document.cookie = name+"="+value+expires+"; path=/";
}

function set_scroll_container_width(item, container) {
	/*
	setTimeout(function() {
	var container_width = 0;

	$(item).each(function() {
	container_width += $(this).outerWidth()+parseInt($(this).css("margin-right"));
	container_width = Math.round(container_width);
	});

	container_width = container_width + (parseInt($(container).css("padding-left")));
	$(container).width(container_width);
	}, 10);
	*/
}

$(document).ready(function() {
	preload_images([
		"../resources/images/checkbox-filled.png",
	]);

	if($("input[type='search']").val() == "") {
		$("input[type='search']").focus();
	}

	if(typeof(mixpanel) == "undefined"){
		mixpanel = {
			track: function(name, data) {
				console.log("Fired '"+name+"' event with", data)
			}
		};
		console.log("initialized fake mixpanel");
	}
	
	init_mixpanel_events();

	$(".program.special .close").click(function(e) {
		$(this).parent(".program.special").hide();
		// if x is clicked, don't show prompt again for 3 days
		set_cookie("research-prompt-closed", 1, 3);
		e.preventDefault();
		e.stopPropagation();
	});

	$("#research-prompt").click(function() {
		$(this).hide();
		// if link is clicked, don't show prompt again for 7 days
		set_cookie("research-prompt-closed", 1, 7);
	});
});
