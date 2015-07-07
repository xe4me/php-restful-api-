<?php

class UsersRegister extends Users{

	public function __construct($request, $response){

		$this->request = $request;
		$this->response = $response;
		$input = $this->request->input;

		if (empty($input->tell) || empty($input->password) || empty($input->confirm_password) || empty($input->fullname) || empty($input->email)){
			new Error(200);
		}


		$email = $input->email;
		$fullname = $input->fullname;
		$tell = $input->tell;
		$password = $input->password;
		$confirm_password = $input->confirm_password;

		
		if ($password != $confirm_password)
		{
			new Error(201); 
		}
		
		
		
		if ($this->checkTellExists($tell))
		{
			new Error(202); // tell user , this tell exists
		}
		


		$input->security = new stdClass();
		$input->personal = new stdClass();
		$input->personal->email = $email;
		$input->personal->fullname = $fullname;
		$input->personal->tell = $tell;

		$input->security->salt = $this->makeSalt();
		$input->security->password = $this->hashPassword($input->password, $input->security->salt);
		
		unset($input->password);
		unset($input->tell);
		unset($input->fullname);
		unset($input->email);
		unset($input->confirm_password);
		
		try{

			$db = new MongoDAO($this->request->endpoint);
			$db = $db->c;


			$db->insert($input);

			unset($input->security);

			$input->uid = $input->_id->{'$id'};

			unset($input->_id);

			$this->response = $input->uid;

		}catch(MongoException $e){
			new Error(13);
		}
		
	}

}


?>