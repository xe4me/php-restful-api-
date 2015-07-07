<?php

/**
    * Rest      : 0    -    99
    * Adds      : 100  -   199
    * Users      : 200  -   299
*/



class Error{

// Error codes and descriptions

    public $errors = '{
        "errors" : [
        {
            "id"            :       1,
            "category"      :       "rest",
            "message"       :       "Invalid endpoint",
            "hint"          :       "Please make sure you are calling the correct endpoint.",
            "httpCode"      :       404,
            "critical"      :       1,
            "crash"         :       1
        },
        {
            "id"            :       2,
            "category"      :       "rest",
            "message"       :       "Request empty",
            "hint"          :       "Your request contains an empty string. Please check your endpoint.",
            "httpCode"      :       400,
            "critical"      :       1,
            "crash"         :       1
        },
        {
            "id"            :       3,
            "category"      :       "rest",
            "message"       :       "Invalid method",
            "hint"          :       "Please make sure you use a provided method or reffer to the documentation",
            "httpCode"      :       404,
            "critical"      :       1,
            "crash"         :       1
        },
        {
            "id"            :       4,
            "category"      :       "rest",
            "message"       :       "Response empty",
            "hint"          :       "Please make sure you have the right endpoint",
            "httpCode"      :       500,
            "critical"      :       1,
            "crash"         :       1
        },
        {
            "id"            :       5,
            "category"      :       "rest",
            "message"       :       "Request type not supported",
            "hint"          :       "Please make sure to use the right request",
            "httpCode"      :       404,
            "critical"      :       1,
            "crash"         :       1
        },
        {
            "id"            :       6,
            "category"      :       "rest",
            "message"       :       "Duplicate key error index",
            "hint"          :       "The _id specified in the mongo object is dupplicated",
            "httpCode"      :       409,
            "critical"      :       1,
            "crash"         :       1
        },
        {
            "id"            :       7,
            "category"      :       "rest",
            "message"       :       "Invalid authentication token",
            "hint"          :       "Please check your authentication token ",
            "httpCode"      :       403, 
            "critical"      :       1,
            "crash"         :       1
        },
        {
            "id"            :       8,
            "category"      :       "rest",
            "message"       :       "Unspecified access header",
            "hint"          :       "You must send correct access header",
            "httpCode"      :       400, 
            "critical"      :       1,
            "crash"         :       1
        },
        {
            "id"            :       9,
            "category"      :       "rest",
            "message"       :       "Server is not responding",
            "hint"          :       "Check if server is up and running",
            "httpCode"      :       500, 
            "critical"      :       1,
            "crash"         :       1
        },
        {
            "id"            :       10,
            "category"      :       "rest",
            "message"       :       "No arguments supplied ",
            "hint"          :       "Make sure to supply arguments in the request body ",
            "httpCode"      :       400, 
            "critical"      :       1,
            "crash"         :       1
        },
        {
            "id"            :       11,
            "category"      :       "rest",
            "message"       :       "Invalid input arguments supplied",
            "hint"          :       "Check if your input arguments are valid ",
            "httpCode"      :       400, 
            "critical"      :       1,
            "crash"         :       1
        },
        {
            "id"            :       12,
            "category"      :       "rest",
            "message"       :       "No ids supplied in inputs ",
            "hint"          :       "Input must has either mids or pids",
            "httpCode"      :       400, 
            "critical"      :       1,
            "crash"         :       1
        },
        {
            "id"            :       13,
            "category"      :       "rest",
            "message"       :       "Server not responding",
            "hint"          :       "Crash on connecting to database",
            "httpCode"      :       500, 
            "critical"      :       1,
            "crash"         :       1
        },
        {
            "id"            :       200,
            "category"      :       "Users",
            "message"       :       "لطفا فيلد ها را پر کنيد",
            "hint"          :       "مطمئن شويد همه ی فيلدها را به درستي پر کرده ايد",
            "httpCode"      :       400, 
            "critical"      :       1,
            "crash"         :       1
        },
        {
            "id"            :       201,
            "category"      :       "Users",
            "message"       :       "رمز عبور و تکرار آن بايستي برابر باشند",
            "hint"          :       "رمز عبور و تکرا آن برابر نيستند.",
            "httpCode"      :       400, 
            "critical"      :       1,
            "crash"         :       1
        },
        {
            "id"            :       202,
            "category"      :       "Users",
            "message"       :       "این شماره تلفن در سيستم وجود دارد",
            "hint"          :       " اين شماره تلفن قبلا در سيستم ثبت شده است",
            "httpCode"      :       409, 
            "critical"      :       1,
            "crash"         :       1
        },
        {
            "id"            :       203,
            "category"      :       "Users",
            "message"       :       "کاربری با اين اطلاعات وجود ندارد",
            "hint"          :       " مطمئن شويد اطلاعات خود را درست وارد کرده ايد",
            "httpCode"      :       404, 
            "critical"      :       1,
            "crash"         :       1
        },
        {
            "id"            :       204,
            "category"      :       "Users",
            "message"       :       "رمز عبور و يا شماره تلفن شما اشتباه است",
            "hint"          :       " مطمئن شويد اطلاعات خود را درست وارد کرده ايد",
            "httpCode"      :       404, 
            "critical"      :       1,
            "crash"         :       1
        }
        ]
    }';

    public $errorId;
    public $errorType;
    public $action;
    public $errorMessage;


    public function __construct($id=0){


        $this->errors = json_decode($this->errors);

        if ($id!=0){
            $error = $this->getError($id);

            if ($error->critical == 1){
                // Report to Admin, the error is critical. Write to file.
                $this->log($error);
            }
            

            http_response_code($error->httpCode);

            
            echo json_encode(
                array(
                    "error_id"      =>$error->id,
                    "error_message" => $error->message,
                    "hint"          => $error->hint,
                    "success"      =>"0" 
                    )
                );
            if ($error->crash){
                exit();
            }
        }


    }


    





    // Get a specific error by its ID
    public function getError($id){
        foreach ($this->errors->errors as $e){
            if ($e->id == $id){
                return $e;
            }
        }
    }

    /*

        I Disabled writing to error.log , because of git conflict error messages

    */

    // Write the error to file if critical is set to 1
        private function log($e){
            $e->time = time();
        //file_put_contents("Repo/errors.log", json_encode($e)."\r\n", FILE_APPEND);
        }
    }
    ?>