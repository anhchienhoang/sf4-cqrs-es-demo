<?php

namespace SfCQRSDemo\Infrastructure\Persistence;

use SfCQRSDemo\Shared\AggregateId;
use SfCQRSDemo\Shared\DomainEvents;
use SfCQRSDemo\Shared\DomainEventsHistory;

interface EventStore
{
    public function append(DomainEvents $events);

    public function get(AggregateId $aggregateId): DomainEventsHistory;
}
