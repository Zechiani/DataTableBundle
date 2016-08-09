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
            new \Twig_SimpleFunction('dataTableRenderTable', [$this, 'dataTableRenderTable'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('dataTableRenderJavascript', [$this, 'dataTableRenderJavascript'], ['is_safe' => ['html']]),
        );
    }
    
    public function dataTableRenderJavascript($id = null)
    {
        if (($configuration = $this->loader->getConfiguration($id)) !== null) {
            return $this->environment->render($this->loader->getTemplate('javascript'), array('configuration' => $configuration));
        }
    }
    
    public function dataTableRenderTable($id = null)
    {
        if (($configuration = $this->loader->getConfiguration($id)) !== null) {
            return $this->environment->render($this->loader->getTemplate('table'), array('configuration' => $configuration));
        }
    }
    
	/* (non-PHPdoc)
     * @see Twig_ExtensionInterface::getName()
     */
    public function getName()
    {
        return 'zechiani_data_table_bundle_twig_extension';   
    }

}