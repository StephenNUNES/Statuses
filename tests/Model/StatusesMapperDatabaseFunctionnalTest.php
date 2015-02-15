<?php

/**
 * Description of StatusesMapperDatabaseFunctionnalTest
 *
 * @author stnunes
 */
class StatusesMapperDatabaseFunctionnalTest extends TestCase {
    
    private $con;
    
    private $mapper;

    public function setUp()
    {
        
        $this->con = new \Model\Connection('sqlite::memory:');
        $this->con->exec(<<<SQL
            CREATE TABLE IF NOT EXISTS Users
            (
                login VARCHAR(50) NOT NULL PRIMARY KEY,
                password VARCHAR(100) NOT NULL
            );

            CREATE TABLE IF NOT EXISTS Statuses 
            (
                id INTEGER PRIMARY KEY,
                message VARCHAR(140) NOT NULL,
                dateStatus DATE NOT NULL,
                nameCreator VARCHAR(50) NOT NULL,
                senderClient VARCHAR(50) NOT NULL,
                CONSTRAINT fk_user FOREIGN KEY (nameCreator) REFERENCES Users(login)
            );
SQL
        );
        $this->mapper = new \Model\StatusesMapperDatabase($this->con);
    }

    public function testNumberRow() {
        
        
        $rows = $this->con->queryData("SELECT COUNT(*) FROM Statuses");
        $this->assertEquals(0, $rows[0]["COUNT(*)"]);      
    }
    
    public function testPersist() {
        
        $status = new Model\Status("12", "message de test", new DateTime("2014-08-05"),
                    "FunctionnalTest", "Windows phone");             
        $this->mapper->persist($status);
        $rows = $this->con->queryData("SELECT * FROM Statuses WHERE id='12'");
        
        $this->assertEquals('12', $rows[0]['id']);  
        
    }
    
    public function testRemove() {
        
        $status = new Model\Status("1256456", "message de test", new DateTime("2014-08-05"),
                    "FunctionnalTest", "Windows phone");             
        $this->mapper->persist($status);
        $rows = $this->con->queryData("SELECT * FROM Statuses WHERE id='1256456'");
        
        $this->assertEquals('1256456', $rows[0]['id']);  
        $this->mapper->remove('1256456');
        
        $resultCount = $this->con->queryData("SELECT COUNT(*) FROM Statuses");
        $this->assertEquals(0, $resultCount[0]["COUNT(*)"]);
        
    }
    
    
}
    

