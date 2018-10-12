<?php

namespace SfCQRSDemo\Shared;

interface AggregateId
{
    public static function fromString(string $productId);

    public function __toString();

    public function equals(AggregateId $other);
}
