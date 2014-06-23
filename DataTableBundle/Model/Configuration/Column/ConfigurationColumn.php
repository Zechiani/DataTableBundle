<?php

namespace Zechiani\DataTableBundle\Model\Configuration\Column;

use Zechiani\DataTableBundle\Model\DataTableParameterBag;

class ConfigurationColumn extends DataTableParameterBag
{   
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
        $this->set('width', $request->get('type'));
        $this->set('createdCell', $request->get('createdCell'));
    }

}