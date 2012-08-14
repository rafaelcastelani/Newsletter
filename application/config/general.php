<?php
/* Define error level and debug stuff :) */
ini_set('error_reporting',E_ALL | E_STRICT);
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
ini_set('default_charset','UTF-8');
ini_set('include_path', 
		'.' . PATH_SEPARATOR . 
		'../application/models/'. PATH_SEPARATOR .
		'../../library/'. PATH_SEPARATOR .
		ini_get('include_path')
		);
setlocale (LC_ALL, "pt_BR");
date_default_timezone_set("America/Sao_Paulo");
?>