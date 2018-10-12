<?php

namespace SfCQRSDemo\Shared;

abstract class AggregateRoot implements RecordsEvents
{
    /**
     * @var array|DomainEvent[]
     */
    private $recordedEvents = [];

    public function getRecordedEvents(): DomainEvents
    {
        return new DomainEvents($this->recordedEvents);
    }

    public function clearRecordedEvents()
    {
        $this->recordedEvents = [];
    }

    protected function recordThat(DomainEvent $event)
    {
        $this->recordedEvents[] = $event;
    }

    protected function apply(DomainEvent $event)
    {
        $method = 'apply'.ClassNameHelper::getShortClassName(get_class($event));
        $this->$method($event);
    }

    protected function applyAndRecordThat(DomainEvent $event)
    {
        $this->apply($event);
        $this->recordThat($event);
    }

    abstract public static function reconstituteFromHistory(DomainEventsHistory $eventsHistory);
}
