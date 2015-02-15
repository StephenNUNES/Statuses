<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserDatabaseFinderFunctionnalTest
 *
 * @author stnunes
 */
class UserDatabaseFinderFunctionnalTest extends TestCase {
  
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
        $this->finder = new \Model\UserDatabaseFinder($this->con);
    }

    public function testGetUser() {
       
         $parametersFirstRequest = ["login" => "stephen", "password" => "stephenPassword"];
         $parametersSecondRequest = ["login" => "marjorie", "password" => "marjoriePassword"];
         $query = "INSERT INTO Users(login, password) "
                . "VALUES(:login, :password)";
         $this->con->executeQuery($query, $parametersFirstRequest);
         $this->con->executeQuery($query, $parametersSecondRequest);
         $rows = $this->finder->getUser("marjorie");
         $this->assertEquals("marjoriePassword", $rows->getPassword());
         
    }
}
