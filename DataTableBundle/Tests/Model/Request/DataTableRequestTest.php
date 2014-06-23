<?php

namespace Zechiani\DataTableBundle\Tests\Model\Request;

use Zechiani\DataTableBundle\Model\Request\DataTableRequest;
use Symfony\Component\HttpFoundation\Request;

class DataTableRequestTest extends \PHPUnit_Framework_TestCase
{
    public function testRequestShouldHaveDefaulValues()
    {
        $dtRequest = new DataTableRequest(new Request());
        
        $this->assertSame(1, $dtRequest->get('draw'));
        $this->assertSame(0, $dtRequest->get('start'));
        $this->assertSame(-1, $dtRequest->get('length'));
        
        $this->assertInstanceOf('\Zechiani\DataTableBundle\Model\Request\Search\RequestSearch', $dtRequest->get('search'));
        $this->assertNull($dtRequest->get('search')->get('value'));
        $this->assertFalse($dtRequest->get('search')->get('regex'));
        
        $this->assertInstanceOf('\Zechiani\DataTableBundle\Model\Request\Order\RequestOrderBag', $dtRequest->get('order'));
        $this->assertInstanceOf('\Zechiani\DataTableBundle\Model\Request\Column\RequestColumnBag', $dtRequest->get('columns'));
    }
    
    public function testShouldAssumeRequestParams()
    {
        $dtRequest = new DataTableRequest(new Request(array(
            'draw' => 2,
            'start' => 3,
            'length' => 4,
            'search' => array(
                'value' => '5',
                'regex' => 'false'
            ),
            'order' => array(
                array(
                    'column' => '1',
                    'dir' => 'ASC'
                ),
                array(
                    'column' => '2',
                    'dir' => 'dEsC'
                )
            ),
            'columns' => array(
                array(
                    'data' => '1',
                    'name' => '2',
                    'searchable' => 'true',
                    'orderable' => 'false',
                    'search' => array(
                        'value' => '1',
                        'regex' => 'false'
                    ),
                ),
                array(
                    'data' => '3',
                    'name' => '4',
                    'searchable' => 'false',
                    'orderable' => 'true',
                ),
            ),
        )));
        
        $this->assertSame(2, $dtRequest->get('draw'));
        $this->assertSame(3, $dtRequest->get('start'));
        $this->assertSame(4, $dtRequest->get('length'));
        $this->assertSame('5', $dtRequest->get('search')->get('value'));
        $this->assertFalse($dtRequest->get('search')->get('regex'));
        
        $order0 = $dtRequest->get('order')->get(0);
        $this->assertSame(1, $order0->get('column'));
        $this->assertSame('ASC', $order0->get('dir'));
        
        $order1 = $dtRequest->get('order')->get(1);
        $this->assertSame(2, $order1->get('column'));
        $this->assertSame('DESC', $order1->get('dir'));
        
        $column0 = $dtRequest->get('columns')->get(0);
        $this->assertSame('1', $column0->get('data'));
        $this->assertSame('2', $column0->get('name'));
        $this->assertTrue($column0->get('searchable'));
        $this->assertFalse($column0->get('orderable'));
        $this->assertSame('1', $column0->get('search')->get('value'));
        $this->assertFalse($column0->get('search')->get('regex'));
        
        $column1 = $dtRequest->get('columns')->get(1);
        $this->assertSame('3', $column1->get('data'));
        $this->assertSame('4', $column1->get('name'));
        $this->assertFalse($column1->get('searchable'));
        $this->assertTrue($column1->get('orderable'));
        $this->assertNull($column1->get('search')->get('value'));
        $this->assertFalse($column1->get('search')->get('regex'));
    }

}
