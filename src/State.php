<?php declare(strict_types=1);

namespace WyriHaximus\Recoil;

use Rx\Subject\Subject;

final class State extends Subject
{
    public const CREATED = 0;
    public const STARTED = 1;
    public const WAITING = 2;
    public const BUSY    = 3;
    public const DONE    = 4;
}
