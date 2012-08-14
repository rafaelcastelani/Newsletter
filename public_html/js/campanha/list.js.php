var zimba = "";
$(document).ready(function() {
	$(".text").editInPlace({
		saving_animation_color: "#ECF2F8",
		url : '/campanha/update/',
		field_type: "textarea",
	});
	$(".date").editInPlace({
		saving_animation_color: "#ECF2F8",
		field_type: "textarea",
		callback : function(field_id, new_value, old_value) {
			var result = old_value;
			var regexp = /^[\d]{4}-[\d]{2}-[\d]{2} [\d]{2}:[\d]{2}:[\d]{2}$/;
			if (regexp.test(new_value)) {
				$.post("/campanha/update/", {
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
	
	$(".select").editInPlace({
		saving_animation_color: "#ECF2F8",
		url : '/campanha/update/',
		field_type: "select",
		select_options: "<?php
		foreach(explode("\n",file_get_contents("../../db/categorias.csv")) as $i=>$line){
				
					$line = explode(";",$line);
					if(trim($line[1]) == ""){
					}else {
						echo trim($line[0]).":".trim($line[1]).",";
					}				
				}
				 ?>"
	});
	$(".priority").editInPlace({
		saving_animation_color: "#ECF2F8",
		url : '/campanha/update/',
		field_type: "select",
		select_options: "<?php
					for($i = -15 ;$i<=15;$i++){
						echo $i.",";
					}				
				 ?>"
	});

	
	
	$("td img").bind('click',function(){
		$( "#dialog-form" ).dialog( "open" );
	})
	
	$("input[type=checkbox]").bind("change", function(){
		field_id = $(this).parent().attr("id");
		this.checked ? new_value = 1 : new_value = 0;
		$.post("/campanha/update/", {
			'element_id' : field_id,
			'update_value' : new_value
		});
	});
});
