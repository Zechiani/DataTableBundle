<?php

namespace Zechiani\DataTableBundle\Service\Generator;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Zechiani\DataTableBundle\Model\Configuration\Column\ConfigurationColumnBag;
use Zechiani\DataTableBundle\Model\Configuration\DataTableConfiguration;

abstract class DataTableGenerator implements InterfaceDataTableGenerator
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    /**
     * @return null | Zechiani\DataTableBundle\Model\Configuration\Column\ConfigurationColumnBag
     */
    abstract public function getConfigurationColumnBag();
    
    /**
     * @param string $name
     * @param ConfigurationColumnBag $columnBag
     * 
     * @return \Zechiani\DataTableBundle\Model\Configuration\DataTableConfiguration
     */
    public function load()
    {
        $loader = $this->container->get('zechiani_data_table.configuration_loader');
        
        $configuration = $this->updateDataTableConfiguration($loader->load($this->getConfigurationName(), $this->getConfigurationColumnBag()));
        
        $loader->setConfiguration($configuration);
        
        return $configuration;
    }
    
    
    /**
     * @return \Zechiani\DataTableBundle\Model\Fetcher\FetcherInterface
     */
    abstract public function getFetcher();
    
    /**
     * @return string
     */
    public function getConfigurationName()
    {
        return 'default';
    }
    
    /**
     * @return DataTableConfiguration $configuration
     */
    public function updateDataTableConfiguration(DataTableConfiguration $configuration)
    {
        return $configuration;
    }
    
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getResponse()
    {
        $configuration = $this->load();

        $response = $this->container->get('zechiani_data_table.builder')->build($configuration, $this->getFetcher());
        
        return new Response($response, Response::HTTP_OK, array('Content-Type' => 'application/json'));
    }
}
