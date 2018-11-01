$(document).ready(function() { 

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
    track_apply_filter($(this).parent(".checkbox-wrapper"));
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
    track_apply_filter($(this));
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
