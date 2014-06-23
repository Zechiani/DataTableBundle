<?php

namespace Zechiani\DataTableBundle\Service\Loader;

use Zechiani\DataTableBundle\Model\Request\DataTableRequest;
use Zechiani\DataTableBundle\Model\Configuration\DataTableConfiguration;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Zechiani\DataTableBundle\Model\Configuration\Column\ConfigurationColumnBag;
use Symfony\Component\HttpFoundation\Request;

class DataTableConfigurationLoader
{
    /**
     * @var array
     */
    protected $options = array();

    /**
     * @var \Zechiani\DataTableBundle\Model\Request\DataTableRequest
     */
    protected $request;
    
    /**
     * @var \Zechiani\DataTableBundle\Model\Configuration\DataTableConfiguration
     */
    protected $configuration;
    
    /**
     * @var array
     */
    protected $template = array();
    
    /**
     * @var boolean
     */
    protected $debug = false;
    
    public function __construct(ContainerInterface $container)
    {
        $this->options = $container->getParameter('zechiani_data_table.configuration');
        $this->template = $container->getParameter('zechiani_data_table.templating');
        $this->debug = $container->getParameter('zechiani_data_table.debug');
        
        $request = $container->get('request_stack')->getCurrentRequest();
        $request = $request ? $request : new Request();
        
        $this->request = new DataTableRequest($request);
    }
    
    /**
     * @param string $configuration
     * @param string $columns
     * @return \Zechiani\DataTableBundle\Model\Configuration\DataTableConfiguration
     */
    public function load($configuration = 'default', $columns = null)
    {   
        if (is_string($columns)) {
            $extra = array('columns' => $this->options[$columns]['columns']);

        } else {
            $extra = array();
        }

        $configuration = new DataTableConfiguration($this->options[$configuration]['options'] + $extra);

        if ($columns instanceof ConfigurationColumnBag) {
            $configuration->set('columns', $columns);
        }
        
        return $this->configuration = $configuration;
    }
    
    /**
     * @return \Zechiani\DataTableBundle\Model\Configuration\DataTableConfiguration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }
    
    /**
     * @return \Zechiani\DataTableBundle\Model\Request\DataTableRequest
     */
    public function getRequest()
    {
        return $this->request;
    }
    
    public function getTemplate($name)
    {
        return $this->template[$name];
    }
}