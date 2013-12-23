<?php

class Chat extends ActiveRecord
{
    public static function model($className=__class__)
    {
        return parent::model($className);
    }
    
    /*public function tableName()
    {
        //return '{{user}}';
        return '{{chat}}';
    }
    public function primaryKey()
    {
        //return '{{user}}';
        return 'chat_to_user';
    }*/
}
