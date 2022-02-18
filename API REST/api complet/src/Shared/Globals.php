<?php

namespace App\Shared;

use Exception;

class Globals
{
    public function json_decode()
    {
        try{
           return file_get_contents('php://input')?
           json_decode(file_get_contents('php://input')):[];
        }catch(Exception $e){
           return [];
        }
    }
}