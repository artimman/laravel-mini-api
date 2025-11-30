<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class ExistsInLocalException extends Exception
{
    protected $message = "Pokemon already exists in local database.";
}
