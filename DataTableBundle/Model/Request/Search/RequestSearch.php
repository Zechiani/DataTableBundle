<?php

namespace Zechiani\DataTableBundle\Model\Request\Search;

use Zechiani\DataTableBundle\Model\DataTableParameterBag;

class RequestSearch extends DataTableParameterBag
{   
    public function __construct(DataTableParameterBag $request)
    {
        $search = $request->get('search', array());
        $search = is_array($search) ? $search : array();
        $search = new DataTableParameterBag($search);
        
        $this->set('value', $search->get('value'));
        $this->set('regex', $search->get('regex', 'false') === 'true');
    }
}