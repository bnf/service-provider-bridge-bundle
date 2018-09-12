<?php

namespace TheCodingMachine\Interop\ServiceProviderBridgeBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class InteropServiceProviderBridgeBundle extends Bundle implements RegistryProviderInterface
{
    private $serviceProviders;
    private $id;

    private static $count = 0;

    /**
     * @param array $serviceProviders An array of service providers, in the format specified in thecodingmachine/service-provider-registry: https://github.com/thecodingmachine/service-provider-registry#how-does-it-work
     */
    public function __construct(array $serviceProviders = [])
    {
        $this->serviceProviders = $serviceProviders;
        $this->id = self::$count;
        self::$count++;
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new ServiceProviderCompilationPass($this->id, $this));
    }

    /**
     * At boot time, let's fill the container with the registry.
     */
    public function boot()
    {
        $registryServiceName = 'service_provider_registry_'.$this->id;
        $this->container->set($registryServiceName, $this->getRegistry($this->container));
    }

    /**
     * @param ContainerInterface $container
     * @return Registry
     */
    public function getRegistry(ContainerInterface $container)
    {
        // In parallel, let's merge the registry:
        return new Registry($this->serviceProviders);
    }
}
