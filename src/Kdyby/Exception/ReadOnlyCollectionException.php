<?php

namespace Kdyby\Exception;

use Doctrine;

class ReadOnlyCollectionException extends NotSupportedException
{
    /**
     * @throws ReadOnlyCollectionException
     */
    public static function invalidAccess($what)
    {
        return new static('Could not ' . $what . ' read-only collection, write/modify operations are forbidden.');
    }
}