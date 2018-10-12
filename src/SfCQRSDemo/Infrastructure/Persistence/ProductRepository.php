<?php

namespace SfCQRSDemo\Infrastructure\Persistence;

use SfCQRSDemo\Model\Product\Product;
use SfCQRSDemo\Model\Product\ProductProjection;
use SfCQRSDemo\Model\Product\ProductRepository as ProductRepositoryPort;
use SfCQRSDemo\Shared\AggregateId;
use SfCQRSDemo\Shared\EventStore;
use SfCQRSDemo\Shared\RecordsEvents;

class ProductRepository implements ProductRepositoryPort
{
    /**
     * @var EventStore
     */
    private $eventStore;

    /**
     * @var ProductProjection
     */
    private $projection;

    public function __construct(EventStore $eventStore, ProductProjection $projection)
    {
        $this->eventStore = $eventStore;
        $this->projection = $projection;
    }

    public function add(RecordsEvents $aggregate)
    {
        $recordedEvents = $aggregate->getRecordedEvents();
        $this->eventStore->append($recordedEvents);
        $this->projection->project($recordedEvents);

        $aggregate->clearRecordedEvents();
    }

    public function get(AggregateId $id): RecordsEvents
    {
        $events = $this->eventStore->get($id);

        return Product::reconstituteFromHistory($events);
    }
}
