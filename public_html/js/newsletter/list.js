$(document).ready(function() {
	$(".text").editInPlace({
		saving_animation_color: "#ECF2F8",
		url : '/newsletter/update/',
	});
	$(".date").editInPlace({
		saving_animation_color: "#ECF2F8",
		callback : function(field_id, new_value, old_value) {
			var result = old_value;
			var regexp = /^[\d]{4}-[\d]{2}-[\d]{2} [\d]{2}:[\d]{2}:[\d]{2}$/;
			if (regexp.test(new_value)) {
				$.post("/newsletter/update/", {
					'element_id' : field_id,
					'update_value' : new_value
				}, function(data) {
					result = data;
					$("#"+field_id).text(new_value);
				});
			}
			return result;
		}
	});
	
	$("td img").bind('click',function(){
		$( "#dialog-form" ).dialog( "open" );
	})
});




