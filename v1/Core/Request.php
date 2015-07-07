<?php


/**
    * Class : Request
    *  Will digest the base request that comes and returns an array which contains all the different 
    * elements and arguments and paramas that later will be needed 
*/

class Request{
	
	public $endpoint; // Controller name 
	public $popo; // This is the action OR method OR verb which can be empty 
	public $args; // All the url parts exploded by " / "
	public $params; // Any special parameter that we find which will come by post  or ...
	public $id; // This is a mongo id of each product
	public $token; // The client token for authentication
	public $request_type;  // Can be POST  / DELETE / PUT / GET
	public $input; // Any input that comes with our POST / PUT request 
	public $protocol; // protocol name can be HTTP or HTTPS 
    

	public function __construct($request){


    

        // Get the token from $request which has been set in the headers
        $this->token = $request['token'];

      

        // Get the args which is simply the url without domain and ...
        $args = $request['args'];



        // This will check if user is authenticated, if not Auth will throw a Error(7) and kills the page
        Auth::authenticate($this->token);



        // Get the all arguments from url 
		$this->args = explode('/', rtrim($args , '/'));	
		

        // Get the Controller name 
        $this->endpoint = ucfirst($this->args[0]); // always the first one is our endpoint , E.g : api/v1/ -> products  

        

        // Do a loop on all arguments to find ids and popo names 
		foreach ($this->args as $arg){

            

		// Look for an id , either mongo id , or product id !	
            if (is_mongo_id($arg)){

				$this->id = $arg;
                continue; // continue if the condition is met , go next loop
			}

            // Check if there is popo with this arg in popo folder
			if (popo_exists($this->endpoint, uc_first($arg))){
                
				$this->popo = uc_first($arg);
                
			}

		}

        
        
        
        // Request type
		$this->request_type = $this->get_request_method();


        // PUT and DELETE can be hidden inside of an POST request , check them :
        if ($this->request_type == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
            if ($_SERVER['HTTP_X_HTTP_METHOD'] == "DELETE") {
                $this->request_type = 'DELETE';
            } else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
                $this->request_type = 'PUT';
            }
        }

        // Get all inputs 
		$this->input = @file_get_contents('php://input');


		$this->input = json_decode($this->input);


        
        // Check if request method is either POST or PUT and if yes , check if input is empty or not 
        validate_input($this->input,$this->request_type);



        // Get params from GET , if is set
		if (isset($_GET)){
			$this->params = $_GET;
            // first param is like : /produtcs/34534543  , So we dont need it 
			array_shift($this->params);

		}

        // Get params from POST , if is set
		if (isset($_POST)){

			foreach ($_POST as $k=>$v){
				$this->params[$k] = $v;;
			}

		}


        // Define the protocol
		$this->protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";

}



/**
    * Method : get_request_method
    * Will show the request method :D
*/
protected function get_request_method(){
    return $_SERVER['REQUEST_METHOD'];
}




/**
    * Method : getParam
    * Gets a parameter and checks into POST and GET and inputs to see if it has been specified or not
*/
public function getParam($p, $default=false){
  $param = "";


        if (isset($_GET[$p])){
            $param = trim($_GET[$p]);
        } else {
            if (isset($_POST[$p])){
                $param = trim($_POST[$p]);
            } else {
                // Check if there is a $p in out inputs
                if (isset($this->input->$p)){
                    if (!is_array($this->input->$p)){
                        $param = trim($this->input->$p);
                    } else {
                        $param = $this->input->$p;
                    }
                }
            }   
        }

        if ($param == ""){
            if ($default){
                return $default;
            } else {
                return false;
            }
        } else {
           return $param;
        }
    }

}
?>