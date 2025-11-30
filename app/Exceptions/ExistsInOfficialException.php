<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class ExistsInOfficialException extends Exception
{
    protected $message = "Pokemon already exists in official PokeAPI.";
}
