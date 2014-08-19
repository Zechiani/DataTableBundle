<?php

namespace Zechiani\DataTableBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Zechiani\DataTableBundle\Model\Configuration\Column\ConfigurationColumnBag;
use Symfony\Component\HttpFoundation\Response;
use Zechiani\DataTableBundle\Model\Fetcher\QueryBuilderFetcher;

class DemoController extends Controller
{
    public function indexAction()
    {   
        $columnBag = new ConfigurationColumnBag();
        $columnBag->addColumn(array('title' => 'ID', 'data' => 'id', 'name' => 'demo.id'));
        $columnBag->addColumn(array('title' => 'Code', 'data' => 'code', 'name' => 'demo.code'));
        $columnBag->addColumn(array('title' => 'Name', 'data' => 'name', 'name' => 'demo.name'));

        $configuration = $this->get('zechiani_data_table.configuration_loader')->load('default', $columnBag);
        
        $configuration->set('processing', true);
        $configuration->set('serverSide', true);
        $configuration->set('ordering', true);
        $configuration->set('ajax', $this->generateUrl('demo_zechiani_data_table_ajax'));

        return $this->render('ZechianiDataTableBundle::index.html.twig');
    }
    
    
    public function ajaxAction()
    {
        $columnBag = new ConfigurationColumnBag();
        $columnBag->addColumn(array('title' => 'ID', 'data' => 'id', 'name' => 'demo.id'));
        $columnBag->addColumn(array('title' => 'Code', 'data' => 'code', 'name' => 'demo.code'));
        $columnBag->addColumn(array('title' => 'Name', 'data' => 'name', 'name' => 'demo.name'));
    
        $configuration = $this->container->get('zechiani_data_table.configuration_loader')->load('default', $columnBag);
        
        $fetcher = new QueryBuilderFetcher($this->getDoctrine()->getManager()->getRepository('ZechianiDataTableBundle:Demo')->createQueryBuilder('demo'));
        
        $response = $this->get('zechiani_data_table.builder')->build($configuration, $fetcher);
    
        return new Response($response, 200, array('Content-Type' => 'application/json'));
    }
}
