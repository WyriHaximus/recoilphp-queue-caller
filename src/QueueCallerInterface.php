<?php declare(strict_types=1);

namespace WyriHaximus\Recoil;

use Rx\ObservableInterface;

interface QueueCallerInterface
{
    public function call(ObservableInterface $observable): State;
}
