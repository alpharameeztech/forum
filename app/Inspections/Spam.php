<?php

namespace App\Inspections;
use App\Inspections\InvalidKeywords;
use Exception;

class Spam
{

    protected $inspections = [

        InvalidKeywords::class,
        DetectKeyHeldDown::class
        
    ];
    
    public function detect($body){


        foreach($this->inspections as $inspection){

            app($inspection)->detect($body); // this will create an instance of the class and call a method of that class 

        }

        return false; // if there is no exception thrown

    }

}
