<?php

class MongoDAO{


	private 	$mongoEdnpoit = DB_HOST;
	public 		$dbname;
	public 		$db;
	public 		$collectionName;
	public 		$conn;
	public 		$c;
	private 	$mongo;



	

	public function __construct($collectionName){
		try {

			$collectionName = strtolower($collectionName);
			$this->conn = new MongoClient($this->mongoEdnpoit); // connect
			$this->db = $this->conn->selectDB(DB_NAME);
			$this->c = $this->db->$collectionName;

			

		} catch ( MongoConnectionException $e ) {

			echo $e;
	    	
	    	exit();
		}
	}

	
}
?>
