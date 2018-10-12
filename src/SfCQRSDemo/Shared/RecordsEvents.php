<?php

namespace SfCQRSDemo\Shared;

interface RecordsEvents
{
    public function getRecordedEvents(): DomainEvents;

    public function clearRecordedEvents();
}
