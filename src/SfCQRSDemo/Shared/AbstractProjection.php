<?php

namespace SfCQRSDemo\Shared;

abstract class AbstractProjection implements Projection
{
    public function project(DomainEvents $events)
    {
        foreach ($events as $event) {
            $method = 'projectWhen'.ClassNameHelper::getShortClassName(get_class($event));
            $this->$method($event);
        }
    }
}
