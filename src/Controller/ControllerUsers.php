<?php

namespace Controller;

use Model\Connection;
use Model\UserMapperDatabase;
use Model\UserDatabaseFinder;
use Model\UserMapperInterface;
use Model\User;
use Exception\UnexistUserException;
/**
 * Description of ControllerUsers
 *
 * @author stnunes
 */
class ControllerUsers {
    
    private $mapper;
    
    private $finder;
    
    public function __construct(Connection $connection) 
    {
        $this->mapper = new UserMapperDatabase($connection);
        $this->finder = new UserDatabaseFinder($connection);
    }
    
    public function verifyLoginPasswordToLogin($loginToVerify, $passwordToVerify) {
        try {
            
            $user = $this->finder->getUser($loginToVerify);
        } catch (UnexistUserException $uue) 
        {
            return false;
        }
       
        if ($loginToVerify === $user->getLogin() && password_verify($passwordToVerify, $user->getPassword())) {
            
            return true;
        }
        return false;
        
    }
    
    public function postUser($login, $hashedPassword) {
        $user = new User($login, $hashedPassword);
        return $this->mapper->persist($user);
    }
}
