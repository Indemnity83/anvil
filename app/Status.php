<?php

namespace App;

use InvalidArgumentException;
use ReflectionClass;

abstract class Status
{
    const Installing = 'installing';
    const Installed = 'installed';

    public static function validate($status)
    {
        $reflection = new ReflectionClass(__CLASS__);
        if (! in_array($status, $reflection->getConstants())) {
            throw new InvalidArgumentException($status.' is not a valid status');
        }
    }
}
