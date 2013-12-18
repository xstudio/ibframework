<?php

/**
 * execute db command
 * 
 * @version 1.0
 * @date 13/12/13
 * @author yueqian.sinaapp.com
 */
class DbCommand
{
    /**
     * execute sql 
     */
    private $_sql='';
    /**
     * select bind array
     */
    private $_bindArr=array();
    public function __construct($conn=null, $sql=null)
    {
        $this->_conn=$conn;
        $this->_sql=$sql;
    }
    /**
     * union prefix and tablename 
     */
    public function getCompleteTable($table='')
    {
        if(strpos('{{', $table)!==false)
            return IB::app()->db_config['prefix'].$table;
        else
            return $table;
    }
    /**
     * set execute sql 
     */
    public function setSql($sql)
    {
        $this->_sql=$sql;
        return $this;
    }
    /**
     * get execute sql 
     */
    public function getSql()
    {
        return $this->_sql;
    }
    /**
     * reset private variable
     */
    public function reset()
    {
        $this->_sql='';
        $this->_bindArr=array();
    }
    /**
     * set sql select
     */
    public function select($val='*')
    {
        $this->_sql='SELECT '.$val;
        return $this;
    }
    /**
     * set sql from
     */
    public function from($val='')
    {
        $this->_sql.=' FROM '.$this->getCompeleteTable($val);
        return $this;
    }
    /**
     * set sql leftjoin
     */
    public function leftJoin($val='')
    {
        $this->_sql.=' LEFT JOIN '.$val;
        return $this;
    }
    /**
     * set sql rightjoin
     */
    public function rightJoin($val='')
    {
        $this->_sql.=' RIGHT JOIN '.$val;
        return $this;
    }
    /**
     * set sql on
     */
    public function on($val='')
    {
        $this->_sql.=' ON '.$val;
        return $this;
    }
    /**
     * set sql where
     */
    public function where($val='', $bind=array())
    {
        $this->_sql.=' WHERE '.$val;
        $this->_bindArr=$bind;
        return $this;
    }
    /**
     * set sql group by
     */
    public function group($val='')
    {
        $this->_sql.=' GROUP BY '.$val;
        return $this;
    }
    /**
     * set sql having
     */
    public function having($val='', $bind=array())
    {
        $this->_sql.=' having '.$val;
        $this->_bindArr=$bind;
        return $this;
    }
    /**
     * set sql union
     * @param string $val empty or ALL
     */
    public function union($val='')
    {
        if($val=='')
            $this->_sql.=' UNION';
        else
            $this->_sql.=' UNION ALL';
        return $this;

    }
    /**
     * set sql order by
     */
    public function order($val='')
    {
        $this->_sql.=' ORDER BY '.$val;
        return $this;
    }
    /**
     * set sql limit
     */
    public function limit($val='')
    {
        $this->_sql.=' LIMIT '.$val;
        return $this;
    }
    /**
     * set sql offset
     */
    public function offset($val='')
    {
        $this->_sql.=' OFFSET '.$val;
        return $this;
    }

    /**
     * query row 
     */
    public function queryRow($fetchMode='')
    {
        return $this->queryInternal('queryRow', $fetchMode);
    }
    /**
     * query column
     */
    public function queryColumn($fetchMode='')
    {
        return $this->queryInternal('queryColumn', $fetchMode);
    }
    /**
     * query scalar like count(*)
     */
    public function queryScalar()
    {
        return $this->queryInternal('queryScalar', 'fetch_array');
    }
    /**
     *  query all
     */
    public function queryAll($fetchMode='')
    {
        return $this->queryInternal('queryAll', $fetchMode);
    }
    /**
     * @return fetch result
     */
    private function queryInternal($method, $fetchMode)
    {
        if($fetchMode=='fetch_array')
            $mode=PDO::FETCH_NUM;
        elseif($fetchMode=='fetch_object')
            $mode=PDO::FETCH_OBJ;
        else
            $mode=PDO::FETCH_ASSOC;
        $sth=$this->_conn->prepare(trim($this->_sql));
        $sth->setFetchMode($mode);
        if(!empty($this->_bindArr))
        {
            foreach($this->_bindArr as $param=>$value)
            {
                $tmp_value=&$this->_bindArr[$param];
                //$tmp_value must be reference variable
                $sth->bindParam($param, $tmp_value, PDO::PARAM_STR);
            }
        }
        $sth->execute();
        if($method=='queryRow')
            $r=$sth->fetch();
        elseif($method=='queryColumn')
            $r=$sth->fetchColumn();
        elseif($method=='queryScalar')
            $r=$sth->fetch()[0];
        elseif($method=='queryAll')
            $r=$sth->fetchAll();
        $this->reset();
        return !empty($r) ? $r : die('Execute SQL Error : '.$sth->errorInfo()[2]);

    }

    /**
     * execute update/delete/insert operation
     * @param array $bind bindParam=>bindValue
     * @return boolean execute result
     */
    public function execute($bind=array())
    {
        if(empty($bind))
            $result=$this->_conn->exec($this->_sql);
        else
        {
            $sth=$this->_conn->prepare($this->_sql);
            if(!empty($bind))
            {
                foreach($bind as $param=>$value)
                {
                    $tmp_value=&$bind[$param];
                    //$tmp_value must be reference variable
                    $sth->bindParam($param, $tmp_value, PDO::PARAM_STR);
                }
            }
            $result=$sth->execute();
        }
        $this->reset();
        return  $result ? $result : die('Execute SQL Error : '.$sth->errorInfo()[2]);
    }    
    /**
     * execute delete sql
     * @param string $table tablename
     * @param string $where where case
     * @param array $bind bindParam=>bindValue
     * @return execute effect rows
     */
    public function delete($table='', $where='', $bind=array())
    {
        $sql='DELETE FROM '.$this->getCompleteTable($table);
        if($where!='')
            $sql.=' WHERE '.$where;
        return $this->setSql($sql)->execute($bind);
    }
    /**
     * execute update sql
     * @param string $table tablename
     * @param array $set setColum=>setValue
     * @param string $where where case
     * @param array $bind bindParam=>bindValue
     * @return execute effect rows
     */
    public function update($table='', $set=array(), $where='', $bind=array())
    {
        foreach($set as $param=>$value)
            $lines[]=$param.'='."'".$value."'";
        $sql='UPDATE '.$this->getCompleteTable($table).' SET '.implode(', ', $lines);
        if($where!='')
            $sql.=' WHERE '.$where;
        return $this->setSql($sql)->execute($bind);
    }
    /**
     * execute insert sql
     * @param string $table tablename
     * @param string $values valuesParam=>valuesValue
     * @return execute effect rows
     */
    public function insert($table='', $values=array())
    {
        $bind=array();
        $val=array();
        $colums=array_keys($values);
        foreach($values as $k=>$v)
        {
            $val[]=':'.$k;
            $bind[':'.$k]=$v;
        }
        $sql='INSERT INTO '.$this->getCompleteTable($table).'('.implode(',', $colums).')VALUES('.implode(',', $val).')';
        echo $sql;
        print_r($bind);
        return $this->setSql($sql)->execute($bind);
    }
    
}
