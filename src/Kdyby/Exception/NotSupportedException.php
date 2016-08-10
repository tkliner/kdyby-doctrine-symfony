<?php
namespace Kdyby\Exception;

use Doctrine;

interface Exception
{

}

class NotSupportedException extends \LogicException implements Exception
{

}