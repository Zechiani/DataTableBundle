<?php

namespace Zechiani\DataTableBundle\Model\Request\Column;

use Zechiani\DataTableBundle\Model\DataTableParameterBag;

class RequestColumnBag extends DataTableParameterBag
{   
    public function __construct(DataTableParameterBag $request)
    {
        $columnBag = $request->get('columns', array());
        $columnBag = is_array($columnBag) ? $columnBag : array();
        
        foreach ($columnBag as $i => $column) {
            $this->addColumn($column, $i);
        }
    }
    
    public function addColumn(array $column, $key = null)
    {
        $key = $key ? $key : $this->count();
    
        $this->set($key, new RequestColumn(new DataTableParameterBag($column)));
    }

}