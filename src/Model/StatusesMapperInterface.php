<?php

namespace Model;

interface StatusesMapperInterface
{
   public function persist(Status $status);

   public function remove($id);
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

