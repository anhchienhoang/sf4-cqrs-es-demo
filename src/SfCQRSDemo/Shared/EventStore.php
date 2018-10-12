<?php

namespace SfCQRSDemo\Shared;

interface EventStore
{
    public function append(DomainEvents $events);

    public function get(AggregateId $aggregateId): DomainEventsHistory;
}
