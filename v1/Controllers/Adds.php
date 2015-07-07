<?php


/**
	* Class : Adds 
	* Refers to a collection called adds in the database
	* 
*/


class Adds{

	protected $request;
	public $response;



	public function __construct(){}


	public function mainRouter($request, $response){

		$this->request = $request;
		$this->response = $response;
		


		switch($this->request->request_type){

			case "GET":
				$this->response = $this->get();
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
			break;											

		}

	}







	/**
		* Method : get
		* Will get products from database , also there are some properties which could be used
		* like : sort , limit , skip , ...
	*/
	public function get(){


		$db = new MongoDAO($this->request->endpoint);
		$db = $db->c;


		// if there is an id specified , just search for that id and return the result
		if($this->request->id!=""){

			$result = $db->findOne(array('_id'=>new MongoId($this->request->id)));
			
			return $result;

		}else{

			$results = $db->find();

			$results = convert_to_array($results);

			return $results;
		}


	}








	/**
		* Method : delete
		* will delete a product with specified _id
	*/
	public function delete(){
		
		$db = new MongoDAO($this->request->endpoint);
		$db = $db->c;


		// Must specify an Id , else throw an Error
		if($this->request->id==""){
			new Error(2);			
		}



		$result = $db->remove(array('_id'=>new MongoId($this->request->id)));
		return $result ;

		

	}







	/**
		* Method : Create
		* Will create a new products based on the inpute comming from POST
	*/
	public function create(){

		$db = new MongoDAO($this->request->endpoint);
		$db = $db->c;

		$input = $this->request->input;

		if(empty($input)){
			new Error(2);
		}


		// we must insert in a try/catch , because if accidentally we specified an id in the product and
		// that id already exist , mongo will shoot a Fatal error that will destroy the page
		try{

			
			$input->add_time = time();
			$input->lock_status = "open";

			$input->specs = json_decode($input->specs);

			$result = $db->insert($input);


		}catch(MongoException $e){

			//$errorMessage =  $e->getMessage();
			//$errorCode =  $e->getCode();
			new Error(6);
		}

		
		return  array($input->_id->{'$id'});
	}	







	/**
		* Method : update
		* Will update a product by given id
	*/
	public function update(){

		$db = new MongoDAO($this->request->endpoint);
		$db = $db->c;

		$input = $this->request->input;	

		// Must specify an Id , else throw an Error
		if($this->request->id==""  || empty($input)){
			new Error(2);			
		}

		
		$result = $db->update(array('_id'=>new MongoId($this->request->id)),$input);
		return $result;



	}


}

?>
