<?php

namespace SfCQRSDemo\Shared;

use Ramsey\Uuid\Uuid;

class UuidGenerator
{
    public static function generate(): string
    {
        return Uuid::uuid4()->toString();
    }
}
