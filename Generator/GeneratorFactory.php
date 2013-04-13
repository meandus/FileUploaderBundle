<?php

namespace QoopLmao\FileUploaderBundle\Generator;

use Symfony\Component\DependencyInjection\ContainerInterface;

class GeneratorFactory
{
    private $generator;

    public function __construct(ContainerInterface $container, $generator_service = null)
    {

    }
}