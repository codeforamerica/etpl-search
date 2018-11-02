$(document).ready(function() {

	if($(window).width() < 667) {
		var suggestion_list_width = 0;
	
		$(".suggestion-wrapper").each(function() {
			suggestion_list_width += $(this).outerWidth()+parseInt($(this).css("margin-right"));
			suggestion_list_width = Math.round(suggestion_list_width);
		});

		$("#suggestions-container").width(suggestion_list_width + (parseInt($("#suggestions-container").css("padding-left"))));
	}

	$(".suggestion-wrapper").on("click", function(e) {
		suggestion_label = $(this).children("label").html();
		search_terms = $(this).data("search-terms");
		
	    mixpanel.track("suggestion_clicked", {
	      label: suggestion_label,
	    });
		
        $('form').append('<input type="hidden" name="query" value="'+search_terms+'" />').submit();
	});
	
	$("input[type='checkbox']").on("touchstart click", function(e) {
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

	$(".checkbox-wrapper").on("touchstart", function(e) {
		e.preventDefault();
		e.stopPropagation();
	}).on("click touchend", function(e) {
		e.preventDefault();
		e.stopPropagation();

		if($(this).children("input[type='checkbox']").attr("checked") == "checked") {
			$(this).removeClass("checked");
			$(this).children("input[type='checkbox']").removeAttr("checked");
		} else {
			$(this).addClass("checked");
			$(this).children("input[type='checkbox']").attr("checked", "checked")
		}
	});

	$("input[type='submit']").on("touchstart touchend", function(e) {
		$(this).addClass("active");
	}).on("click touchend", function(e) {
		$("form").submit();
		$(this).removeClass("active");
		if((navigator.platform.indexOf("iPhone") != -1)) {
			$(".submit-button-wrapper").removeClass("keyboard-active");
		}
	});

	$("input[type='text'], input[type='search']").focus(function() {
		if((navigator.platform.indexOf("iPhone") != -1)) {
			$(".submit-button-wrapper").addClass("keyboard-active");
		}
	}).blur(function() {
		if((navigator.platform.indexOf("iPhone") != -1)) {
			$(".submit-button-wrapper").removeClass("keyboard-active");
		}
	});

});
