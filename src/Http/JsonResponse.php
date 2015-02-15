<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Http;

use Http\Response;
/**
 * Description of JsonResponse
 *
 * @author stnunes
 */
class JsonResponse extends Response {
    
    public function __construct($content, $statusCode = 200, array $headers = [])
    {
        $this->statusCode = $statusCode;
        $this->headers    = array_merge([ 'Content-Type' => 'application/json' ], $headers);
        $arrayContent = [];
        if (is_array($content['statuses']))
        {
            foreach ($content['statuses'] as $objectStatus)
            {
                array_push($arrayContent, $objectStatus->toFormatForJson());
            }
            $this->content = json_encode($arrayContent);
        }
        else
        {
            $this->content = json_encode($content['statuses']);
        }
     
        
    }
    
    public function getContent()
    {
        return $this->content;
    }
}
