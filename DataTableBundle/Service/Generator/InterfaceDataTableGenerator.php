<?php

namespace Zechiani\DataTableBundle\Service\Generator;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Zechiani\DataTableBundle\Model\Configuration\Column\ConfigurationColumnBag;
use Zechiani\DataTableBundle\Model\Configuration\DataTableConfiguration;

interface InterfaceDataTableGenerator
{
    /**
     * @return null | Zechiani\DataTableBundle\Model\Configuration\Column\ConfigurationColumnBag
     */
    function getConfigurationColumnBag();
    
    /**
     * @return \Zechiani\DataTableBundle\Model\Configuration\DataTableConfiguration
     */
    function load();
    
    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    function getQueryBuilder();
    
    /**
     * @return string
     */
    function getConfigurationName();
    
    /**
     * @return DataTableConfiguration $configuration
     */
    function updateDataTableConfiguration(DataTableConfiguration $configuration);
    
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    function getResponse();
    
    
}