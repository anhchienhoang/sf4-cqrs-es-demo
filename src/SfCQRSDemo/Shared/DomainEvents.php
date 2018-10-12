<?php

namespace SfCQRSDemo\Shared;

use ArrayIterator;
use IteratorAggregate;

class DomainEvents implements IteratorAggregate
{
    /**
     * @var array|DomainEvent[]
     */
    private $events = [];

    /**
     * @param array $events
     */
    public function __construct(array $events)
    {
        $this->events = $events;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->events);
    }
}
