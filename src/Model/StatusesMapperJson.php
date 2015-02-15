<?php

namespace Model;

class StatusesMapperJson implements StatusesMapperInterface
{
   private $contentJson;
    
   public function persist(Status $status)
   {
       $this->loadData();
       
       array_push($this->contentJson, $status->toFormatForJson());
       
       $this->saveData();
   }

   public function remove($id)
   {
       $this->loadData();
       foreach($this->contentJson as $idStatus => $valueStatus)
       {
           if ($valueStatus["id"] === $id)
           {
               unset($this->contentJson[$idStatus]);
               break;
           }
       }
       $this->saveData();
   }

   public function getAllStatuses(Criteria $criteria = null)
   {
       $this->loadData();
       return $this->contentJson;
   }
   
   private function saveData()
   {
       file_put_contents(__DIR__ . "/../../data/statuses.json", json_encode($this->contentJson));
   }
   
   private function loadData()
   {
       $contentNoParsed = json_decode(file_get_contents(__DIR__ . "/../../data/statuses.json", true));
       $arrayStatuses = [];
       if (!is_null($contentNoParsed))
       {
        foreach ($contentNoParsed as $objectStatus)
        {
            $newStatus = array();
            $newStatus["id"] = $objectStatus->id;
            $newStatus["message"] = $objectStatus->message;
            $newStatus["date"] = $objectStatus->date;
            $newStatus["nameCreator"] = $objectStatus->nameCreator;
            $newStatus["senderClient"] = $objectStatus->senderClient;
            
            array_push($arrayStatuses, $newStatus);
        }
       }
        $this->contentJson = $arrayStatuses;
        
        
   }
}
