<?php

namespace Zechiani\DataTableBundle\Model\Configuration;

use Zechiani\DataTableBundle\Model\DataTableParameterBag;
use Zechiani\DataTableBundle\Model\Configuration\Column\ConfigurationColumnBag;
use Zechiani\DataTableBundle\Model\Configuration\Language\ConfigurationLanguage;

/**
 * @link http://datatables.net/reference/option/
 */
 class DataTableConfiguration extends DataTableParameterBag
{
    /**
     * @var array
     */
    protected $reserved = array('id' => null, 'cellpadding' => null, 'cellspacing' => null, 'border' => null, 'class' => null);

    public function __construct(array $configuration = array())
    {
        $configuration = new DataTableParameterBag($configuration);

        $this->set('autoWidth', $configuration->get('autoWidth'));
        $this->set('deferRender', (string) $configuration->get('deferRender', 'false') === 'true');
        $this->set('info', $configuration->get('info', true) === true);
        $this->set('jQueryUI', (string) $configuration->get('jQueryUI', 'false') === 'true');
        $this->set('ordering', (string) $configuration->get('ordering', 'false') === 'true');
        $this->set('paging', (string) $configuration->get('paging', 'true') === 'true');
        $this->set('processing', (string) $configuration->get('processing', 'true') === 'true');
        $this->set('scrollX', $configuration->get('scrollX'));
        $this->set('scrollY', $configuration->get('scrollY'));
        $this->set('searching', (string) $configuration->get('searching', 'true') === 'true');
        $this->set('serverSide', (string) $configuration->get('serverSide', 'false') === 'true');
        $this->set('stateSave', (string) $configuration->get('stateSave', 'false') === 'true');
        $this->set('pageLength', (int) $configuration->get('pageLength', 10));
        
        $this->set('columns', new ConfigurationColumnBag($configuration));
        $this->set('language', new ConfigurationLanguage($configuration));
        
        foreach ($this->reserved as $key => $value) {
            $this->reserved[$key] = $configuration->get($key);
        }
        
        if ($this->reserved['id'] === null) {
            $this->reserved['id'] = uniqid('DT');
        }
    }
    
    public function __call($method, $args)
    {
        $set = null;
        
        if (strpos($method, 'set') === 0) {
            $set = true;
            
        } elseif (strpos($method, 'get') === 0) {
            $set = false;
        }
        
        if ($set === null) {
            $key = $method;
            
        } else {
            $key = lcfirst(substr($method, 3, strlen($method)));
        }

        if (array_key_exists($key, $this->reserved)) {
            return $set ? $this->reserved[$key] = reset($args) : $this->reserved[$key]; 
        }
        
        return $set ? $this->set($key, array_shift($args)) : $this->get($key, array_shift($args));
    }    
}