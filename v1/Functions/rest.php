<?php

        /*

                Explanations : 
                
                All the REST related methods that we need in our various classes in the Api ,
                will be defined here 

        */








/**
    * Method : http_response_code 
    * Will check if there is a method with this name in current version of PHP 
    * if not , we define that .
    * This function will return a related error to the number it takes as it's input
*/
if (!function_exists('http_response_code')) {
    function http_response_code($code = NULL) {

        if ($code !== NULL) {

            switch ($code) {
                case 100: $text = 'Continue'; break;
                case 101: $text = 'Switching Protocols'; break;
                case 200: $text = 'OK'; break;
                case 201: $text = 'Created'; break;
                case 202: $text = 'Accepted'; break;
                case 203: $text = 'Non-Authoritative Information'; break;
                case 204: $text = 'No Content'; break;
                case 205: $text = 'Reset Content'; break;
                case 206: $text = 'Partial Content'; break;
                case 300: $text = 'Multiple Choices'; break;
                case 301: $text = 'Moved Permanently'; break;
                case 302: $text = 'Moved Temporarily'; break;
                case 303: $text = 'See Other'; break;
                case 304: $text = 'Not Modified'; break;
                case 305: $text = 'Use Proxy'; break;
                case 400: $text = 'Bad Request'; break;
                case 401: $text = 'Unauthorized'; break;
                case 402: $text = 'Payment Required'; break;
                case 403: $text = 'Forbidden'; break;
                case 404: $text = 'Not Found'; break;
                case 405: $text = 'Method Not Allowed'; break;
                case 406: $text = 'Not Acceptable'; break;
                case 407: $text = 'Proxy Authentication Required'; break;
                case 408: $text = 'Request Time-out'; break;
                case 409: $text = 'Conflict'; break;
                case 410: $text = 'Gone'; break;
                case 411: $text = 'Length Required'; break;
                case 412: $text = 'Precondition Failed'; break;
                case 413: $text = 'Request Entity Too Large'; break;
                case 414: $text = 'Request-URI Too Large'; break;
                case 415: $text = 'Unsupported Media Type'; break;
                case 500: $text = 'Internal Server Error'; break;
                case 501: $text = 'Not Implemented'; break;
                case 502: $text = 'Bad Gateway'; break;
                case 503: $text = 'Service Unavailable'; break;
                case 504: $text = 'Gateway Time-out'; break;
                case 505: $text = 'HTTP Version not supported'; break;
                default:
                    exit('Unknown http status code "' . htmlentities($code) . '"');
                break;
            }

            $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');

            header($protocol . ' ' . $code . ' ' . $text);

            $GLOBALS['http_response_code'] = $code;

        } else {

            $code = (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);

        }

        return $code;

    }
}







/**
    * Method : load_popo 
    * will include the specified popo name in the API , if it exist in the popo folder 
*/
function load_popo($endpoint, $popo){

  

    if (popo_exists($endpoint, $popo)){

    		$filename = str_replace("/Functions", "",dirname(__FILE__));

			$filename .= '/Popo/'.$endpoint.$popo.'.php';


        include_once $filename;

    } else {

        new Error(3);

    }

}









/**
    * Method : popo_exists 
    * Will check if there is file with specified pop name in the popo folder
*/
function popo_exists($endpoint, $popo){


	$filename = str_replace("/Functions", "",dirname(__FILE__));

	$filename .= '/Popo/'.$endpoint.$popo.'.php';

	if  (file_exists($filename)){
      
		return true;

	} else {
          
		return false;

	}
}










/**
    * Method : load_controller 
    * will include the specified Controller class in the API , if it exist in the Controllers folder 
*/
function load_controller($endpoint){
    if (controller_exists($endpoint)){

        $filename = str_replace("/Functions", "",dirname(__FILE__));
        $filename .= '/Controllers/'.$endpoint.'.php';
        include_once $filename;

    } else {
        
        new Error(1);
        
    }
}










/**
    * Method : controller_exists 
    * Will check if there is file with specified endpoint name in the Controllers folder
*/
function controller_exists($endpoint){

    $filename = str_replace("/Functions", "",dirname(__FILE__));
    $filename .= '/Controllers/'.$endpoint.'.php';
    if  (file_exists($filename)){
        return true;
    } else {
        return false;
    }
}




/**
    * Method : get_request
    * will gets full url and return anything that is after /api/v1/
*/
function get_request($server){



    // Check if user has sent correct access header 
    if(!isset($server['HTTP_ACTOKEN'])){
        
        
        //new Error(8);    

    }



    //$token =trim($server['HTTP_ACTOKEN']);

    $token ="dsfsdfsdfsdf";

    $args = $server['REQUEST_URI'];

    $args = trim($args);
    $args = explode("/api/v1/", $args);

    $args = explode("?", $args[1]);

    $args = $args[0];


    // create an array that contains both arguments and the access token header 
    // we will use this later in Request class 
    $request = array(
            'token' =>$token,
            'args' =>$args
        );

    
    return $request;
}


/**
    * Method : create_hash
    * Will get an string and returns a salted and crypted hash
*/
function create_hash($str){
    

    // A higher "cost" is more secure but consumes more processing power
    $cost = 10;


    // Create a random salt
    $salt = substr(str_replace('+','.',base64_encode(md5(mt_rand(), true))),0,32);


    
    // "$2a$" Means we're using the Blowfish algorithm. The following two digits are the cost parameter.
    $salt = sprintf("$2a$%02d$", $cost) . $salt;


    $hash = crypt($str, $salt);

    $hashes = array(
        'hash'=>$hash,
        'salt'=>$salt
        );
    return $hashes;
}



/**
    * Method : validate_input
    * Will check if input is empty or not while request type is POST or PUT
*/

function validate_input($input , $request_type){

    if($request_type=="POST" || $request_type=="PUT"){

        if($input == ""){

            new Error(10);

        }

    }


}




?>