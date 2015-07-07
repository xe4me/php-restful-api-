<?php



/**
	* Class : Auth
	* Will use this class in authentication , either check a user or create a user  
*/
 class Auth
{
	
	public function __construct(){}




	/*
		* Method : authentify
		* will throw an error if user is not authenticated
	*/
	public static function authenticate($token){

		if (!self::isAuthenticated($token)){
			new Error(7);
		}
		
	}






	/*
		* Method : isAuthenticated
		* Will connect to database to check if there is actually a user with specified token
	*/
	public static function isAuthenticated($token){
		$db = new MongoDAO('users');
		$db = $db->c;
		

		// remove this line in later
		return true;


		$result = $db->findOne(array('token'=>$token));

		if($result==''){

			return false;

		}


		return true;
	}










	/*
		* Method : createUser
		* Will get an object as a user , for now we just get user and password
		* And will create a unique token for the use
	*/
	public static function createUser($user){
		$db = new MongoDAO('users');
		$db = $db->c;
		$user = array();
		
		// This will come from a form , but here I used just for test
		$user['username'] = "milad";

		$user['password'] = "private";



		$hash  = create_hash($user['username']);
		$user['password'] = md5(sha1($user['password']));
		$user['token'] = $hash['hash'];
		$user['salt'] = $hash['salt'];


		$res = $db->insert($user);
		pre($user);

	}


	


}

?>