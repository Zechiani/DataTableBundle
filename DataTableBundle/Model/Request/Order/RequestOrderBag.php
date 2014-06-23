<?php

namespace Zechiani\DataTableBundle\Model\Request\Order;

use Zechiani\DataTableBundle\Model\DataTableParameterBag;

class RequestOrderBag extends DataTableParameterBag
{   
    public function __construct(DataTableParameterBag $request)
    {
        $orderBag = $request->get('order', array());
        $orderBag = is_array($orderBag) ? $orderBag : array();

        foreach ($orderBag as $i => $order) {
            $this->addOrder($order, $i);
        }
    }
    
    public function addOrder(array $order, $key = null)
    {
        $key = $key ? $key : $this->count();
        
        $order = new DataTableParameterBag($order);

        $this->set($key, new RequestOrder($order));
    }
}