<?php

namespace Raketa\BackendTestTask\Application\Exception;

use Throwable;

class CartPersistenceException extends \Exception
{
    public function __construct(string $message = "Failed to save Cart", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}