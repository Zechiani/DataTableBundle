<?php

namespace Zechiani\DataTableBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('zechiani_data_table');
        $rootNode
            ->canBeEnabled()
            ->children()
                ->booleanNode('enabled')->defaultTrue()->end()
                ->scalarNode('templating')
                    ->defaultValue(array('table' => 'ZechianiDataTableBundle::table.html.twig', 'javascript' => 'ZechianiDataTableBundle::javascript.html.twig'))
                ->end()
                ->booleanNode('debug')->defaultValue('%kernel.debug%')->end()
                ->scalarNode('configuration')->defaultValue(array(
                    'default' => array(
                        'options' => array(
                            'id' => 'dataTable',
                            'ordering' => 'false',
                            'paging' => 'true',
                            'processing' => 'false',
                            'searching' => 'true',
                            'serverSide' => 'false'
                        ),
                        'columns' => array()
                    )
                ))->end()
            ->end()
        ;

        return $treeBuilder;
    }
    
   
}
