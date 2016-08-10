<?php

namespace Kdyby\Exception;

use Doctrine;

class DuplicateEntryException extends DBALException
{

    /**
     * @var array
     */
    public $columns;



    /**
     * @param \Exception $previous
     * @param array $columns
     * @param string $query
     * @param array $params
     * @param \Doctrine\DBAL\Connection $connection
     */
    public function __construct($previous, $columns = [], $query = NULL, $params = [], Doctrine\DBAL\Connection $connection = NULL)
    {
        parent::__construct($previous, $query, $params, $connection);
        $this->columns = $columns;
    }



    /**
     * @return array
     */
    public function __sleep()
    {
        return array_merge(parent::__sleep(), ['columns']);
    }

}
