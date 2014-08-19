<?php

namespace Zechiani\DataTableBundle\Service\Builder;

use Zechiani\DataTableBundle\Service\Loader\DataTableConfigurationLoader;
use Zechiani\DataTableBundle\Model\Configuration\Column\ConfigurationColumnBag;
use Zechiani\DataTableBundle\Model\Response\DataTableResponse;
use Zechiani\DataTableBundle\Model\Configuration\DataTableConfiguration;
use Zechiani\DataTableBundle\Model\Fetcher\FetcherInterface;

/**
 * @name zechiani_data_table.builder
 */
class DataTableBuilder
{
    /**
     * @var \Zechiani\DataTableBundle\Service\Loader\DataTableConfigurationLoader
     */
    protected $loader;
    
    /**
     * @var \Zechiani\DataTableBundle\Model\Configuration\DataTableConfiguration
     */
    protected $configuration;
    
    public function __construct(DataTableConfigurationLoader $loader)
    {
        $this->loader = $loader;
    }
    
    /**
     * @param QueryBuilder $qb
     * @return \Zechiani\DataTableBundle\Model\Response\DataTableResponse
     */
    public function build(DataTableConfiguration $configuration, FetcherInterface $fetcher)
    {        
        $total = $fetcher->getTotal();
        
        $fetcher->addSearch($this->loader->getRequest()->getSearchColumns($configuration->get('columns')));
        $fetcher->addOrder($this->loader->getRequest()->getSortColumns($configuration->get('columns')));
                
        $filtered = $fetcher->getTotal();
        
        $fetcher->addLimit($this->loader->getRequest()->get('length'));
        $fetcher->addOffset($this->loader->getRequest()->get('start'));

        return new DataTableResponse($this->loader->getRequest(), $configuration, $total, $filtered, $fetcher->fetch());
    }
}