<?php

namespace Zechiani\DataTableBundle\Twig;

use Zechiani\DataTableBundle\Service\Loader\DataTableConfigurationLoader;

class DataTableTwigExtension extends \Twig_Extension
{
    /**
     * @var \Zechiani\DataTableBundle\Service\Loader\DataTableConfigurationLoader
     */
    protected $loader;
    
    public function __construct(DataTableConfigurationLoader $loader)
    {
        $this->loader = $loader;
    }
    
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }
    
    public function getFunctions()
    {
        return array(
            'dataTableRenderTable' => new \Twig_Function_Method($this, 'dataTableRenderTable', array('is_safe' => array('html'))),
            'dataTableRenderJavascript' => new \Twig_Function_Method($this, 'dataTableRenderJavascript', array('is_safe' => array('html'))),
        );
    }
    
    public function dataTableRenderJavascript()
    {
        return $this->environment->render($this->loader->getTemplate('javascript'), array('configuration' => $this->loader->getConfiguration(), 'id' => $this->loader->getConfiguration()->getId()));
    }
    
    public function dataTableRenderTable()
    {
        return $this->environment->render($this->loader->getTemplate('table'), array('columns' => $this->loader->getConfiguration()->get('columns'), 'id' => $this->loader->getConfiguration()->getId()));
    }
    
	/* (non-PHPdoc)
     * @see Twig_ExtensionInterface::getName()
     */
    public function getName()
    {
        return 'zechiani_data_table_bundle_twig_extension';   
    }

}