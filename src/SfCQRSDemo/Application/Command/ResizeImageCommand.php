<?php

namespace SfCQRSDemo\Application\Command;

class ResizeImageCommand
{
    /**
     * @var string
     */
    private $image;

    /**
     * @param string $image
     */
    public function __construct(string $image)
    {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }
}
