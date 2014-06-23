<?php

namespace Zechiani\DataTableBundle\Model\Response;

use Zechiani\DataTableBundle\Model\Request\DataTableRequest;
use Zechiani\DataTableBundle\Model\DataTableParameterBag;
use Zechiani\DataTableBundle\Model\Configuration\DataTableConfiguration;

class DataTableResponse extends DataTableParameterBag
{   
    public function __construct(DataTableRequest $request, DataTableConfiguration $configuration, $total, $filtered, array $data = array())
    {
        $keys = array();
        
        foreach ($configuration->get('columns') as $column) {
            $keys[] = $column->get('data');
        }

        $result = array();
        
        foreach ($data as $item) {
            $result[] = array_intersect_key($item, array_flip($keys));
        }

        $this->set('draw', $request->get('draw'));
        $this->set('recordsTotal', $total);
        $this->set('recordsFiltered', $filtered);
        $this->set('data', $result);
    }
}