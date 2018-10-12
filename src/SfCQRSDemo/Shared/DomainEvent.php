<?php

namespace SfCQRSDemo\Shared;

interface DomainEvent
{
    public function getAggregateId(): AggregateId;
}
