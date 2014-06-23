<?php

namespace Zechiani\DataTableBundle\Model\Request;

use Symfony\Component\HttpFoundation\Request;
use Zechiani\DataTableBundle\Model\DataTableParameterBag;
use Zechiani\DataTableBundle\Model\Request\Search\RequestSearch;
use Zechiani\DataTableBundle\Model\Request\Order\RequestOrderBag;
use Zechiani\DataTableBundle\Model\Request\Column\RequestColumnBag;

/**
 * @link http://datatables.net/manual/server-side
 */
class DataTableRequest
{
    /**
     * @var \Zechiani\DataTableBundle\Model\DataTableParameterBag
     */
    protected $parameters;
    
    public function __construct(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            $this->parameters = new DataTableParameterBag($request->request->all());
        
        } else {
            $this->parameters = new DataTableParameterBag($request->query->all());

        }
        
        $this->parameters->set('draw', $this->parameters->getInt('draw', 1));
        $this->parameters->set('start', $this->parameters->getInt('start', 0));
        $this->parameters->set('length', $this->parameters->getInt('length', 10));

        $this->parameters->set('search', new RequestSearch($this->parameters));
        $this->parameters->set('order', new RequestOrderBag($this->parameters));
        $this->parameters->set('columns', new RequestColumnBag($this->parameters));
    }
    
    public function get($key)
    {
        return $this->parameters->get($key);
    }
    
    public function getSearchColumns()
    {
        $search = array();
        
        foreach ($this->get('columns') as $column) {
            if ($column->get('searchable') && $column->get('search')->get('regex') == false) {
                // specific search
                $value = trim($column->get('search')->get('value'));
                
                // global search
                $value = strlen($value) ? $value : trim($this->get('search')->get('value'));
                
                $search[$column->get('name')] = $value;
            }
        }
        
        return $search;
    }
    
    public function getSortColumns()
    {
        $sort = array();
        $columns = $this->get('columns');
        
        foreach ($this->get('order') as $order) {
            $columnKey = $order->get('column');

            $column = $columns->get($columnKey);
            
            if ($column === null) {
                continue;
            }
            
            $sort[$column->get('name')] = $order->get('dir');
        }

        return $sort;
    }
}