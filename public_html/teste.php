<?php
header("content-type:text-plain;");
$string = explode("\n",file_get_contents("categorias.csv"));

foreach($string as $i=>$line){
	
	$line = explode(";",$line);
	if(trim($line[1]) == ""){
		if($i){
			echo "</optgroup>\n";
		}
		echo "<optgroup label=\"".trim($line[0])."\">\n";
	}else {
		echo "\t<option value=\"".trim($line[1])."\">".trim($line[0])."</option>\n";
	}
	
}
echo "</optgroup>";