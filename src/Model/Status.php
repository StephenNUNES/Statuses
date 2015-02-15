<?php

namespace Model;

use DateTime;
/** POPO représentant un status de l'application.
 * Il s'agit d'une Entity en terme de DDD 
 */
class Status 
{
   /** Id numérique de Status */
   private $id;

   /** Message du Status, ne doit pas excéder 140 caractères */
   private $message;

   /** Date de création du Status */
   private $dateStatus;

   /** Nom du membre ayant créé le Status */
   private $nameCreator;

   /** Client ayant envoyé le status ( iOs, Android, desktop, etc) n'est pas obligatoire */
   private $senderClient;
   
   public function __construct($id, $message, DateTime $date, $nameCreator, $senderClient)
   {
       $this->id = $id;
       $this->message = $message;
       $this->dateStatus = $date;
       $this->nameCreator = $nameCreator;
       $this->senderClient = $senderClient;
   }

   public function getId()
   {
       return $this->id;
   }
   
   public function getMessage()
   {
       return $this->message;
   }
   
   public function getDateStatus()
   {
       return $this->dateStatus;
   }
   
   public function getNameCreator()
   {
       return $this->nameCreator;
   }
   
   public function getSenderClient()
   {
       return $this->senderClient;
   }
   
    public function __toString()
    {
        return $this->dateStatus->format("d-m-Y") . " " . $this->nameCreator . " said : " . $this->message;
    }
    
    public function toFormatForJson()
    {
        $arrayJson = [];
        $arrayJson["id"] = $this->getId();
        $arrayJson["message"] = $this->getMessage();
        $arrayJson["date"] = $this->getDateStatus();
        $arrayJson["nameCreator"] = $this->getNameCreator();
        $arrayJson["senderClient"] = $this->getSenderClient();
        return $arrayJson;
    }
}
