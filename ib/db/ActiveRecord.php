<?php

/**
 * Active Record a simple ORM model
 *
 * @filesource
 * @version 1.0
 * @date 13/12/19
 * @author yueqian.sinaapp.com
 */

/**
 * 所有ar对象的基类，子类通过集成可方便对单表进行数据的CRUD操作
 *
 * <code>
 * <?php
 * class Chat extends ActiveRecord
 * {
 *   //如果ar子类需要调用Chat::mode()查询，必须覆写此方法
 *   public static function model($className=__class__)
 *   {
 *       return parent::model($className);
 *   }
 *   //如果表名于类名首字母转换为小写后相同，则需要写此方法，{{}}，会自动连接配置文件中设置的表前缀
 *   //public function tableName()
 *   //{
 *       //return '{{chat}}';
 *   //}
 *   //如果表中没有设置主键，则需要写此方法指名主键
 *   //public function primaryKey()
 *   //{
 *       //return 'chat_to_user';
 *   //}
 * }
 *  
 * }
 * $chat=new Chat(); //实例化聊天类
 *
 * $chat->findAll('id_chats>:id', array(':id'=>3)); //查询所有id>3的数据
 * $chat->find('id_chat>4'); //不绑定数据
 *
 * Chat::model()->findAll('id_chat>:id', array(':id'=>3)); //不用实例化即可调用查询
 * Chat::model()->findByPk(4); //查询主键为4的数据
 *
 * print_r($chat->attributes); //输出$chat对象属性，及表中对应字段及当前值
 *
 * $chat->findAllByAttributes(
 *      array('order'=>'id_chat desc', 'limit'=>'2'), 'id_chat>:id', array(':id'=>5)
 * ); 
 * $chat->findByAttributes(array('order'=>'id_chat desc'), 'id_chat>:id', array(':id'=>5));
 *
 * //通过数组设置属性值
 * $chat->attributes=array('chat_to_user'=>'aaa', 'chat_from_user'=>'adaw'); 
 *
 * $chat->chat_to_user='aafwafaw'; //分别设置
 * $chat->chat_from_user='afwfa';
 * $chat->chat_created=time();
 * $chat->save(); //保存attributes到数据表中
 *
 * $chat->attributes=array(
 *  'chat_to_user'=>'a', 
 *  'chat_from_user'=>'b',
 *  'chat_message'=>'saa'
 * );
 * $chat->update(); //通过主键值更新表数据
 *
 * $chat->updateAll(array('chat_message'=>'sb'), 'id_chat in(1, 2)');
 *
 * Chat::model()->deleteByPk('a'); //删除主键为a的
 * Chat::model()->delete('id_chats>:id', array(':id'=>8)); //删除
 * </code>
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
     * table name
     */
    private $_tableName;
    /**
     * table primary key
     */
    private $_primaryKey;

    public function __construct()
    {
        $this->_tableName=strtolower(get_class($this));
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
     * if child want to change _tableName can override it 
     */
    public function primaryKey()
    {
        return $this->_primaryKey;
    }
    /**
     * if child want to change _tableName can override it 
     */
    public function tableName()
    {
        return $this->_tableName;
    }

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
                if(isset($this->_attributes[$key]) || $key==$this->primaryKey())
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
        $bind_param=':'.$this->primaryKey();
        return $this->_cmd->delete($this->tableName(), $this->primaryKey().'='.$bind_param, array($bind_param=>$pk));
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
        $bind_param=':'.$this->primaryKey();
        $bind_value=$this->_attributes[$this->primaryKey()];
        unset($this->_attributes[$this->primaryKey()]);
        $where=$this->primaryKey().'='.$bind_param;
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
        $bind_param=':'.$this->primaryKey();
        $result=$this->_cmd->select()->from($this->tableName())
            ->where($this->primaryKey().'='.$bind_param, array($bind_param=>$pk))->queryRow();
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
