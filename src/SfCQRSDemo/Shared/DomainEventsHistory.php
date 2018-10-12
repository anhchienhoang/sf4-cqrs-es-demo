<?php

namespace SfCQRSDemo\Shared;

use ArrayIterator;
use IteratorAggregate;

class DomainEventsHistory extends DomainEvents
{
    /**
     * @var AggregateId
     */
    private $aggregateId;

    /**
     * @param AggregateId   $aggregateId
     * @param DomainEvent[] $events
     */
    public function __construct(AggregateId $aggregateId, $events)
    {
        $this->aggregateId = $aggregateId;

        parent::__construct($events);
    }

    public function getAggregateId(): AggregateId
    {
        return $this->aggregateId;
    }
}
