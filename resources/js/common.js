function preload_images(images) {
	$(images).each(function() {
		$("<img src='"+this+"'/>").appendTo("body").css("display", "none");
	});
}

$(document).ready(function() {
	
	preload_images([
		"../resources/images/checkbox-filled.png",
	]);
		
});