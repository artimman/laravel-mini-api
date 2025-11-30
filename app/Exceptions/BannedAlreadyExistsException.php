<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class BannedAlreadyExistsException extends Exception
{
    protected $message = 'Pokemon is already banned.';
}
