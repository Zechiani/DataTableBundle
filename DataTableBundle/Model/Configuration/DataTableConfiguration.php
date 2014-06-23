<?php

namespace Zechiani\DataTableBundle\Model\Configuration;

use Zechiani\DataTableBundle\Model\DataTableParameterBag;
use Zechiani\DataTableBundle\Model\Configuration\Column\ConfigurationColumnBag;

/**
 * @link http://datatables.net/reference/option/
 */
 class DataTableConfiguration extends DataTableParameterBag
{
    /**
     * @var string
     */
    protected $id;
    
    public function __construct($configuration = array())
    {
        $configuration = new DataTableParameterBag($configuration);
        
        $this->set('autoWidth', $configuration->get('autoWidth'));
        $this->set('deferRender', (string) $configuration->get('deferRender', 'false') === 'true');
        $this->set('info', $configuration->get('info', true) === true);
        $this->set('jQueryUI', (string) $configuration->get('jQueryUI', 'false') === 'true');
        $this->set('ordering', (string) $configuration->get('ordering', 'false') === 'true');
        $this->set('paging', (string) $configuration->get('paging', 'true') === 'true');
        $this->set('processing', (string) $configuration->get('processing', 'true') === 'true');
        $this->set('scrollX', (string) $configuration->get('scrollX', 'false') === 'true');
        $this->set('scrollY', $configuration->get('scrollY'));
        $this->set('searching', (string) $configuration->get('searching', 'true') === 'true');
        $this->set('serverSide', (string) $configuration->get('serverSide', 'false') === 'true');
        $this->set('stateSave', (string) $configuration->get('stateSave', 'false') === 'true');
        $this->set('pageLength', $configuration->get('pageLength', 10));

        $this->set('columns', new ConfigurationColumnBag($configuration));
        
        $this->id = $configuration->get('id', 'dataTable');
    }
    
    public function getId()
    {
        return $this->id;
    }
}