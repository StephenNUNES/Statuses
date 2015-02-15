<?php

namespace Model;

use Model\Connection;
use Model\StatusesMapperInterface;

/**
 * Description of StatusesMapperDatabase
 *
 * @author stnunes
 */
class StatusesMapperDatabase implements StatusesMapperInterface {
    
    private $con;

    public function __construct(Connection $con)
    {
        $this->con = $con;
    }

    public function persist(Status $status) {
        
        $parameters = ["id" => $status->getId(), "message" => $status->getMessage(), "dateStatus" => $status->getDateStatus()->format("Y-m-d"), "nameCreator" => $status->getNameCreator(), "senderClient" => $status->getSenderClient()];
        $query = "INSERT INTO Statuses(id, message, dateStatus, nameCreator, senderClient) "
                . "VALUES(:id, :message, :dateStatus, :nameCreator, :senderClient)";
        return $this->con->executeQuery($query, $parameters);
    }

    public function remove($id) {
        $query = "DELETE FROM Statuses WHERE id=" . $id;
        return $this->con->executeQuery($query);
    }

}
