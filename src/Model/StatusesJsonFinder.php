<?php

namespace Model;

use Exception\UnexistStatusException;
use Http\Criteria;
/**
 * Description of JsonFinder
 *
 * @author stnunes
 */
class StatusesJsonFinder implements StatusesFinderInterface {
    
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
    
    public function findAll(Criteria $criteria = null) 
    {
        $arrayWithAllStatuses = [];
        foreach ($this->statusMapper->getAllStatuses() as $arrayStatus)
        {
            $status = new Status($arrayStatus["id"],
                    $arrayStatus["message"], $arrayStatus["date"],
                    $arrayStatus["nameCreator"], $arrayStatus["senderClient"]
                    );
            
            
            array_push($arrayWithAllStatuses, $status);
        }
        return $arrayWithAllStatuses;
    }

    public function findOneById($id) 
    { 
      foreach ($this->statusMapper->getAllStatuses() as $arrayStatus)
        {
          if ($id === $arrayStatus["id"]) 
          {
            $status = new Status($arrayStatus["id"],
                $arrayStatus["message"], $arrayStatus["date"],
                $arrayStatus["nameCreator"], $arrayStatus["senderClient"]
            );
            return $status;
          }
        }
        throw new UnexistStatusException();
    }
    
    public function findLastId()
    {
        $idMax = 0;
        foreach ($this->statusMapper->getAllStatuses() as $arrayStatus)
        {
          if ($idMax <= $arrayStatus["id"]) 
          {
             $idMax = $arrayStatus["id"] + 1;
          }
        }
        return $idMax;
    }
}
