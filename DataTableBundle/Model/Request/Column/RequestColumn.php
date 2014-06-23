<?php

namespace Zechiani\DataTableBundle\Model\Request\Column;

use Zechiani\DataTableBundle\Model\DataTableParameterBag;
use Zechiani\DataTableBundle\Model\Request\Search\RequestSearch;

class RequestColumn extends DataTableParameterBag
{   
    public function __construct(DataTableParameterBag $request)
    {
        $this->set('data', $request->get('data', ''));
        $this->set('name', $request->get('name', ''));
        $this->set('searchable', $request->get('searchable', 'false') === 'true');
        $this->set('orderable', $request->get('orderable', 'true') === 'true');
        $this->set('search', new RequestSearch($request));
    }
}   