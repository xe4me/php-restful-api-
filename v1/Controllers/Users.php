

<?php


/**
	* Class 	: Users
	* Refers to a collection called users in the database
	* Errors 	: 300-399
*/


	class Users {

		protected $request;
		public $response;


		public function __construct() { }


		public function mainRouter($request, $response)
		{
			$this->request = $request;
			$this->response = $response;



			switch($this->request->request_type)
			{
				case "GET":
				$this->response = $this->userInfo();
				break;

				case "POST":
				$this->response = $this->create();
				break;

				case "DELETE":
				$this->response = $this->delete();
				break;

				case "PUT":
				$this->response = $this->update();
				break;			

				default:
				new Error(5);
			}

		}





	/**
		* Method : userInfo
		* Will get user infrmation from database by `id`
	*/
		public function userInfo()
		{
			$db = new MongoDAO($this->request->endpoint);
			$db = $db->c;



		// if there is an id specified , just search for that id and return the result
			if ($this->request->id != "")
			{
				$result = $db->findOne(
					array('_id' => new MongoId($this->request->id)),
					array(
						'security' => FALSE
						)
					);

				return $result;
			}
			else
			{
				return array();
			}

		}










	/**
		* Method : Create
		* Will create a new user based on the input comming from POST
	*/
		public function create()
		{
			$db = new MongoDAO($this->request->endpoint);
			$db = $db->c;


			$user_object = new stdClass();
		// we must insert in a try/catch , because if accidentally we specified an id in the product and
		// that id already exist , mongo will shoot a Fatal error that will destroy the page
			try
			{

				$result = $db->insert($user_object);
			}
			catch(MongoException $e)
			{

				new Error(6);
			}

			return array($user_object->_id->{'$id'});

		}







	/**
		* Method : checkEmailExist
		* Will get an email and search's into the db to find if there is such an email or not 
		* This will asure us that user hasn't been signed up before
	*/
		public function checkEmailExist($email){
			$db = new MongoDAO($this->request->endpoint);
			$db = $db->c;

		$result = $db->findOne( // I changed count to findOne , because count is gonna count all the db to do the query
			array('email' => $email)
			);

		if (count($result) >= 1)
		{
			return TRUE;
		}
		
		return FALSE;
	}
		/**
			* Method : checkEmailExist
			* Will get an email and search's into the db to find if there is such an email or not 
			* This will asure us that user hasn't been signed up before
		*/
		public function checkTellExists($tell){

			try{

				$db = new MongoDAO($this->request->endpoint);
				$db = $db->c;
				$result = $db->findOne( 
					array('personal.tell' => $tell)
					);

				if (count($result) >= 1){

					return TRUE;
				}

			}catch(MongoException $e){
				new Error(13);
			}

			return FALSE;
		}










	/**
		* Method : delete
		* will delete a user with specified _id
	*/
		public function delete()
		{		
			$db = new MongoDAO($this->request->endpoint);
			$db = $db->c;

		// Must specify an Id , else throw an Error
			if($this->request->id == "")
			{
				new Error(2);			
			}


			try {

				$result = $db->remove(
					array('_id' => new MongoId($this->request->id))
					);


			} catch (MongoException $e) {

				new Error(101);		

			}



			return array() ;
		}










	/**
		* Method : update
		* Will edit "Personal Account Info"
	*/
		public function update()
		{

			if ($this->request->request_type != "PUT"){

				new Error(5);
			}


			$input = $this->request->input;

			if (empty($input)){

				new Error(2);			
			}





			$db = new MongoDAO($this->request->endpoint);
			$db = $db->c;



			if (empty($input->uid))
			{
				new Error(12);
			}

			$uid = $input->uid;
			$user_info = $db->findOne(
				array('_id' => new MongoId($uid))
				);




		//$update_input = new stdClass();
			$update_input = (object) $user_info;
			unset($update_input->_id);

			$changed = FALSE;



			if (empty($input->first_name))
			{
				new Error(22);
			}

			if ($user_info['first_name'] != $input->first_name)
			{
				$update_input->first_name = $input->first_name;
				$changed= TRUE;
			}


			if (empty($input->last_name))
			{
				new Error(23);
			}

			if ($user_info['last_name'] != $input->last_name)
			{
				$update_input->last_name = $input->last_name;
				$changed= TRUE;
			}



			if ( ! empty($input->new_email))
			{
				if (empty($input->current_email))
				{
					new Error(19);
				}

				if ($user_info['email'] != $input->current_email)
				{
					new Error(19);
				}

				if (empty($input->confirm_new_email))
				{
					new Error(25);
				}

				if ($input->new_email != $input->confirm_new_email)
				{
					new Error(16);
				}

				if ($input->new_email != $input->current_email)
				{
					$update_input->email = $input->new_email;
					$changed= TRUE;

					if ( ! $this->isValidEmail(($input->new_email)))
					{
						new Error(19);
					}

					if ($this->checkEmailExist($input->new_email))
					{
						new Error(15);
					}
				}

			}



			if ( ! empty($input->new_password))
			{
				if (empty($input->current_password))
				{
					new Error(21);
				}

				$hash_current_password = $this->hashPassword($input->current_password, $user_info['security']['salt']);
				if ($user_info['security']['password'] != $hash_current_password)
				{
					new Error(21);
				}

				if (empty($input->confirm_new_password))
				{
					new Error(26);
				}

				if ($input->new_password != $input->confirm_new_password)
				{
					new Error(16);
				}

				if ($input->new_password != $input->current_password)
				{
					$update_input->security = new stdClass();
					$changed= TRUE;

					$update_input->security->salt = $this->makeSalt();
					$update_input->security->password = $this->hashPassword($input->new_password, $update_input->security->salt);
				}
			}


			if ($changed)
			{
			//$update_input = (object) array_merge((array) $user_info, (array) $update_input);
				$db->update(
					array('_id' => new MongoId($uid)), $update_input
					);
				return TRUE;
			}
			else
			{
				return FALSE;
			}

		}
















		public function makeSalt()
		{
		// A higher "cost" is more secure but consumes more processing power
			$cost = 10;

		// Create a random salt
			$salt = substr(str_replace('/', '', str_replace('+', '.', base64_encode(md5(mt_rand() . microtime(), true)))), 0, 32);

		// "$2a$" Means we're using the Blowfish algorithm. The following two digits are the cost parameter.
			$salt = sprintf("$2a$%02d$", $cost) . $salt;

			return $salt;
		}







		public function hashPassword($password, $salt)
		{
			$password = md5(md5($password) . SHA1($salt));
			return $password;
		}







		public function isValidEmail($email_address)
		{
			if ( ! empty($email_address))
				if ( ! preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{1,6}$/i', $email_address))
				{
					return FALSE;
				}

				return TRUE;
			}


		}

		?>
