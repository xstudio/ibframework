<?php

/**
 * Active Record a simple ORM model
 *
 * @version 1.0
 * @date 13/12/19
 * @author yueqian.sinaapp.com
 */
class ActiveRecord
{
    /**
     * child model instance
     */
    private static $_models=array();
    /**
     * DbCommand instance
     */
    private $_cmd;
    /**
     * ar attributes key is table column name
     */
    private $_attributes=array();
    /**
     * table primary key
     */
    private $_primaryKey;

    public function __construct()
    {
        $this->_cmd=IB::app()->db->createCommand();
        $this->setAttributes();
    }
    /**
     * get ar instance
     */
    public static function model($className=__CLASS__)
    {
        if(isset(self::$_models[$className]))
            return self::$_models[$className];
        else
            return self::$_models[$className]=new $className;
    }
    /**
     * get table name default className
     * if you want to change it, child class must override this function
     */
    public function tableName()
    {
        return strtolower(get_class($this));
    }
    /**
     * set object variable
     */
    public function __set($variable, $value)
    {
        $setter='set'.ucfirst($variable);
        if(isset($this->$variable))
            $this->$variable=$value;
        elseif(isset($this->_attributes[$variable]))
            $this->_attributes[$variable]=$value;
        elseif(method_exists($this, $setter))
            $this->$setter($value);
    }
    /**
     * get object variable
     * @return function getVariable if exists this function
     * mixed variable if exists this variable name
     * attributes[variable] if exists
     * null not exists 
     */
    public function __get($variable)
    {
        $getter='get'.ucfirst($variable);
        if(isset($this->$variable))
            return $this->$variable;
        elseif(isset($this->_attributes[$variable]))
            return $this->_attributes[$variable];
        elseif(method_exists($this, $getter))
            return $this->$getter();
        else
            return null;
    }
    /**
     * _primaryKey getter
     */
    public function getPrimayKey()
    {
        return $this->_primaryKey;
    }
    /**
     * 
     */
    public function primaryKey(){}

    /**
     * _attributes getter
     */
    public function getAttributes()
    {
        if(!empty($this->_attributes))
            return $this->_attributes;
        else
            return $this->_attributes=$this->setAttributes();
    }
    /**
     * _attributes setter
     * @param array $attributes 
     * if empty $attributes fetch column from table and set _attributes key
     */
    public function setAttributes($attributes=array())
    {
        if(empty($attributes))
        {
            $columns=$this->_cmd->setSql('desc '.$this->_cmd->getCompleteTable($this->tableName()))->queryAll();
            foreach($columns as $column)
            {
                $this->_attributes[$column['Field']]='';
                if($column['Key']=='PRI')
                {
                    //primarykey  value set null for easy insert
                    $this->_attributes[$column['Field']]=null;
                    $this->_primaryKey=$column['Field'];
                }
            }
        }
        else
        {
            foreach($attributes as $key=>$value)
                if(isset($this->_attributes[$key]) || $key==$this->_primaryKey)
                    $this->_attributes[$key]=$value;
        }
    }

    /**
     * save _attributes to db
     */
    public function save()
    {
        return $this->_cmd->insert($this->tableName(), $this->_attributes);
    }
    /**
     * delete record from db where=$where
     * @param string $where case
     * @param array $bind $where bind array
     */
    public function delete($where='', $bind=array())
    {
        return $this->_cmd->delete($this->tableName(), $where, $bind);
    }
    /**
     * delete record from db by primaryKey
     */
    public function deleteByPk($pk='')
    {
        $bind_param=':'.$this->_primaryKey;
        return $this->_cmd->delete($this->tableName(), $this->_primaryKey.'='.$bind_param, array($bind_param=>$pk));
    }
    /**
     * update all record where=$where
     * @param array $set set case
     * @param string $where case
     * @param array $bind $where bind array
     */
    public function updateAll($set=array(), $where='', $bind=array())
    {
        return $this->_cmd->update($this->tableName(), $set, $where, $bind);
    }
    /**
     * update _attributes to db by primary key
     */
    public function update()
    {
        $bind_param=':'.$this->_primaryKey;
        $bind_value=$this->_attributes[$this->_primaryKey];
        unset($this->_attributes[$this->_primaryKey]);
        $where=$this->_primaryKey.'='.$bind_param;
        return $this->_cmd->update($this->tableName(), $this->_attributes, $where, array($bind_param=>$bind_value));
    }
    /**
     * find one record from db where=$where
     * @param string $where
     * @param array $bind
     */
    public function find($where='', $bind=array())
    {
        $result=$this->_cmd->select()->from($this->tableName())->where($where, $bind)->queryRow();
        $this->setAttributes($result);
        return $result;
    }
    /**
     * find one record from db by primarykey
     * @param string $pk primaryKey value
     */
    public function findByPk($pk='')
    {
        echo $this->_primaryKey;
        $bind_param=':'.$this->_primaryKey;
        $result=$this->_cmd->select()->from($this->tableName())
            ->where($this->_primaryKey.'='.$bind_param, array($bind_param=>$pk))->queryRow();
        $this->setAttributes($result);
        return $result;
    }
    /**
     * find all record from db where=$where
     * @param string $where
     * @param array $bind
     */
    public function findAll($where='', $bind=array())
    {   
        return $this->_cmd->select()->from($this->tableName())->where($where, $bind)->queryAll();
    }
    /**
     * find one record by attributes like order/limit/offset
     * @param array $attributes key->order/limit/offset value->param value
     * @param string $where
     * @param array $bind
     */
    public function findByAttributes($attributes=array(), $where='', $bind=array())
    {
        $result=$this->_cmd->select()->from($this->tableName())->where($where, $bind);
        foreach($attributes as $key=>$value)
            $result->$key($value);
        $result=$result->queryRow();
        $this->setAttributes($result);
        return $result;
    }
    /**
     * find all record by attributes like order/limit
     * @param array $attributes 
     * @param string $where
     * @param array $bind
     */
    public function findAllByAttributes($attributes=array(), $where='', $bind=array())
    {
        $result=$this->_cmd->select()->from($this->tableName())->where($where, $bind);
        foreach($attributes as $key=>$value)
            $result->$key($value);
        return $result->queryAll();
    }
    /**
     * get row count where=$where
     * @param string $where
     * @param array $bind
     */
    public function rowCount($where, $bind=array())
    {
        return $this->_cmd->select('COUNT(*)')->from($this->tableName())->where($where, $bind)->queryScalar();
    }
}
