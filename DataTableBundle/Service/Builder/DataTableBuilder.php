<?php

namespace Zechiani\DataTableBundle\Service\Builder;

use Zechiani\DataTableBundle\Service\Loader\DataTableConfigurationLoader;
use Zechiani\DataTableBundle\Model\Configuration\Column\ConfigurationColumnBag;
use Doctrine\ORM\QueryBuilder;
use Zechiani\DataTableBundle\Model\Response\DataTableResponse;

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

    public function load($configuration = 'default', ConfigurationColumnBag $columns = null)
    {
        return $this->configuration = $this->loader->load($configuration, $columns);
    }
    
    /**
     * @param QueryBuilder $qb
     * @return \Zechiani\DataTableBundle\Model\Response\DataTableResponse
     */
    public function build(QueryBuilder $qb)
    {
        $this->configuration = $this->configuration ? $this->configuration : $this->loader->load();
        
        $alias = $qb->getRootAliases();
        $alias = array_shift($alias);
        
        $total = $this->getTotal($qb, $alias);
        
        $this->addSearch($qb);
        $this->addOrder($qb);
        $this->addLimit($qb);

        $filtered = $this->getTotal($qb, $alias);
        
        $this->addOffset($qb);
        
        return new DataTableResponse($this->loader->getRequest(), $this->loader->getConfiguration(), $total, $filtered, $qb->getQuery()->getArrayResult());
    }
    
    protected function getTotal(QueryBuilder $qb, $alias)
    {
        $qb = clone $qb;
        
        $qb->select("COUNT($alias.id) a_" . uniqid());
        $qb->setMaxResults(1);
    
        return $qb->getQuery()->getSingleScalarResult();
    }
    
    protected function addSearch(QueryBuilder $qb)
    {
        foreach ($this->loader->getRequest()->getSearchColumns() as $column => $value) {
            $qb->orWhere($qb->expr()->like($column, $qb->expr()->literal(sprintf('%%%s%%', $value))));            
        }
    }
    
    protected function addOrder(QueryBuilder $qb)
    {
        foreach ($this->loader->getRequest()->getSortColumns() as $column => $order) {
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