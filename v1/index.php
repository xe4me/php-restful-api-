<?php



	// Include the configuration :
	require_once 'Conf/config.php';
	

	
	/*print_r($_POST);
	print_r($_GET);
	prex(@file_get_contents('php://input'));*/
	//prex($_SERVER);
	

	try{

		new Api(get_request($_SERVER));

	}catch(Exception $e){

		new Error(1);

	}

?>