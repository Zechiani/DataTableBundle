<?php

namespace Zechiani\DataTableBundle\Service\Builder;

use Zechiani\DataTableBundle\Service\Loader\DataTableConfigurationLoader;
use Zechiani\DataTableBundle\Model\Configuration\Column\ConfigurationColumnBag;
use Doctrine\ORM\QueryBuilder;
use Zechiani\DataTableBundle\Model\Response\DataTableResponse;
use Zechiani\DataTableBundle\Model\Configuration\DataTableConfiguration;

/**
 * @name zechiani_data_table.builder
 */
class DataTableBuilder
{
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
    public function build(DataTableConfiguration $configuration, QueryBuilder $qb)
    {        
        $alias = $qb->getRootAliases();
        $alias = array_shift($alias);
        
        $total = $this->getTotal($qb, $alias);
        
        $this->addSearch($qb, $configuration->get('columns'));
        $this->addOrder($qb, $configuration->get('columns'));
        
        $filtered = $this->getTotal($qb, $alias);
        
        $this->addLimit($qb);
        $this->addOffset($qb);

        return new DataTableResponse($this->loader->getRequest(), $configuration, $total, $filtered, $qb->getQuery()->getArrayResult());
    }
    
    protected function getTotal(QueryBuilder $qb, $alias)
    {
        $qb = clone $qb;
        
        $qb->select("COUNT($alias.id)");
        $qb->setMaxResults(1);
    
        return $qb->getQuery()->getSingleScalarResult();
    }
    
    protected function addSearch(QueryBuilder $qb, ConfigurationColumnBag $columns)
    {
        $or = $qb->expr()->orX();
        
        foreach ($this->loader->getRequest()->getSearchColumns($columns) as $column => $value) {
            $or->add($qb->expr()->like($column, $qb->expr()->literal(sprintf('%%%s%%', $value))));            
        }
        
        $qb->andWhere($or);
    }
    
    protected function addOrder(QueryBuilder $qb, ConfigurationColumnBag $columns)
    {
        foreach ($this->loader->getRequest()->getSortColumns($columns) as $column => $order) {
            $qb->addOrderBy($column, $order);
        }   
    }
    
    protected function addLimit(QueryBuilder $qb)
    {
        if ($this->loader->getRequest()->get('length') > 0) {
            $qb->setMaxResults($this->loader->getRequest()->get('length'));
        }
    }

    protected function addOffset(QueryBuilder $qb)
    {
        if ($this->loader->getRequest()->get('start') > 0) {
            $qb->setFirstResult($this->loader->getRequest()->get('start'));
        }
    }
}