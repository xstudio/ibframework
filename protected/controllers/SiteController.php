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
}
