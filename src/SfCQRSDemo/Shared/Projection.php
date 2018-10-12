<?php

namespace SfCQRSDemo\Shared;

interface Projection
{
    public function project(DomainEvents $events);
}
