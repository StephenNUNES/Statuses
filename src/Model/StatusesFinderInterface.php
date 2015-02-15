<?php

namespace Model;

use Model\Criteria;

interface StatusesFinderInterface
{
    /**
     * Returns all elements.
     *
     * @return array
     */
    public function findAll(Criteria $criteria = null);

    /**
     * Retrieve an element by its id.
     *
     * @param  mixed      $id
     * @return null|mixed
     */
    public function findOneById($id);
}
