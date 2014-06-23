<?php

namespace Zechiani\DataTableBundle\Tests\Service\Loader;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zechiani\DataTableBundle\Service\Loader\DataTableConfigurationLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class DataTableConfigurationLoaderTest extends WebTestCase
{
    public function testShouldLoadDefaultConfiguration()
    {
        $container = parent::createClient()->getKernel()->getContainer();
        
        $requestStack = new RequestStack();
        $requestStack->push(new Request());
        
        $container->set('request_stack', $requestStack);
        
        $dtBuilder = new DataTableConfigurationLoader($container);
        
        $this->assertInstanceOf('\Zechiani\DataTableBundle\Model\Configuration\DataTableConfiguration', $dtBuilder->getConfiguration());
    }
}
