<?php

namespace Ptbfw\SlowStepDetector;

use Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Loader\XmlFileLoader,
    Symfony\Component\Config\FileLocator
;
use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Symfony\Component\DependencyInjection\Definition;
use Behat\Testwork\EventDispatcher\ServiceContainer\EventDispatcherExtension;

/**
 * SlowStepDetector
 *
 * @author Angel Koilov <angel.koilov@gmail.com>
 */
class Extension implements ExtensionInterface
{

    public function load(ContainerBuilder $container, array $config)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/services'));
        $loader->load('core.xml');
        $container->setParameter('ptbfw.slowstepdetector.parameters', $config);
        
        
        $definition = new Definition('Ptbfw\SlowStepDetector\Listener\SlowStepDetectorListener', $config);
        $definition->addTag(EventDispatcherExtension::SUBSCRIBER_TAG, array('priority' => 100));
        $container->setDefinition('ptbfw.slowstepdetector.SlowStepDetectorListener', $definition);
        
    }

    public function configure(\Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $builder)
    {
        $builder
                ->children()
                    ->integerNode('verbose')
                        ->defaultValue(0)
                    ->end()
                    ->integerNode('slowStepTime')
                        ->defaultValue(3)
                    ->end()
                ->end();
    }

    public function getConfigKey()
    {
        return 'PtbfwSlowStepDetector';
    }

    public function initialize(\Behat\Testwork\ServiceContainer\ExtensionManager $extensionManager)
    {
        
    }

    public function process(ContainerBuilder $container)
    {

    }

}
