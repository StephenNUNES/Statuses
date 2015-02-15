<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Model;
/**
 * Description of DatabaseFinderTest
 *
 * @author stnunes
 */
class StatusesDatabaseFinderTest extends \TestCase {
    
    private $finder;
    
    private $mockConnection;
    
    public function setUp()
    {
        $this->mockConnection = $this->getMockBuilder('Model\MockConnection')->getMock(); 
        $this->finder = new \Model\StatusesDatabaseFinder($this->mockConnection);
    }
    
    public function testFindAllReturnTheResultFormattedInObject()
    {
        $this->mockConnection->expects($this->any())->method('queryData')
                ->will($this->returnValue([0 => ["id" => "0", "message" => "Challenge accepted", "dateStatus" => "2017-06-07", "nameCreator" => "Barnet Stinson", "senderClient" => "iOS"]]));
        $this->assertEquals($this->finder->findAll()[0]->getMessage(), "Challenge accepted"); 
    }
    
    public function testFindOneByIdReturnTheResultFormattedInObject()
    {
        $this->mockConnection->expects($this->any())->method('queryData')
                ->will($this->returnValue([0 => ["id" => "0", "message" => "Challenge accepted", "dateStatus" => "2017-06-07", "nameCreator" => "Barnet Stinson", "senderClient" => "iOS"]]));
        $this->assertEquals($this->finder->findOneById("0")->getNameCreator(), "Barnet Stinson");
    }
    
    public function testFindNextIdAvailableReturnTheNextIdAvailable()
    {
        $this->mockConnection->expects($this->any())->method('queryData')
                ->will($this->returnValue([0 => ["MAX(id)" => 0, ]]));
        $this->assertEquals(count($this->finder->findNextIdAvailable(0)), 1);
    }
}
