<?php

namespace Zechiani\DataTableBundle\Model\Fetcher;

interface FetcherInterface
{
    function getTotal();
    
    function addSearch(array $columns);
    
    function addOrder(array $columns);
        
    function addLimit($limit);
  
    function addOffset($offset);
    
    function fetch();
}