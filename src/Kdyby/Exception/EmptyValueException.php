<?php

namespace Kdyby\Exception;

use Doctrine;

class EmptyValueException extends DBALException
{

    /**
     * @var string
     */
    public $column;



    /**
     * @param \Exception $previous
     * @param string $column
     * @param string $query
     * @param array $params
     * @param \Doctrine\DBAL\Connection $connection
     */
    public function __construct($previous, $column = NULL, $query = NULL, $params = [], Doctrine\DBAL\Connection $connection = NULL)
    {
        parent::__construct($previous, $query, $params, $connection);
        $this->column = $column;
    }



    /**
     * @return array
     */
    public function __sleep()
    {
        return array_merge(parent::__sleep(), ['column']);
    }

}
