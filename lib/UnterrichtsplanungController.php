<?php

namespace Unterrichtsplanung;

use Psr\Container\ContainerInterface;

class UnterrichtsplanungController
{
    /**
     * Der Konstruktor.
     *
     * @param ContainerInterface $container der Dependency Container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}
