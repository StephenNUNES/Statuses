<?php

namespace Model;

use DateTime;
/**
 * Description of StatusesMapperDatabaseTest
 *
 * @author stnunes
 */
class StatusesMapperDatabaseTest extends \TestCase{
    
    private $statusesMapperDatabase;

    public function setUp()
    {
        $mockConnection = $this->getMockBuilder('Model\MockConnection')->getMock(); 
        $this->statusesMapperDatabase = new StatusesMapperDatabase($mockConnection);
        $mockConnection->expects($this->any())->method('executeQuery')
                ->will($this->returnValue(true));
    }
    
    public function testPersistMethod() {
        $status = new Status("1", "coucou", new DateTime("2011-01-14"), "Marjorie", "Blackberry");
        $this->assertEquals($this->statusesMapperDatabase->persist($status), true);
    }
    
    public function testRemoveMethod() {
        $this->assertEquals($this->statusesMapperDatabase->remove("45"), true);
    }
    
    
    
}
