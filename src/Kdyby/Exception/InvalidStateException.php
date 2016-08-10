<?php

namespace Kdyby\Exception;

use Doctrine;

interface Exception
{

}

class InvalidStateException extends \RuntimeException implements Exception
{

}