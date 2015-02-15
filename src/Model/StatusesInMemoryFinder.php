<?php

namespace Model;

use Exception\UnexistStatusException;
use Http\Criteria;
/** Implémentation de l'interface Finder
 *
 */ 
class StatusesInMemoryFinder implements StatusesFinderInterface {

   /** 
    * Instance du StatusMapper */
   private $statusMapper;
   
   /**
    * Constructeur du Finder de status
    */
   public function __construct(StatusesMapperInterface $statusMapper)
   {
      $this->statusMapper = $statusMapper; 
   }
   
   /**
     * {@inheritDoc}
   */
   public function findAll(Criteria $criteria = null)
   {
      $allStatuses = [];
      foreach ($this->statusMapper->getAllStatuses() as $id => $status)
      {
         array_push($allStatuses, $status);
      }
      return $allStatuses;
   }

   /**
     * {@inheritDoc}
   */
   public function findOneById($id)
   {    
      if (array_key_exists($id, $this->statusMapper->getAllStatuses()))
      {
          return $this->statusMapper->getAllStatuses()[$id];
      }
      else
      {
          throw new UnexistStatusException();
      }
   }
}
