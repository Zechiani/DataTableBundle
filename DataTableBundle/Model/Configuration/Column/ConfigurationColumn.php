<?php

namespace Zechiani\DataTableBundle\Model\Configuration\Column;

use Zechiani\DataTableBundle\Model\DataTableParameterBag;

class ConfigurationColumn extends DataTableParameterBag
{   
    /**
     * @var \Closure
     */
    protected $callback;
    
    /**
     * @var \Closure
     */
    protected $modifier;
    
    /**
     * @var string
     */
    protected $orderByColumn;
    
    public function __construct(DataTableParameterBag $request)
    {
        $this->set('cellType', $request->get('cellType', 'td'));
        $this->set('className', $request->get('className'));
        $this->set('contentPadding', $request->get('contentPadding'));
        $this->set('createdCell', $request->get('createdCell'));
        $this->set('data', $request->get('data'));
        $this->set('name', $request->get('name'));
        $this->set('orderable', $request->get('orderable', true) === true);
        $this->set('orderData', $request->get('orderData'));
        $this->set('orderDataType', $request->get('orderDataType'));
        $this->set('render', $request->get('render'));
        $this->set('searchable', $request->get('searchable', true) === true);
        $this->set('title', $request->get('title'));
        $this->set('type', $request->get('type'));
        $this->set('visible', $request->get('visible', true) === true);
        $this->set('width', $request->get('width'));
        $this->set('createdCell', $request->get('createdCell'));
        
        if (($callback = $request->get('callback', null)) instanceof \Closure) {
            $this->setCallback($callback);
        }
        
        if (($modifier = $request->get('modifier', null)) instanceof \Closure) {
            $this->setModifier($modifier);
        }
        
        if (($orderByColumn = $request->get('orderByColumn', null)) !== null) {
            $this->setOrderByColumn($orderByColumn);
            
        } else {
            $this->setOrderByColumn($this->get('name'));
        }
    }

    public function setCallback(\Closure $callback)
    {
        $this->callback = $callback;
    }
    
    public function getCallback()
    {
        return $this->callback;
    }
    
    public function doCallback(array $item)
    {
        return call_user_func($this->callback, $item);
    }
    
    public function setModifier(\Closure $modifier)
    {
        $this->modifier = $modifier;
    }
    
    public function getModifier()
    {
        return $this->modifier;
    }
    
    public function doModify($item, $data)
    {
        return call_user_func($this->modifier, $item, $data);
    }
    
    public function setOrderByColumn($orderByColumn)
    {
        $this->orderByColumn = $orderByColumn;
    }
    
    public function getOrderByColumn()
    {
        return $this->orderByColumn;
    }
}