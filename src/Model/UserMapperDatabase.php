<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Model;

/**
 * Description of UserMapperDatabase
 *
 * @author stnunes
 */
class UserMapperDatabase implements UserMapperInterface {
    
    private $con;

    public function __construct(Connection $con)
    {
        $this->con = $con;
    }
    
    public function persist($user) {
        $parameters = ["login" => $user->getLogin(), "password" => $user->getPassword()];
        $query = "INSERT INTO Users(login, password) "
                . "VALUES(:login, :password)";
        return $this->con->executeQuery($query, $parameters);
    }
    
}
