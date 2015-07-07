<?php

    
    /**
        * Class :  API
        * Description : will be filled later !
        * Author : Reza Bashash , Milad Hosseini
    **/




class Api{




    /** 
        * Property : $data
        * All the possible properties that we need later
    */  

    protected $data = array();





    
    /** 
        * Property : $controller
        * its the Controller class which is the same as endpoint
    */  
    protected $controller;




    /**
     * Constructor: __construct
     * There is no other method in our API , when it is constructed , everything will fires
     */

    public function __construct($request) {

            
        // Get the request , which is an array of ($id , $token , $args , $params , $popo , ....)
        $data = new Request($request);

    
        
            // check if there is controller with this endpoint name : 

              
            load_controller($data->endpoint);

            /*
                Explanation : 

                if there is a popo name in request , we dont need to instantiate the endpoint 
                because the popo itself can access the property of its father ( which is the endpoint )

                if there is no popo
                then we must instantiate the Parent class for example : Products 
                this will just fire a mainRouter function in that class 
                
                So : mainRouter only will fire if there is no popo specified !

            */


            if($data->popo!=''){ // means there is a method(verb) beside of the endpoint 


                // Load the popo 
                load_popo($data->endpoint, $data->popo);



                // popo name like : ProdutcsSale   = > Products is a endpoint and Sale is a popo
                 $class_name = $data->endpoint.$data->popo;
                


                // instantiate the popo class
                 
                $this->controller = new $class_name($data, array());
                new Response($this->controller->response,$data);


                
            } else { // no popo


                $this->controller = new $data->endpoint(); 


                $this->controller->{"mainRouter"}($data, array());
                

                new Response($this->controller->response,$data);
            }

             

    }


}


?>