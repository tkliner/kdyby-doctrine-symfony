<?php

namespace Kdyby\Exception;

use Doctrine;

class DBALException extends \RuntimeException implements Exception
{

    /**
     * @var string
     */
    public $query;

    /**
     * @var array
     */
    public $params = [];

    /**
     * @var \Doctrine\DBAL\Connection
     */
    public $connection;



    /**
     * @param \Exception $previous
     * @param string $query
     * @param array $params
     * @param \Doctrine\DBAL\Connection $connection
     * @param string $message
     */
    public function __construct($previous, $query = NULL, $params = [], Doctrine\DBAL\Connection $connection = NULL, $message = NULL)
    {
        parent::__construct($message ?: $previous->getMessage(), $previous->getCode(), $previous);
        $this->query = $query;
        $this->params = $params;
        $this->connection = $connection;
    }



    /**
     * This is just a paranoia, hopes no one actually serializes exceptions.
     *
     * @return array
     */
    public function __sleep()
    {
        return ['message', 'code', 'file', 'line', 'errorInfo', 'query', 'params'];
    }

}