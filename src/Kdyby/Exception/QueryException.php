<?php

namespace Kdyby\Exception;

use Doctrine;

class QueryException extends \RuntimeException implements Exception
{

    /**
     * @var \Doctrine\ORM\Query
     */
    public $query;



    /**
     * @param \Exception $previous
     * @param \Doctrine\ORM\AbstractQuery $query
     * @param string $message
     */
    public function __construct($previous, Doctrine\ORM\AbstractQuery $query = NULL, $message = "")
    {
        parent::__construct($message ?: $previous->getMessage(), 0, $previous);
        $this->query = $query;
    }

}
