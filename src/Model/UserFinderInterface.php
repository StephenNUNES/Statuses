<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Model;
/**
 * Description of UserFinderInterface
 *
 * @author stnunes
 */
interface UserFinderInterface {
    
    public function getUser($loginOfUser);
}
