<?php

/**
 *
 */
class SiteController extends Controller
{

    public function index()
    {
        $this->render('index');
    }

    public function login()
    {
        //var_dump(IB::app()->db->createCommand()->getCompleteTable('adw'));
        //$this->render('login');
        //var_dump();
        //
        $db=IB::app()->db;
        echo '<pre>';
        //print_r(IB::app()->db->createCommand()->select('*')->from('chat')->where('chat_to_user=:user and id_chat<:id', array( ':id'=>3, ':user'=>'822@11.com'))->order('id_chat desc')->limit('1')->queryAll());
        //print_r($db->createCommand('select * from chat')->queryAll());
        //var_dump($db->createCommand()->insert('chat', array('chat_to_user'=>'2013Test', 'chat_from_user'=>'2013ForTest', 'chat_message'=>'2013', 'chat_created'=>time())));
        //var_dump($db->createCommand()->update('chat', array('chat_to_user'=>'2013Upd', 'chat_from_user'=>'2013ForUpd'), 'id_chat=:id', array(':id'=>11)));
        var_dump($db->createCommand()->delete('chat', 'id_chat=:id', array(':id'=>15)));
    }
    public function model()
    {
        //var_dump(Chat::model()->attributes);
        /*$chat=new Chat;
        echo '<pre>';
        print_r($chat->attributes);
        /*$chat->id_chat=5;
        $chat->chat_to_user='ar_to@a.com';
        $chat->chat_from_user='ar_from@a.com';
        $chat->chat_message='cafawf';
        $chat->chat_created=time();*/
        //$chat->attributes=array('chat_to_user'=>'aaa', 'id_chat'=>5, 'chat_from_user'=>'adaw', 'chat_message'=>'sefes', 'chat_created'=>time());
        //print_r($chat->attributes);
        //use php pdo connection
        //print_r(IB::app()->db->getConnection()->query('desc '.$this->tableName())->fetchAll());
    }
}
