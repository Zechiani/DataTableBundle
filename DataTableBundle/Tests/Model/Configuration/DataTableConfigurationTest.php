<?php

namespace Zechiani\DataTableBundle\Tests\Model\Configuration;

use Zechiani\DataTableBundle\Model\Configuration\DataTableConfiguration;
use Zechiani\DataTableBundle\Service\Configuration\ParameterProcessor;
use Zechiani\DataTableBundle\Model\DataTableParameterBag;

class DataTableConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function createDataTableConfiguration()
    {
        $dtConfiguration = new DataTableConfiguration();
        $dtConfiguration->clear();
        
        return array(array($dtConfiguration));
    }
    
    /**
     * @dataProvider createDataTableConfiguration
     */
    public function testConvertToString(DataTableConfiguration $dtConfiguration)
    {
        $dtConfiguration->set('key_true', true);
        $dtConfiguration->set('key_false', false);
        $dtConfiguration->set('key_array', new DataTableParameterBag(array('key_1' => 'a', 'key_2' => 'b', 'key_3' => array('key_3_1' => 1))));
        $dtConfiguration->set('another_key', 'value');

        $this->assertEquals('{"key_true":true,"key_false":false,"key_array":{"key_1":"a","key_2":"b","key_3":{"key_3_1":1}},"another_key":"value"}', (string) $dtConfiguration);
    }
    
    /**
     * @dataProvider createDataTableConfiguration
     */
    public function testShouldNotEspaceJavascriptFunction(DataTableConfiguration $dtConfiguration)
    {
        $dtConfiguration->set('function', 'function(){}');

        $this->assertEquals('{"function":function(){}}', (string) $dtConfiguration);
    }
    
    /**
     * @dataProvider createDataTableConfiguration
     */
    public function testShouldRemoveNull(DataTableConfiguration $dtConfiguration)
    {
        $dtConfiguration->set('key_false', false);
        $dtConfiguration->set('key_null', null);
        $dtConfiguration->set('key_array', array('key_1' => 'a', 'key_2' => null, 'key_3' => array('key_3_1' => null)));
    
        $this->assertEquals('{"key_false":false,"key_array":{"key_1":"a","key_3":[]}}', (string) $dtConfiguration);
    }
    
    /**
     * @dataProvider createDataTableConfiguration
     */
    public function testShouldNotEscapeJavascriptArray(DataTableConfiguration $dtConfiguration)
    {
        $dtConfiguration->set('array', '[["A", 1, 2], ["B", 2, 3]]');
        $dtConfiguration->set('array_new_line', '
            [
                ["A", 1, 2],
                ["B", 2, 3]
            ]
        ');
        
        $this->assertEquals('{"array":[["A", 1, 2], ["B", 2, 3]],"array_new_line":
            [
                ["A", 1, 2],
                ["B", 2, 3]
            ]
        }', (string) $dtConfiguration);
    }
    
    /**
     * @dataProvider createDataTableConfiguration
     */
    public function testShouldNotEscapeJavascriptObject(DataTableConfiguration $dtConfiguration)
    {
        $dtConfiguration->set('object', '{a:1, b:2}');
        $dtConfiguration->set('object_new_line', '
            [
                {"a":1, "b": 2},
                {"c":3, "d": 4}
            ]
        ');
    
        $this->assertEquals('{"object":"{a:1, b:2}","object_new_line":
            [
                {"a":1, "b": 2},
                {"c":3, "d": 4}
            ]
        }', (string) $dtConfiguration);
    }
}
