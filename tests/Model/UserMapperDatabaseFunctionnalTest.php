<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UserMapperDatabaseFunctionnalTest
 *
 * @author stnunes
 */
class UserMapperDatabaseFunctionnalTest extends TestCase {
    
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
        $this->mapper = new \Model\UserMapperDatabase($this->con);
    }

    public function testNumberRow() {
        
        $rows = $this->con->queryData("SELECT COUNT(*) FROM Users");
        $this->assertEquals(0, $rows[0]["COUNT(*)"]);      
    }
    
    public function testPersist() {
        
        $user = new Model\User("phpuser", "motdepasse");             
        $this->mapper->persist($user);
        $rows = $this->con->queryData("SELECT * FROM Users WHERE login='phpuser'");
        
        $this->assertEquals('phpuser', $rows[0]['login']);  
        
    }       
}
