<?php


class Connector 
{
	
	public $curl;
    public $api;   	
    public $response = NULL;
    public $uri =NULL;
    public $apiUri=NULL;


    public $searchUri = NULL;
    


	public function __construct($api) {
    	
        $this->apiUri=API_URI;
        $this->searchUri = ES_SERVER."/".ES_INDEX."/adds/";
        $this->setApi($api);

        
        if (!extension_loaded('curl')) {
            throw new ErrorException('no Curl library found');
        }

        $this->curl = curl_init();
        
        $this->setopt(CURLOPT_TIMEOUT, 200);
        $this->setopt(CURLOPT_FORBID_REUSE, 0);
        $this->setopt(CURLOPT_RETURNTRANSFER, 1);
        $this->setopt(CURLINFO_HEADER_OUT, TRUE);
        //$this->setopt(CURLOPT_HEADER, TRUE);
        $this->setopt(CURLOPT_SSL_VERIFYPEER, false);
    }





    public function get($params) {
        
        $this->setopt(CURLOPT_URL, $this->uri.$params);
        $this->setopt(CURLOPT_HTTPGET, TRUE);
        $this->_exec();
        return json_decode($this->response);
    }


     public function post_search($data="{}",$params="") {          
        $this->setopt(CURLOPT_URL, $this->uri.$params);
        $this->setopt(CURLOPT_CUSTOMREQUEST, 'POST');
        $this->setopt(CURLOPT_POSTFIELDS, $data);
        $this->_exec();
        return $this->response;

    }



	 public function post($data="{}",$params="") {          
        $this->setopt(CURLOPT_URL, $this->uri.$params);
        $this->setopt(CURLOPT_CUSTOMREQUEST, 'POST');
        $this->setopt(CURLOPT_POSTFIELDS, $data);
        $this->_exec();
        return json_decode($this->response, true);

    }




     public function put($data="{}",$params="") {

        
        $this->setopt(CURLOPT_URL, $this->uri.$params);
        $this->setopt(CURLOPT_CUSTOMREQUEST, 'PUT');
        $this->setopt(CURLOPT_POSTFIELDS, $data);
        $this->_exec();   
        return json_decode($this->response);

    }





    function setApi($api){
       $this->api = $api; 
       $api = $api."Uri"; 
       $this->uri = $this->$api;

    }


    

   

   

    

    function setOpt($option, $value) {
        return curl_setopt($this->curl, $option, $value);
    }

   

    function close() {
        curl_close($this->curl);
    }

  
  

    function _exec() {




        $this->response = curl_exec($this->curl);

        // Check if ElastiSearch is up adn running
        if($this->api=='search' && $this->response==''){


            // means elastic search hasn't return any response , so its down !
            new Error(9);
        }

    }

    function __destruct() {
        $this->close();
    }




  

}



?>