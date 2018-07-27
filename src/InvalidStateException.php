<?php declare(strict_types=1);

namespace WyriHaximus\Recoil;

use Exception;

final class InvalidStateException extends Exception
{
    public static function create($state)
    {
        return new self('Invalid state: ' . $state);
    }
}
