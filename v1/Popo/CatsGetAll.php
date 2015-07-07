<?php

class CatsGetAll extends Cats{

	public function __construct($request, $response){

		$this->request = $request;
		$this->response = $response;


	    $db = new MongoDAO($this->request->endpoint);
		$db = $db->c;

		$result = $db->find();
		
		$this->response = convert_to_array($result);
		
	}

}


?>