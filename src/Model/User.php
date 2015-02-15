<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Model;
/**
 * Description of User
 *
 * @author stnunes
 */
class User {
    
    private $login;
    
    private $password;
    
    public function __construct($login, $password) {
        $this->login = $login;
        $this->password = $password;
    }
    
    public function getLogin() {
        return $this->login;
    }
    
    public function getPassword() {
        return $this->password;
    }

}
