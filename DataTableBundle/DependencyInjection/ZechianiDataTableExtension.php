<?php

namespace Zechiani\DataTableBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ZechianiDataTableExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);
        
        if ($this->isConfigEnabled($container, $config)) {
            $asseticBundle = $container->getParameter('assetic.bundles');
            $asseticBundle[] = 'ZechianiDataTableBundle';

            $container->setParameter('assetic.bundles', $asseticBundle);
            
            $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
            $loader->load('services.yml');

            $container->setParameter('zechiani_data_table.debug', $config['debug']);
            $container->setParameter('zechiani_data_table.templating', $config['templating']);
            $container->setParameter('zechiani_data_table.configuration', $config['configuration']);
        }
    }
}
