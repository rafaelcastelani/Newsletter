$(document).ready(function(){
	$('#start_date,#end_date').datetimepicker({	showSecond: true,
											dateFormat: 'yy-mm-dd',
											timeFormat: 'hh:mm:ss'});
	
	$("#is_magazine, #is_banner , #is_product ").bind("change",function(){
	    if(this.checked){
	        $("#url_nova_oferta,#url_em_andamento,#url_ultimos_dias,#type_code").attr("readonly","readonly");
	        $("#url_nova_oferta,#url_em_andamento,#url_ultimos_dias").val("--");
	        $("option[value=99006]").attr("selected","selected");
	    }else {
	        $("#url_nova_oferta,#url_em_andamento,#url_ultimos_dias,#type_code").removeAttr("readonly")
	        $("#url_nova_oferta,#url_em_andamento,#url_ultimos_dias").val("");
	        $("option[value=10000]").attr("selected","selected");
	    }
	});
	
});