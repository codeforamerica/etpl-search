$(document).ready(function() {
	
	$(".suggestion-wrapper").on("click", function(e) {
		suggestion_label = $(this).children("label").html();
		search_terms = $(this).data("search-terms");
		
	    mixpanel.track("suggestion_clicked", {
	      label: suggestion_label,
	    });
		
        $('form').append('<input type="hidden" name="query" value="'+search_terms+'" id="query_from_suggestion"/>').submit();
	});

  $("input[type='checkbox']").on("touchstart click", function(e) {
    e.preventDefault();
    e.stopPropagation();

    var checkbox = $(this);
    var wrapper = checkbox.parent(".checkbox-wrapper");

    if(checkbox.attr("checked") === "checked") {
      wrapper.removeClass("checked");
      checkbox.removeAttr("checked").removeClass("checked");
    } else {
      wrapper.addClass("checked");
      checkbox.attr("checked", "checked").addClass("checked");
      track_filter_added(wrapper);
    }
  });

  $(".checkbox-wrapper").on("touchstart", function(e) {
    e.preventDefault();
    e.stopPropagation();
  }).on("click touchend", function(e) {
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
  });

  $("input[type='submit']").on("touchstart touchend", function(e) {
    $(this).addClass("active");
  }).on("click touchend", function(e) {
    $("#query_from_suggestion").remove();
    $("form").submit();
    $(this).removeClass("active");
    if((navigator.platform.indexOf("iPhone") !== -1)) {
      $(".submit-button-wrapper").removeClass("keyboard-active");
    }
  });

	$("input[type='text'], input[type='search']").focus(function() {
		if((navigator.platform.indexOf("iPhone") !== -1)) {
			$(".submit-button-wrapper").addClass("keyboard-active");
		}
	}).blur(function() {
		if((navigator.platform.indexOf("iPhone") !== -1)) {
			$(".submit-button-wrapper").removeClass("keyboard-active");
		}
	});
	
});

$(window).on("load", function() {
	if($(window).width() < 667) {
		set_scroll_container_width(".suggestion-wrapper", "#suggestions-container");
	}
});