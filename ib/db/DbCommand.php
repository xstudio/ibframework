<?php

/**
 * execute db command
 * 
 * @filesource
 * @version 1.0
 * @date 13/12/13
 * @author yueqian.sinaapp.com
 */
/**
 * PDO操作封装类，使用类中提供方法代替sql语句书写
 *
 * <code>
 * <?php
 * //请先修改配置文件中db链接，调用IB::app()->db->createCommand生成此类对象
 * IB::app()->db->createCommand()
 *  ->select('*')
 *  ->from('chat')
 *  ->where('chat_to_user=:user and id_chat<:id', array( ':id'=>3, ':user'=>'822@11.com'))
 *  ->order('id_chat desc')
 *  ->limit('1')
 *  ->queryAll();
 * 
 * $db=IB::app()->db;
 * $db->createCommand('select * from chat')->queryAll(); //通过sql语句查询
 *
 * //插入
 * $db->createCommand()->insert('chat', array(
 *      'chat_to_user'=>'2014', 
 *      'chat_from_user'=>'2013', 
 *      'chat_message'=>'up'))
 *  ); 
 *
 *  //更新chat表中id_chat=11的行
 * $db->createCommand()->update('chat', array(
 *      'chat_to_user'=>'2014', 
 *      'chat_from_user'=>'2013'
 *  ), 'id_chat=:id', array(':id'=>11)); 
 *
 * $db->createCommand()->delete('chat', 'id_chat=:id', array(':id'=>15)); //删除id_chat=15
 * </code>
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
        if(strpos($table, '{{')!==false)
            return IB::app()->db_config['prefix'].ltrim(rtrim($table, '}}'), '{{');
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
        $this->_sql.=' FROM '.$this->getCompleteTable($val);
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

        try
        {
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
            {
                $tmp=$sth->fetch();
                $r=$tmp[0];
            }
            elseif($method=='queryAll')
                $r=$sth->fetchAll();
            $this->reset();
            
            $e_info=$sth->errorInfo();
            if(is_null($e_info[2]))
                return $r;
            else
                throw new AppException('Execute SQL Error :'.$e_info[2]);
        }
        catch(AppException $e)
        {
            $this->reset();
            throw new Exception($e->getMessage());
        }

    }

    /**
     * execute update/delete/insert operation
     * @param array $bind bindParam=>bindValue
     * @return boolean execute result
     */
    public function execute($bind=array())
    {
        try
        {
            if(empty($bind))
            {
                $result=$this->_conn->exec($this->_sql);
                if($this->_conn->errorCode()!='00000')
                {
                    $e_info=$this->_conn->errorInfo();
                    throw new AppException('Execute SQL Error :'.$e_info[2]);
                }
            }
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
                if($sth->errorCode()!='00000')
                {
                    $e_info=$sth->errorInfo();
                    throw new AppException('Execute SQL Error :'.$e_info[2]);
                }
                
            }
            $this->reset();
            return $result;
        }
        catch(AppException $e)
        {
            $this->reset();
            throw new Exception($e->getMessage());
        }
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
        //echo $sql;
        //print_r($bind);
        return $this->setSql($sql)->execute($bind);
    }
    
}
