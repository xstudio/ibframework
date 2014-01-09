<?php

/**
 *
 */
class SiteController extends Controller
{

    public function index()
    {
        //IB::import('application.a.*');
        //IB::import('application.a.b.*');
        //$c=new C;
        //$m=new M;
        $this->render('index');
    }

    public function login()
    {
        //var_dump(IB::app()->db->createCommand()->getCompleteTable('adw'));
        //$this->render('login');
        //var_dump();
        //
        $db=IB::app()->db;
	var_dump($db);
        echo '<pre>';
        //print_r(IB::app()->db->createCommand()->select('*')->from('chat')->where('chat_to_user=:user and id_chat<:id', array( ':id'=>3, ':user'=>'822@11.com'))->order('id_chat desc')->limit('1')->queryAll());
        //print_r($db->createCommand('select * from chat')->queryAll());
        //var_dump($db->createCommand()->insert('chat', array('chat_to_user'=>'2013Test', 'chat_from_user'=>'2013ForTest', 'chat_message'=>'2013', 'chat_created'=>time())));
        //var_dump($db->createCommand()->update('chat', array('chat_to_user'=>'2013Upd', 'chat_from_user'=>'2013ForUpd'), 'id_chat=:id', array(':id'=>11)));
        //var_dump($db->createCommand()->delete('chat', 'id_chat=:id', array(':id'=>15)));
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
    public function ar()
    {
        echo '<pre>';
        $chat=new Chat();
        //print_r($chat->findAll('id_chats>:id', array(':id'=>3)));
        //print_r(Chat::model()->findAll('id_chat>:id', array(':id'=>3)));
        //print_r(Chat::model()->findByPk(4));
        //print_r(Chat::model()->findAll('id_chat>:id', array(':id'=>0)));
       // $chat=new Chat();
        //$chat->find('id_chat>4');
        //print_r($chat->attributes);
       // print_r($chat->findAllByAttributes(array('order'=>'id_chat desc', 'limit'=>'2'), 'id_chat>:id', array(':id'=>5)));
        //print_r($chat->findByAttributes(array('order'=>'id_chat desc'), 'id_chat>:id', array(':id'=>5)));
        //
        //$chat->attributes=array('chat_to_user'=>'aaa', 'chat_from_user'=>'adaw', 'chat_created'=>time());
        //$chat->chat_to_user='aafwafaw';
        //$chat->chat_from_user='afwfa';
        //$chat->chat_created=time();
        //var_dump($chat->save());
        //print_r($chat->find('id_chat=1'));
        //$chat->attributes=array('chat_to_user'=>'sfsfsefsf', 'chat_from_user'=>'ssssssss','chat_message'=>'sefsefesfesfes', 'chat_created'=>time());
        //$chat->update();
        //var_dump($chat->updateAll(array('chat_message'=>'sb', 'chat_created'=>time()), 'id_chat in(1, 2)'));
        //
        //var_dump(Chat::model()->deleteByPk('a'));
        //var_dump(Chat::model()->delete('id_chats>:id', array(':id'=>8)));
        //
    }
    public function transaction()
    {
        $db=IB::app()->db;
        $transaction=$db->beginTransaction();
        try
        {
            //throw new AppException('sssssssss');
            $db->createCommand('delete from chat where id_chat=17')->execute();
            $db->createCommand('delete from chat where id_chat=4')->execute();
            $transaction->commit();
        }
        catch(Exception $e)
        {
            $transaction->rollback();
        }
    }
    public function log()
    {
        /*try
        {
            throw new AppException('sfesfes');
        }
        catch(AppException $e)
        {
            var_dump($e->getMessage());
        }*/
        IB::log('Undefined Action log');
    }
    public function url()
    {
        //var_dump($_SERVER);
       $this->redirect('log', array('id'=>5, 'max'=>15));
    }

    public function memcache()
    {
        var_dump(IB::app()->memcache);
    }
}
