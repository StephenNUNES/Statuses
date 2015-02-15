<?php

namespace Model;

class Criteria {
    
    private $where;
    
    private $order;
    
    private $limit;
    
    private $offset;
    
    private $orderColumn;
    
    public function __construct($limit = null, $offset = null, $order = null, $where = null, $orderColumn = null) {
        $this->where = $where;
        $this->order = $order;
        $this->limit = $limit;
        $this->offset = $offset;
        $this->orderColumn = $orderColumn;
    }
    
    public function getLimit() {
        return $this->limit;
    }
    
    public function getOffSet() {
        return $this->offset;
    }
    
    public function getOrder() {
        return $this->order;
    }
    
    public function getWhere() {
        return $this->where;
    }
    
    public function getOrderColumn() {
        return $this->orderColumn;
    }

}
