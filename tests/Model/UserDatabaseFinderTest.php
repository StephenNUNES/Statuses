<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserDatabaseFinderTest
 *
 * @author stnunes
 */
class UserDatabaseFinderTest extends TestCase {
    
    private $finder;
    
    private $mockConnection;
    
    public function setUp()
    {
        $this->mockConnection = $this->getMockBuilder('Model\MockConnection')->getMock(); 
        $this->finder = new \Model\UserDatabaseFinder($this->mockConnection);
    }
    
    public function testGetUserReturnTheResultFormattedInObject()
    {
        $this->mockConnection->expects($this->any())->method('queryData')
                ->will($this->returnValue([0 => ["login" => "monnouveaulogin", "password" => "azertyuiop"]]));
        $this->assertEquals($this->finder->getUser("monnouveaulogin")->getPassword(), "azertyuiop"); 
    }
    
}
