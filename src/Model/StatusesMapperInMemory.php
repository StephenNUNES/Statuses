<?php

namespace Model;

/** Classe de mapper des objets métier statuses
 *
 */
class StatusesMapperInMemory implements StatusesMapperInterface
{
   /**
    * Tableau contenant les status enregistrés.
    */
   private $statusesArray;
   
   public function __construct() 
   {
        $this->statusesArray = array();
   }   

   /** Méthode permettant de sauvegarder une instance de la classe Status
    */
   public function persist(Status $status)
   {
      $this->statusesArray[$status->getId()] = $status;
   }

   /** Méthode permettant de supprimer  une instance de la classe Status
    */
   public function remove($id)
   {
      unset($this->statusesArray[$id]);
   }

   /** Permet d'obtenir le tableau des statuses */
   public function getAllStatuses(Criteria $criteria = null)
   {
      return $this->statusesArray;
   }

}
