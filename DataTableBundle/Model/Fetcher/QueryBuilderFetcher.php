<?php

namespace Zechiani\DataTableBundle\Model\Fetcher;

use Doctrine\ORM\QueryBuilder;

class QueryBuilderFetcher implements FetcherInterface
{
    /**
     * @var \Doctrine\ORM\QueryBuilder
     */
    protected $qb;
    
    public function __construct(QueryBuilder $qb)
    {
        $this->qb = $qb;
    }
    
    public function getTotal()
    {
        $alias = $this->qb->getRootAliases();
        $alias = array_shift($alias);
        
        /* @var $qb \Doctrine\ORM\QueryBuilder */
        $qb = clone $this->qb;
        
        $qb->select("COUNT($alias.id)");
        $qb->setMaxResults(1);
        
        return $qb->getQuery()->getSingleScalarResult();
    }
    
    public function addSearch(array $columns)
    {
        if (count($columns) == 0) {
            return;
        } 
        
        $or = $this->qb->expr()->orX();
        
        foreach ($columns as $column => $value) {
            $or->add($this->qb->expr()->like($column, $this->qb->expr()->literal(sprintf('%%%s%%', $value))));
        }
        
        $this->qb->andWhere($or);
    }
    
    public function addOrder(array $columns)
    {
        if (count($columns) == 0) {
            return;
        }
    
        foreach ($columns as $column => $order) {
            $this->qb->addOrderBy($column, $order);
        }
    }
    
    public function addLimit($limit)
    {
        $limit = (int) $limit;
        
        if ($limit <= 0) {
            return;
        }
    
        $this->qb->setMaxResults($limit);
    }
    
    public function addOffset($offset)
    {
        $offset = (int) $offset;
        
        if ($offset <= 0) {
            return;
        }
        
        $this->qb->setFirstResult($offset);
    }
    
    public function fetch()
    {
        return $this->qb->getQuery()->getArrayResult();
    }
    
}