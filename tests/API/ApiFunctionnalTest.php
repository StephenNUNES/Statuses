<?php 

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use Goutte\Client;
/**
 * Description of ApiFunctionnalTest
 *
 * @author stnunes
 */
class ApiFunctionnalTest extends TestCase {
    
    private $client;
    
    private $endpoint;
    
    public function setUp()
    {
        $this->client   = new Client();
        $this->endpoint = "http://localhost:82";
    }
    
    public function testRequestGiveNotFound() {
        $crawler  = $this->client->request('GET', sprintf('%s/zertyhujk', $this->endpoint));
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatus());
    }
    
    public function testRequestGetStatuses() {
        $crawler  = $this->client->request('GET', sprintf('%s/statuses', $this->endpoint));
        $response = $this->client->getResponse();
        
        $this->assertEquals(200, $response->getStatus());
        $this->assertEquals('text/html', $response->getHeader('Content-Type'));
    }
    
    public function testRequestGetOneStatuses() {
        $crawler  = $this->client->request('GET', sprintf('%s/statuses/0', $this->endpoint));
        $response = $this->client->getResponse();
        
        $this->assertEquals(200, $response->getStatus());
        $this->assertEquals('text/html', $response->getHeader('Content-Type'));
        
    }
    
    public function testRequestPostStatuses() {
        $params = ["username" => "stephen", "_method" => "POST", "message" => "goutte cest génial"];
        $crawler  = $this->client->request('POST', sprintf('%s/statuses', $this->endpoint, $params));
        $response = $this->client->getResponse();
        $this->assertEquals(201, $response->getStatus());
        $this->assertEquals('text/html', $response->getHeader('Content-Type'));
    }
    
    public function testRequestDeleteStatuses() {
        $params = ["_method" => "DELETE"];
        $crawler  = $this->client->request('DELETE', sprintf('%s/statuses/0', $this->endpoint, $params));
        $response = $this->client->getResponse();
        
        $this->assertEquals(200, $response->getStatus());
        $this->assertEquals('text/html', $response->getHeader('Content-Type'));
        
    }
    
}
