<?php

class UsersLogin extends Users{

	public function __construct($request, $response){

		$this->request = $request;
		$this->response = $response;



		$user_tell 	= 	@$this->request->input->tell;
		$password 	= 	@$this->request->input->password;

		if(empty($user_tell) || empty($password)){
			new Error(200);// tell or pass is empty
		}

		try{

			$db = new MongoDAO($this->request->endpoint);
			$db = $db->c;
			
			$user_result = $db->findOne(array('personal.tell' => $user_tell));

			if ( ! empty($user_result)){
				$calculatedPassword = $this->hashPassword($password, $user_result['security']['salt']);
				
				if ($user_result['security']['password'] == $calculatedPassword)
				{				
					unset($user_result['security']);
					
					$user_result['uid'] = $user_result['_id']->{'$id'};

					unset($user_result['_id']);

					$this->response = $user_result;
				}
				else
				{
					new Error(204);
				}
			}
			else{
				new Error(203);

			}			

		}catch(MongoException $e){
			new Error(13);
		}

	}

}


?>