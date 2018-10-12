<?php

namespace SfCQRSDemo\Infrastructure\Serializer;

use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;

class SerializerFactory
{
    private $cacheDir;

    private $debug;

    private $metadataDir;

    public function __construct($cacheDir, $debug, $metadataDir)
    {
        $this->cacheDir = $cacheDir;
        $this->debug = $debug;
        $this->metadataDir = $metadataDir;
    }

    /**
     * @return Serializer
     */
    public function create(): Serializer
    {
        $builder = SerializerBuilder::create();

        $builder->setCacheDir($this->cacheDir)
            ->setDebug($this->debug)
            ->addMetadataDir($this->metadataDir);

        return $builder->build();
    }
}
