<?php

namespace Zechiani\DataTableBundle\Model\Configuration\Column;

use Zechiani\DataTableBundle\Model\DataTableParameterBag;

class ConfigurationColumnBag extends DataTableParameterBag
{
    public function __construct(DataTableParameterBag $request = null)
    {
        if ($request == null) {
            return;
        }

        $columnBag = $request->get('columns', array());
        $columnBag = is_array($columnBag) ? $columnBag : array();
    
        foreach ($columnBag as $i => $column) {
            $this->addColumn($column, $i);
        }
    }
    
    public function addColumn(array $column, $key = null)
    {
        $key = $key ? $key : $this->count();
    
        $this->set($key, new ConfigurationColumn(new DataTableParameterBag($column)));
    }
    
    public function getCallbackList()
    {
        return array_filter($this->all(), function(ConfigurationColumn $column) {
            return $column->getCallback() !== null;
        });
    }

    public function getModifierList()
    {
        return array_filter($this->all(), function(ConfigurationColumn $column) {
            return $column->getModifier() !== null;
        });
    }
}