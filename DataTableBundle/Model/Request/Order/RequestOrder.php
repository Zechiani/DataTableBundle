<?php

namespace Zechiani\DataTableBundle\Model\Request\Order;

use Zechiani\DataTableBundle\Model\DataTableParameterBag;

class RequestOrder extends DataTableParameterBag
{   
    public function __construct(DataTableParameterBag $request)
    {
        $this->set('column', $request->getInt('column'));
        $this->set('dir', strtoupper($request->get('dir')) == 'ASC' ? 'ASC' : 'DESC');
    }
}