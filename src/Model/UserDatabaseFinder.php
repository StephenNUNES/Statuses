<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Model;

use Model\UserMapperInterface;
use Model\User;
use Exception\UnexistUserException;
use Model\Connection;

/**
 * Description of UserDatabaseFinder
 *
 * @author stnunes
 */
class UserDatabaseFinder implements UserFinderInterface {
    
    /** 
    * Instance de Connection utilisé */
    private $connection;
    
    /**
    * Constructeur du Finder d'users
    */
   public function __construct(Connection $connection)
   {
      $this->connection = $connection; 
   }
    
    
    public function getUser($loginOfUser) {
        $query = "SELECT * FROM Users WHERE login='" . $loginOfUser . "'";
        $user = $this->connection->queryData($query);
               
        if (!empty($user)) {
            
            return new User($user[0]['login'], $user[0]['password']);
        }
        throw new UnexistUserException;
    }
}
