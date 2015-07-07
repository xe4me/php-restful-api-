<?php

	error_reporting(E_ALL);
	ini_set('display_errors', 1);	


	$GLOBALS['start']=microtime(true);

	define('APP_NAME', 'chizio');
	
	date_default_timezone_set('UTC'); 
	define('CORE_DEBUG', false);
	define('DEBUG', true);
	define('DB_HOST', 'localhost:27017');
	define('DB_NAME',APP_NAME);
	define('SELF', getcwd());
	define('CONTROLLERS','Controllers');
	define('POPOS','Popo');
	define('ES_INDEX', APP_NAME);
	define('ES_SERVER', 'http://0.0.0.0:9200');
	define('API_URI', 'http://localhost/apache/chizio/api/v1/');	

	


	// Include all the dependencies

	// Include all the core files
	foreach (glob(SELF.'/Core/*.php') as $core_file ) {
		require_once $core_file;	
	}
	
	// include all the functions 
	foreach (glob(SELF.'/Functions/*.php') as $function_file ) {
		require_once $function_file;	
	}
	
	// include all the functions 
	foreach (glob(SELF.'/Consts/*.php') as $const_file ) {
		require $const_file;	
	}


	//require_once '../../helpers/currency.php';
	//require_once '../../helpers/price.php';




?>