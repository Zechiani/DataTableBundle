<?php

namespace Zechiani\DataTableBundle\Model;

use Symfony\Component\HttpFoundation\ParameterBag;

class DataTableParameterBag extends ParameterBag
{
    protected $removeNull = true;
    
    public function clear()
    {
        $this->parameters = array();
    }
    
    protected $parameters = array();
    
    protected function recursive(&$parameters, \Closure $callback)
    {
        foreach ($parameters as $key => &$value) {
            if (is_array($value)) {
                $value = $this->recursive($value, $callback);
    
                continue;
            }
    
            $callback($parameters, $key, $value);
        }
    
        return $parameters;
    }
    
    public function __toString()
    {
        $parameters = $this->parameters;
        
        $removeNull = $this->removeNull;
    
        $this->recursive($parameters, function(&$array, $key, &$value) use ($removeNull) {
            // convert DataTableParameterBag
            $value = $value instanceof DataTableParameterBag ? (string) $value : $value;
            
            // convert DateTime
            $value = $value instanceof \DateTime ? $value->format(DATE_ATOM) : $value;
            
            $value = is_object($value) ? (string) $value : $value;

            // remove null
            if ($value === null && $removeNull) {
                unset($array[$key]);
            }
        });

        $result = json_encode($parameters, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $search = $replace = array();

        $extract = function($values) use (&$search, &$replace, &$extract) {
            foreach ($values as $key => $value) {
                if (is_array($value)) {
                    $extract($value);

                // function
                } elseif (preg_match('/^function.*/', trim($value))) {
                    $search[] = sprintf('"%s"', str_replace("\n", '\n', trim($value)));
                    $replace[] = $value;

                // 2D array or object notation
                } elseif (!empty($value) && is_string($value) && is_array(json_decode($value, true)) && json_last_error() == 0) {
                    $search[] = sprintf('"%s"', str_replace(array('"', "\n"), array('\"', '\n'), $value));
                    $replace[] = $value;
                }
            }
        };

        $extract($parameters);

        $result = str_replace($search, $replace, $result);

        return $result == '[]' ? '' : $result;
    }
}