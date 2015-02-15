<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Http;

use Exception\HttpParameterNotFound;
use Negotiation\FormatNegotiator;
/**
 * Description of Request
 *
 * @author stnunes
 */
class Request {
    
    const GET    = 'GET';

    const POST   = 'POST';

    const PUT    = 'PUT';

    const DELETE = 'DELETE';
    
    private $parameters = [];
    
    /**
     * 
     * @param array $query is an array of GET parameters ($_GET). We use the term query as these parameters are part of the Query String.
     * @param array $request is an array of POST parameters ($_POST). We use the term request as these parameters are part of the Request Body.
     */
    public function __construct(array $query, array $request)
    {
        $this->parameters = array_merge($query , $request);
    }
    
    public static function createFromGlobals()
    {
        if (isset($_SERVER['HTTP_CONTENT_TYPE']))
        {
            if ($_SERVER['HTTP_CONTENT_TYPE'] === "application/json")
            {
                $data    = file_get_contents('php://input');
                $request = json_decode($data, true);
                return new self($_GET, $request);
            }
        } 
        else if (isset($_SERVER['CONTENT_TYPE']))
        {
            if ($_SERVER['CONTENT_TYPE'] === "application/json")
            {
                $data    = file_get_contents('php://input');
                $request = @json_decode($data, true);
                var_dump($request);
                return new self($_GET, $request);
            }
        }
        return new self($_GET, $_POST);
    }
    
    public function getMethod()
    {
        $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : self::GET;
        
        if (self::POST === $method)
        {
            return $this->getParameter("_method", $method);
        }
        else
        {
            return $method;
        }
    }
    
    public function getUri()
    {
        $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
        if ($pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        return $uri;
    }
    
    public function getParameter($name, $default = null)
    {
        if (isset($this->parameters[$name]))
        {
            return $this->parameters[$name];
        }
        
        
        return $default; 
    }
    
    public function guessBestFormat()
    {
        $negociator = new FormatNegotiator();
        return $negociator->getFormat($_SERVER['HTTP_ACCEPT']);
    }
}