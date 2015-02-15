<?php

namespace Controller;

use Exception\UnexistStatusException;
use Exception\HttpParameterNotFound;
use Model\Status;
use Model\StatusMapperInterface;
use Model\StatusesMapperDatabase;
use Model\StatusesInMemoryFinder;
use Model\StatusesDatabaseFinder;
use Model\Connection;
use Exception\HttpException;
use DateTime;
use Http\Request;
use Model\Criteria;
/**
 * Contrôleur pour les requêtes avec le verbe GET
 */
class ControllerStatuses {
    

    private $mapper;
    
    private $finder;
    
    public function __construct(Connection $connection) 
    {
        $this->mapper = new StatusesMapperDatabase($connection);
        $this->finder = new StatusesDatabaseFinder($connection);
    }
    
    public function getStatuses(Request $request) {
        
        $limit = $request->getParameter("limit");
        $offset = $request->getParameter("offset");
        $order = $request->getParameter("order");
        $orderColumn = $request->getParameter("orderColumn");
        $where = $request->getParameter("where");
                
        $statuses = $this->finder->findAll(new Criteria($limit, $offset, $order, $where, $orderColumn));
        return $statuses;
    }
    
    public function getStatusById($idStatuses) {
        $statusFinded = null;
        try 
        {
            $statusFinded = $this->finder->findOneById($idStatuses);
        } catch (UnexistStatusException $use)
        {
            throw new HttpException(404, 'Status not found');
        }
        
        return $statusFinded;
    }
    
    public function postStatus($username, $message)
    {
        $newId = $this->finder->findNextIdAvailable();
        $this->mapper->persist(new Status($newId,
                $message, new DateTime(), $username, "Desktop"));
    }
    
    public function deleteStatus($idStatuses)
    {
        try 
        {
            $this->finder->findOneById($idStatuses);
        } catch (UnexistStatusException $use)
        {
            throw new HttpException(404, 'Status not found');
        }
        $this->mapper->remove($idStatuses);
    }
    

}