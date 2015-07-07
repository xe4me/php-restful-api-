<?php

class AddsGetNew extends Adds{


	public function __construct($request, $response){
		

		//sleep(2);
		$this->request = $request;
		$this->response = $response;


		$start = (int)$this->request->input->start;
		$last = $this->request->input->last;



		$db = new MongoDAO($this->request->endpoint);
		$db = $db->c;

		$result = $db->find(
			array(AdKeys::PUBLISH_TIME=>
				array(
					'$gte'=>$last)
				)
			)
		->limit(20)
		->sort(
			array(AdKeys::MID=>-1)
			);
		
		$this->response = convert_to_array($result);

		
	}

}




?>