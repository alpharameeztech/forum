<?php

namespace App\Repository\Tools;

use App\Tool;

class AllJson
{

    public static function get($product_id)
    {

        $tools = Tool::where('product_id',$product_id)->get();

        $data = [

            'items' => []
        ];

        foreach($tools as $key=>$value){

            $data[$value->product_id] = [
                'id' => $value->product_id,
                'toolName' => $value->title,
                'toolDisc' => $value->title,
                'toolPdf' => $value->title
            ];

        }
//        return $data;
        return $tools;

    }

    public static function all(){
        $tools = Tool::latest()->get();

        return $tools;
    }

}
