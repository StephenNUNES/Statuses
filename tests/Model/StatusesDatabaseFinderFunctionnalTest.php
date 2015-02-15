<?php


/**
 * Description of DatabaseFinderFunctionnalTest
 *
 * @author stnunes
 */
class StatusesDatabaseFinderFunctionnalTest extends TestCase {
    
    private $con;
    
    private $finder;

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
        $this->finder = new \Model\StatusesDatabaseFinder($this->con);
    }

    public function testFindAllWithoutRows() {
       
        $rows = $this->finder->findAll();
  
        $this->assertCount(0, $rows);      
    }
    
    public function testFindAllWithRows() {
        $parametersFirstRequest = ["id" => "78", "message" => "mon premier message", "dateStatus" => "2014-06-14", "nameCreator" => "marjorie", "senderClient" => "Njob n goss OS"];
        $parametersSecondRequest = ["id" => "47", "message" => "mon deuxième message", "dateStatus" => "2018-06-14", "nameCreator" => "marjorie", "senderClient" => "Android"];
        $query = "INSERT INTO Statuses(id, message, dateStatus, nameCreator, senderClient) "
                . "VALUES(:id, :message, :dateStatus, :nameCreator, :senderClient)";
        $this->con->executeQuery($query, $parametersFirstRequest);
        $this->con->executeQuery($query, $parametersSecondRequest);
        
        $rows = $this->finder->findAll();
        $this->assertCount(2, $rows);
        
    }
    
    public function testFindOneById() {
        
        $parametersFirstRequest = ["id" => "78", "message" => "mon premier message", "dateStatus" => "2014-06-14", "nameCreator" => "marjorie", "senderClient" => "Njob n goss OS"];
        $parametersSecondRequest = ["id" => "47", "message" => "mon deuxième message", "dateStatus" => "2018-06-14", "nameCreator" => "marjorie", "senderClient" => "Android"];
        $query = "INSERT INTO Statuses(id, message, dateStatus, nameCreator, senderClient) "
                . "VALUES(:id, :message, :dateStatus, :nameCreator, :senderClient)";
        $this->con->executeQuery($query, $parametersFirstRequest);
        $this->con->executeQuery($query, $parametersSecondRequest);
        
        $rows = $this->finder->findOneById("47");
        $this->assertEquals("mon deuxième message", $rows->getMessage());
    }
    
    public function testFindNextIdAvailable() {
        
        $parametersFirstRequest = ["id" => "564", "message" => "mon premier message", "dateStatus" => "2014-06-14", "nameCreator" => "marjorie", "senderClient" => "Njob n goss OS"];
        $parametersSecondRequest = ["id" => "49999", "message" => "mon deuxième message", "dateStatus" => "2018-06-14", "nameCreator" => "marjorie", "senderClient" => "Android"];
        $query = "INSERT INTO Statuses(id, message, dateStatus, nameCreator, senderClient) "
                . "VALUES(:id, :message, :dateStatus, :nameCreator, :senderClient)";
        $this->con->executeQuery($query, $parametersFirstRequest);
        $this->con->executeQuery($query, $parametersSecondRequest);
        
        $rows = $this->finder->findNextIdAvailable();

        $this->assertEquals(50000, $rows);
    }

}
