<?php

        /*

                Explanations : 
                
                All the methods that we need in our various classes in the Api , but are not specially 
                related to REST .
                This means any function that is generally used will be defined here

        */








/**
    * Method : pre
    * will echo out the json/array file in a pretty way
*/

function pre($s){
	echo "<pre>";
	print_r($s);
	echo "</pre>";
}


function prex($s){
    echo "<pre>";
    print_r($s);
    echo "</pre>";
    exit();
}




// function unset_return(&$array , $key){

//     $r = $array[$key];
//     unset($array[$key]);
//     return $r;
// }


/**
    * Method : is_mongo_id
    * Check if given $id is actually is a mongo id 
    * A mongo id is 24 character long and contains both numbers and characters and nothing else
*/

function is_mongo_id($id){
    $regex = '/^[0-9a-z]{24}$/';
    if (class_exists("MongoId")){
        try{
            $tmp = new MongoId($id);
            if ($tmp->{'$id'} == $id){
               return true;
           }
           return false;
       } catch (Exception $e){
        return false;
    }
}

if (preg_match($regex, $id)){
    return true;
}
return false;
}









/**
    * Method : uc_first
    * This will just uppercase the first letter of the string , and the rest will remain unchanged
    * eg : miladHosseini  ---- >   MiladHosseini
    * This is different from php's ucfirst , because that will lowercase the rest of the string 
*/

function uc_first($str){
   
    $first_letter = substr($str, 0,1);
    $remain = substr($str, -(strlen($str)-1));;
    $first_letter = strtoupper($first_letter);

    return $first_letter.$remain;
}

















/**
    * Method : convert_to_array
    * Will itterate through a MongoObject and converts it to an simple array
*/


function convert_to_array($mongoObj){

    
    $array = array();

    foreach ($mongoObj as $obj) {
        array_push($array , $obj);
    }

    return $array;
}




/**
    * Method : convert_to_mongoIds
    * Will get array of ids and return an array of mongo ids;
*/
function convert_to_mongoIds($ids){

    if(sizeof($ids)==0){
        return array();
    }

    $MongoIds = array();

    foreach ($ids as $id) {

        $MongoIds[] = new MongoId($id);

    }

    return $MongoIds;

}



function create_array($number, $data)
{
    $result = array();
    foreach ($data as $row)
    {
        if ($row['enroller_id'] == $number)
        {
            $result[$row['id']] = create_array($row['id'], $data);
        }
    }
    return $result;
}





function translate($data,$language,$request_original=false){

    if($data==null || empty($data)){return $data;}
    $ignore_keys = array("_id","photos","product_id","parent","set","ts","added_by","added_on");
    $price_keys = array("price","discount");

    

    $result = array();
   // $result['language'] = $GLOBALS['lang']->language;
    foreach ($data as $key=>$value) {

        if(in_array($key, $ignore_keys,true)){
            $result[$key] = $value;
            //$result[$key."_en"] = $value;
            continue;
        }

        if(in_array($key, $price_keys,true)){

            $result[$key] = $GLOBALS['price']->getPriceRaw($value);
            $result[$key."_formated"] = $GLOBALS['price']->getPrice($value);
            continue;
        }        

        if(is_array($value)){

            $result[$key] = translate($value,$language,$request_original);            

        }else{

            $result[$key] = $GLOBALS["lang"]->getTranslate($value);
            
            if($request_original==="TRUE"){
                $result[$key."_original"] = $value;
            }

        }
    }
    return $result;
}
















function makeSalt()
{
        // A higher "cost" is more secure but consumes more processing power
    $cost = 10;

        // Create a random salt
    $salt = substr(str_replace('/', '', str_replace('+', '.', base64_encode(md5(mt_rand() . microtime(), true)))), 0, 32);

        // "$2a$" Means we're using the Blowfish algorithm. The following two digits are the cost parameter.
    $salt = sprintf("$2a$%02d$", $cost) . $salt;

    return $salt;
}







function hashPassword($password, $salt)
{
    $password = md5(md5($password) . SHA1($salt));
    return $password;
}







function isValidEmail($email_address)
{
    if ( ! empty($email_address))
        if ( ! preg_match('/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{1,6}$/i', $email_address))
        {
            return FALSE;
        }

        return TRUE;
    }



    ?>