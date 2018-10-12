<?php

namespace SfCQRSDemo\Shared;

interface AggregateRepository
{
    public function add(RecordsEvents $aggregate);

    public function get(AggregateId $id): RecordsEvents;
}
