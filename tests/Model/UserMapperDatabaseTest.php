<?php

namespace Model;

/**
 * Description of UserMapperDatabaseTest
 *
 * @author stnunes
 */
class UserMapperDatabaseTest extends \TestCase{
    
    private $userMapperDatabase;

    public function setUp()
    {
        $mockConnection = $this->getMockBuilder('Model\MockConnection')->getMock(); 
        $this->userMapperDatabase = new UserMapperDatabase($mockConnection);
        $mockConnection->expects($this->any())->method('executeQuery')
                ->will($this->returnValue(true));
    }
    
    public function testPersistMethod() {
        $user = new User("login", "password");
        $this->assertEquals($this->userMapperDatabase->persist($user), true);
    }
    
    
}
