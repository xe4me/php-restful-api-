<?php


/**
    * Class : Response
    * will echo out the json_encoded response given in the constructor 
*/



class Response{


// Encapsulate the response and output as JSON. Set success headers.

    public function __construct($response,$data){

            //ob_start('ob_gzhandler');
            // SUCCESS RESPONSE CODE
            
            http_response_code(200);
            header("Content-Type: application/json; charset=utf-8");
            header("Transfer-Encoding : deflate");
            header("Content-Encoding : deflate");

            // Wrap or no Wrap?
            // No Wrap is suggested by many, but can be used to measure time processed etc.
            // $response['timestamp']              = time();
            // $response['processing_speed']        = microtime(true) - $GLOBALS['start'];

            
            // If ?callback is provided, encapsulate in ( ) for JSONP.
            if (isset($_GET['callback'])){

                /*
                header("Access-Control-Allow-Orgin: *"); // allow any origin (Ip address ) to request our API
                header("Access-Control-Allow-Methods: *"); // allow any method from any origin to be sent to our API
                */

                header("Content-Type: application/json");

                echo $_GET['callback']."(".json_encode($response).");";
            } else {
                
                $response = array(
                    "success"=>1,
                    "extra"=>$response
                    );
                echo json_encode($response,JSON_UNESCAPED_UNICODE);
            }
            // If response is empty throw error. Probably caused by our system, so counts as Internal Error.    
            
            exit();
        }
}

?>