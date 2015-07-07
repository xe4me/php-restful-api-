<?php

class UsersNames extends Users{

	public function __construct($request, $response){
		
		$this->request = $request;
		$this->response = $response;

		$this->response = [
		"Milad",
		"Resza",
		"Ali",
		"Ghasem"
		];
		

	}

}


?>