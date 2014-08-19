<?php

namespace Zechiani\DataTableBundle\Model\Fetcher;

class NativeQueryFetcher implements FetcherInterface
{
    /**
     * @var \PDO
     */
    protected $pdo;
    
    /**
     * @string
     */
    protected $sql;
    
    /**
     * @var null | array
     */
    protected $parameters = null;
    
    /**
     * @var array
     */
    protected $fields;
    
    public function __construct(\PDO $pdo, $sql, $fields = array())
    {
        # $sql = SELECT [FIELDS] FROM t1 WHERE 1 [WHERE] [ORDERBY] [LIMIT] [OFFSET]
        
        $this->pdo = $pdo;
        $this->sql = $sql;
        $this->fields = $fields;
    }
    
    public function getTotal()
    {
        $sql = str_replace('[FIELDS]', 'COUNT(*)', $this->sql);
        $sql = str_replace(array('[WHERE]', '[ORDERBY]', '[LIMIT]', '[OFFSET]'), '', $sql);

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->parameters);
        
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        return array_shift($result);
    }
    
    public function addSearch(array $columns)
    {
        if (count($columns) == 0) {
            $this->sql = str_replace('[WHERE]', '', $this->sql);
            return;
        }
        
        $where = array();
        $parameters = array();
        
        foreach ($columns as $column => $value) {
            if ($value == '') {
                continue;
            }
            
            $where[] = sprintf("%s RLIKE :%s", $column, $column = str_replace('.', '_', $column));
            $parameters[sprintf(':%s', $column)] = $value; 
        }
        
        if (count($where) == 0) {
            $this->sql = str_replace('[WHERE]', '', $this->sql);
            return;
        }
        
        $this->parameters = $parameters;
        $where = sprintf(' AND (%s)', implode(' OR ', array_values($where)));
        
        $this->sql = str_replace('[WHERE]', $where, $this->sql);
    }
    
    public function addOrder(array $columns)
    {
        if (count($columns) == 0) {
            $this->sql = str_replace('[ORDERBY]', '', $this->sql);
            return;
        }
    
        $orderBy = array();
        
        foreach ($columns as $column => $order) {
            $orderBy[] = sprintf('%s %s', $column, $order);
        }

        $orderBy = sprintf('ORDER BY %s', implode(', ', $orderBy));
        
        $this->sql = str_replace('[ORDERBY]', $orderBy, $this->sql);
    }
    
    public function addLimit($limit)
    {
        $limit = (int) $limit;
        
        if ($limit <= 0) {
            $this->sql = str_replace('[LIMIT]', '', $this->sql);
            $this->sql = str_replace('[OFFSET]', '', $this->sql);
            
            return;
        }
    
        $this->sql = str_replace('[LIMIT]', sprintf('LIMIT %d', $limit), $this->sql);
    }
    
    public function addOffset($offset)
    {
        $offset = (int) $offset;
        
        if ($offset <= 0) {
            $this->sql = str_replace('[OFFSET]', '', $this->sql);
            
            return;
        }
        
        $this->sql = str_replace('[OFFSET]', sprintf(', %d', $offset), $this->sql);
    }
    
    public function fetch()
    {
        $sql = str_replace('[FIELDS]', implode(', ', $this->fields), $this->sql);
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->parameters);
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
}