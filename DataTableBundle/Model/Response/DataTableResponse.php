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
            $value = $column->get('data');
            
            if ($value == null) {
                continue;
            }
            
            $keys[] = $value;
        }

        $result = array();
        
        $callbackList = $configuration->get('columns')->getCallbackList();
        $modifierList = $configuration->get('columns')->getModifierList();
        
        foreach ($data as $item) {
            $item = array_intersect_key($item, array_flip($keys));
            
            foreach ($modifierList as $key => $modifier) {
                $item = $modifier->doModify($item);
            }

            foreach ($callbackList as $key => $callback) {
                $item[$key] = $callback->doCallback($item);
            }
            
            $result[] = $item;
        }

        $this->set('draw', $request->get('draw'));
        $this->set('recordsTotal', $total);
        $this->set('recordsFiltered', $filtered);
        $this->set('data', $result);
    }
}