<?php
namespace Bnf\Interop\ServiceProviderBridgeBundle\Tests\Fixtures;

use Interop\Container\ServiceProviderInterface;
use Psr\Container\ContainerInterface;

class TestServiceProviderOverride implements ServiceProviderInterface
{
    public function getFactories()
    {
        return [];
    }

    public static function overrideServiceA(ContainerInterface $container, \stdClass $serviceA = null): \stdClass
    {
        $serviceA->newProperty = 'foo';
        return $serviceA;
    }

    public function getExtensions()
    {
        return [
            'serviceA' => [ TestServiceProviderOverride::class, 'overrideServiceA' ]
        ];
    }
}
