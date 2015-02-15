<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Model;

use DateTime;
use Exception\UnexistStatusException;
use Model\Criteria;
use Model\Connection;

/**
 * Description of MySqlFinder
 *
 * @author stnunes
 */
class StatusesDatabaseFinder implements StatusesFinderInterface {
    
    /** 
    * Instance du StatusMapper */
    private $connection;
    
    /**
    * Constructeur du Finder de status
    */
   public function __construct(Connection $connection)
   {
      $this->connection = $connection; 
   }
    
    public function findAll(Criteria $criteria = null) {
        
        $query = "SELECT * FROM Statuses";
        if ($criteria !== null) {
            
            $query = $this->manageCriteria($criteria, $query);
        }

        $result = $this->connection->queryData($query);
        
        $arrayWithAllStatuses = [];
        foreach ($result as $arrayStatus)
        {
            $status = new Status($arrayStatus["id"],
                    $arrayStatus["message"], new DateTime($arrayStatus["dateStatus"]),
                    $arrayStatus["nameCreator"], $arrayStatus["senderClient"]
                    );
            
            
            array_push($arrayWithAllStatuses, $status);
        }
        return $arrayWithAllStatuses;
        
        
    }

    public function findOneById($idParameter) {
        
        $query = "SELECT * FROM Statuses WHERE id=" . $idParameter;
        $result = $this->connection->queryData($query);
               
        
        $arrayStatus = $result;
        if (count($arrayStatus) == 1)
        {
            $status = new Status($arrayStatus[0]["id"],
                $arrayStatus[0]["message"], new DateTime($arrayStatus[0]["dateStatus"]),
                $arrayStatus[0]["nameCreator"], $arrayStatus[0]["senderClient"]
            );
            return $status; 
        }
        else
        {
            throw new UnexistStatusException();
        }
    }
    
    public function findNextIdAvailable()
    {
        $query = "SELECT MAX(id) FROM Statuses ";
        $result = $this->connection->queryData($query);
        return $result[0]["MAX(id)"] + 1;
    }
    
    private function manageCriteria(Criteria $criteria, $query) {
        
        if (null !== $criteria->getWhere())
        {
            $query .= " WHERE nameCreator='" . $criteria->getWhere() ."'";
        }
        if (null !== $criteria->getOrderColumn() && null !== $criteria->getOrder())
        {
            $query .= " ORDER BY " . $criteria->getOrderColumn() . " " . $criteria->getOrder();
        }

        if (null !== $criteria->getLimit() && null !== $criteria->getOffSet())
        {
            $query .= " LIMIT " . $criteria->getOffSet() . ", " . $criteria->getLimit();
        }
        return $query;
    }

//put your code here
}
